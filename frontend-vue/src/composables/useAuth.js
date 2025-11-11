import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { authService } from "@/services/authService";
import { useNotification } from "@/composables/useNotification";

// Estado global compartilhado (singleton)
let authState = null;

function getAuthState() {
    if (!authState) {
        authState = {
            user: ref(null),
            loading: ref(false),
            error: ref(null),
            token: ref(localStorage.getItem("auth_token")),
            isAuthenticated: ref(false), // Agora é um ref ao invés de computed
        };

        // Função para atualizar o estado de autenticação
        const updateAuthState = () => {
            authState.isAuthenticated.value =
                !!authState.token.value && !!authState.user.value;
            console.log(
                "🔄 Auth state updated:",
                authState.isAuthenticated.value,
                {
                    token: !!authState.token.value,
                    user: !!authState.user.value,
                },
            );
        };

        // Inicializar usuário do localStorage
        const initUser = () => {
            const storedUser = localStorage.getItem("auth_user");
            if (storedUser) {
                try {
                    const userData = JSON.parse(storedUser);
                    authState.user.value = userData;
                } catch (e) {
                    console.error("❌ Error parsing stored user:", e);
                    localStorage.removeItem("auth_user");
                    localStorage.removeItem("auth_token");
                    authState.token.value = null;
                }
            }
            updateAuthState(); // Atualizar estado após inicialização
        };

        // Inicializar
        initUser();
    }
    return authState;
}

export function useAuth() {
    const router = useRouter();
    const { showNotification } = useNotification();
    const state = getAuthState();

    // Função para atualizar o estado de autenticação
    const updateAuthState = () => {
        state.isAuthenticated.value = !!state.token.value && !!state.user.value;
    };

    // Fazer login
    async function login(email, password, remember = false) {
        state.loading.value = true;
        state.error.value = null;
        // Sempre faz logout antes de novo login para evitar dados antigos
        await logout();
        try {
            const response = await authService.login({
                email,
                password,
                remember,
            });
            console.log("✅ Login successful:", response);
            console.log("✅ Login successful:", response);
            state.user.value = response.user;
            state.token.value = response.token;
            // Salvar no localStorage
            localStorage.setItem("auth_token", response.token);
            localStorage.setItem("auth_user", JSON.stringify(response.user));
            console.log(
                "💾 Data saved to localStorage - token:",
                !!response.token,
                "user:",
                !!response.user,
            );
            updateAuthState(); // Atualizar estado após login
            showNotification("Login realizado com sucesso!", "success");
            // Redirecionar para página anterior ou home
            const redirect = router.currentRoute.value.query.redirect || "/";
            router.push(redirect);
            return response;
        } catch (err) {
            state.error.value =
                err.response?.data?.message || "Erro ao fazer login";
            showNotification(state.error.value, "error");
            throw err;
        } finally {
            state.loading.value = false;
        }
    }

    // Fazer registro
    async function register(userData) {
        state.loading.value = true;
        state.error.value = null;

        try {
            const response = await authService.register(userData);

            state.user.value = response.user;
            state.token.value = response.token;

            // Salvar no localStorage
            localStorage.setItem("auth_token", response.token);
            localStorage.setItem("auth_user", JSON.stringify(response.user));

            updateAuthState(); // Atualizar estado após registro
            showNotification("Conta criada com sucesso!", "success");

            // Redirecionar para home
            router.push("/");

            return response;

            return response;
        } catch (err) {
            state.error.value =
                err.response?.data?.message || "Erro ao criar conta";
            showNotification(state.error.value, "error");
            throw err;
        } finally {
            state.loading.value = false;
        }
    }

    // Fazer logout
    async function logout() {
        state.loading.value = true;

        try {
            // Tentar fazer logout no servidor
            if (state.token.value) {
                await authService.logout();
            }
        } catch (err) {
            console.error("Erro ao fazer logout no servidor:", err);
        } finally {
            // Limpar dados locais mesmo se falhar no servidor
            state.user.value = null;
            state.token.value = null;

            localStorage.removeItem("auth_token");
            localStorage.removeItem("auth_user");

            updateAuthState(); // Atualizar estado após logout
            showNotification("Logout realizado com sucesso!", "success");
            router.push("/login");

            state.loading.value = false;
        }
    }

    // Renovar token
    async function refreshToken() {
        try {
            const response = await authService.refresh();

            state.token.value = response.token;
            state.user.value = response.user;

            localStorage.setItem("auth_token", response.token);
            localStorage.setItem("auth_user", JSON.stringify(response.user));

            return response;
        } catch (err) {
            console.error("Erro ao renovar token:", err);
            await logout();
            throw err;
        }
    }

    // Obter dados do usuário
    async function fetchUser() {
        if (!state.token.value) return null;

        try {
            const response = await authService.getUser();
            state.user.value = response.user;
            localStorage.setItem("auth_user", JSON.stringify(response.user));
            return response.user;
        } catch (err) {
            console.error("Erro ao buscar dados do usuário:", err);
            await logout();
            throw err;
        }
    }

    // Verificar se token é válido
    async function checkAuth() {
        if (!state.token.value) return false;

        try {
            await fetchUser();
            return true;
        } catch (err) {
            await logout();
            return false;
        }
    }

    // Definir token (para quando vem do interceptador)
    function setToken(newToken) {
        state.token.value = newToken;
        localStorage.setItem("auth_token", newToken);
    }

    // Definir usuário
    function setUser(newUser) {
        state.user.value = newUser;
        localStorage.setItem("auth_user", JSON.stringify(newUser));
    }

    // Solicitar reset de senha
    async function forgotPassword(email) {
        state.loading.value = true;
        state.error.value = null;

        try {
            const response = await authService.forgotPassword(email);
            showNotification("Email de recuperação enviado!", "success");
            return response;
        } catch (err) {
            state.error.value =
                err.response?.data?.message ||
                "Erro ao enviar email de recuperação";
            showNotification(state.error.value, "error");
            throw err;
        } finally {
            state.loading.value = false;
        }
    }

    // Redefinir senha
    async function resetPassword(data) {
        state.loading.value = true;
        state.error.value = null;

        try {
            const response = await authService.resetPassword(data);
            showNotification("Senha redefinida com sucesso!", "success");
            return response;
        } catch (err) {
            state.error.value =
                err.response?.data?.message || "Erro ao redefinir senha";
            showNotification(state.error.value, "error");
            throw err;
        } finally {
            state.loading.value = false;
        }
    }

    return {
        user: state.user,
        loading: state.loading,
        error: state.error,
        token: state.token,
        isAuthenticated: state.isAuthenticated,
        login,
        register,
        logout,
        refreshToken,
        fetchUser,
        checkAuth,
        setToken,
        setUser,
        forgotPassword,
        resetPassword,
    };
}
