import axios from "axios";
// Avoid calling composables (useNotification/useAuth) here because
// this file runs outside of component setup() and calling inject()
// will emit warnings. Use safe fallbacks that read localStorage and
// emit a global event for notifications instead.

const API_BASE_URL =
    import.meta.env.VITE_API_URL || "http://127.0.0.1:8100/api";

// Debug: mostrar a baseURL usada pelo cliente API para evitar confusões de CORS/URL
console.log("[api] API_BASE_URL:", API_BASE_URL);

// Origem da API (sem o sufixo /api) — útil para montar URLs de assets retornadas pelo backend
export const API_ORIGIN = API_BASE_URL.replace(/\/api\/?$/, "");

/**
 * Resolve um caminho de asset retornado pelo backend para uma URL completa.
 * - Se já for uma URL absoluta (http/https) retorna como está.
 * - Se for um caminho relativo (ex: /storage/...) prefixa com a origem da API.
 */
export function resolveAssetUrl(path) {
    if (!path) return path;
    if (typeof path !== "string") return path;
    if (path.startsWith("http://") || path.startsWith("https://")) return path;
    // Garantir que exista uma / entre a origem e o caminho
    return API_ORIGIN + (path.startsWith("/") ? path : `/${path}`);
}

// Configurar cliente axios
export const apiClient = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
    timeout: 30000, // Aumentar timeout para requests mais longos
    withCredentials: true, // Necessário para Sanctum (cookies)
});

// Variáveis para controle de refresh
let isRefreshing = false;
let failedQueue = [];

const processQueue = (error, token = null) => {
    failedQueue.forEach(({ resolve, reject }) => {
        if (error) {
            reject(error);
        } else {
            resolve(token);
        }
    });

    failedQueue = [];
};

// Request interceptor - adicionar token de autenticação
apiClient.interceptors.request.use(
    (config) => {
        // Centralizar leitura do token pelo useAuth, se possível
        let token = null;
        // Ler token diretamente do localStorage (seguro fora de setup)
        token = localStorage.getItem("auth_token");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        // Adicionar timestamp para evitar cache
        if (config.method === "get") {
            config.params = {
                ...config.params,
                _t: Date.now(),
            };
            // Adicionar headers para forçar refresh e evitar cache
            config.headers = {
                ...config.headers,
                "Cache-Control": "no-cache, no-store, must-revalidate",
                "Pragma": "no-cache",
                "Expires": "0",
            };
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    },
);

// Response interceptor - tratamento de erros e refresh de token
apiClient.interceptors.response.use(
    (response) => {
        return response;
    },
    async (error) => {
        const originalRequest = error.config;

        // Se for erro 401 e não for uma tentativa de retry
        if (error.response?.status === 401 && !originalRequest._retry) {
            // Se for endpoint de login/register, não tentar refresh
            if (
                originalRequest.url?.includes("/auth/login") ||
                originalRequest.url?.includes("/auth/register")
            ) {
                return Promise.reject(error);
            }

            // Se já está fazendo refresh, adicionar à fila
            if (isRefreshing) {
                return new Promise((resolve, reject) => {
                    failedQueue.push({ resolve, reject });
                })
                    .then((token) => {
                        originalRequest.headers.Authorization = `Bearer ${token}`;
                        return apiClient(originalRequest);
                    })
                    .catch((err) => {
                        return Promise.reject(err);
                    });
            }

            originalRequest._retry = true;
            isRefreshing = true;

            try {
                // Tentar renovar token
                const refreshResponse = await axios.post(
                    `${API_BASE_URL}/auth/refresh`,
                    {},
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem("auth_token")}`,
                        },
                    },
                );

                const { token, user } = refreshResponse.data;

                // Atualizar token no localStorage
                localStorage.setItem("auth_token", token);
                if (user) {
                    localStorage.setItem("auth_user", JSON.stringify(user));
                }

                // Processar fila de requests que falharam
                processQueue(null, token);

                // Atualizar header da requisição original
                originalRequest.headers.Authorization = `Bearer ${token}`;

                // Retentar requisição original
                return apiClient(originalRequest);
            } catch (refreshError) {
                // Se falhar ao renovar token, limpar estado local e forçar logout
                processQueue(refreshError, null);
                localStorage.removeItem("auth_token");
                localStorage.removeItem("auth_user");
                // Redirecionar para login se não estiver já lá
                if (!window.location.pathname.includes("/login")) {
                    window.location.href = "/login";
                }
                return Promise.reject(refreshError);
            } finally {
                isRefreshing = false;
            }
        }

        // Implementar retry para erros de rede e 5xx
        if (shouldRetry(error) && !originalRequest._retryCount) {
            originalRequest._retryCount = 0;
        }

        if (shouldRetry(error) && originalRequest._retryCount < 3) {
            originalRequest._retryCount++;

            // Delay exponencial: 1s, 2s, 4s
            const delay = Math.pow(2, originalRequest._retryCount) * 1000;

            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve(apiClient(originalRequest));
                }, delay);
            });
        }

        // Tratamento de outros erros HTTP

        // Notificação global de erro amigável (usar fallback seguro)
        const showNotificationSafe = (message, type = "error") => {
            // Permite a aplicação registrar um handler global: window.__APP_SHOW_NOTIFICATION__
            if (
                window &&
                typeof window.__APP_SHOW_NOTIFICATION__ === "function"
            ) {
                try {
                    window.__APP_SHOW_NOTIFICATION__({ message, type });
                    return;
                } catch (e) {
                    // fallthrough to console
                }
            }
            // Fallbacks: console + alert (somente em dev)
            if (process.env.NODE_ENV === "development") {
                // eslint-disable-next-line no-alert
                alert(`${type.toUpperCase()}: ${message}`);
            } else {
                console[type === "error" ? "error" : "log"](
                    `${type}: ${message}`,
                );
            }
        };

        if (error.response) {
            switch (error.response.status) {
                case 403:
                    showNotificationSafe("Acesso negado", "error");
                    break;
                case 404:
                    showNotificationSafe("Recurso não encontrado", "error");
                    break;
                case 422:
                    // Erros de validação - não notificar, deixar o componente tratar
                    break;
                case 429:
                    showNotificationSafe(
                        "Muitas requisições. Tente novamente em alguns segundos.",
                        "error",
                    );
                    break;
                case 500:
                case 502:
                case 503:
                case 504:
                    showNotificationSafe(
                        "Erro interno do servidor. Tente novamente.",
                        "error",
                    );
                    break;
                default:
                    showNotificationSafe(
                        error.response.data?.message || error.message,
                        "error",
                    );
            }
        } else if (error.request) {
            showNotificationSafe(
                "Erro de conexão. Verifique sua internet.",
                "error",
            );
        } else {
            showNotificationSafe("Erro inesperado: " + error.message, "error");
        }

        return Promise.reject(error);
    },
);

// Função para determinar se deve fazer retry
function shouldRetry(error) {
    // Retry em erros de rede
    if (!error.response) {
        return true;
    }

    // Retry em erros 5xx (servidor)
    if (error.response.status >= 500) {
        return true;
    }

    // Retry em 429 (rate limit)
    if (error.response.status === 429) {
        return true;
    }

    return false;
}

// Interceptador para debug (apenas em desenvolvimento)
if (import.meta.env.DEV) {
    apiClient.interceptors.request.use((config) => {
        console.log(`🚀 ${config.method?.toUpperCase()} ${config.url}`, {
            params: config.params,
            data: config.data,
        });
        return config;
    });

    apiClient.interceptors.response.use(
        (response) => {
            console.log(
                `✅ ${response.config.method?.toUpperCase()} ${response.config.url}`,
                {
                    status: response.status,
                    data: response.data,
                },
            );
            return response;
        },
        (error) => {
            console.error(
                `❌ ${error.config?.method?.toUpperCase()} ${error.config?.url}`,
                {
                    status: error.response?.status,
                    message: error.response?.data?.message || error.message,
                },
            );
            return Promise.reject(error);
        },
    );
}

// Função para obter CSRF token (necessário para Sanctum)
export const getCsrfToken = async () => {
    try {
        await axios.get(
            `${API_BASE_URL.replace("/api", "")}/sanctum/csrf-cookie`,
            {
                withCredentials: true,
            },
        );
    } catch (error) {
        console.warn("Erro ao obter CSRF token:", error);
    }
};
