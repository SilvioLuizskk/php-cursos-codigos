import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { authService } from "@/services/authService";
import { useNotification } from "@/composables/useNotification";

export function useAuth() {
    const router = useRouter();
    const { showNotification } = useNotification();

    const user = ref(null);
    const loading = ref(false);
    const error = ref(null);
    const token = ref(localStorage.getItem("auth_token"));

    const isAuthenticated = computed(() => !!token.value && !!user.value);

    // Inicializar usuário do localStorage
    const initUser = () => {
        const storedUser = localStorage.getItem("auth_user");
        if (storedUser) {
            try {
                user.value = JSON.parse(storedUser);
            } catch (e) {
                localStorage.removeItem("auth_user");
                localStorage.removeItem("auth_token");
            }
        }
    };

    // Fazer login
    async function login(email, password, remember = false) {
        loading.value = true;
        error.value = null;
        // Sempre faz logout antes de novo login para evitar dados antigos
        await logout();
        try {
            const response = await authService.login({
                email,
                password,
                remember,
            });
            user.value = response.user;
            token.value = response.token;
            // Salvar no localStorage
            localStorage.setItem("auth_token", response.token);
            localStorage.setItem("auth_user", JSON.stringify(response.user));
            showNotification("Login realizado com sucesso!", "success");
            // Redirecionar para página anterior ou home
            const redirect = router.currentRoute.value.query.redirect || "/";
            router.push(redirect);
            return response;
        } catch (err) {
            error.value = err.response?.data?.message || "Erro ao fazer login";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Fazer registro
    async function register(userData) {
        loading.value = true;
        error.value = null;

        try {
            const response = await authService.register(userData);

            user.value = response.user;
            token.value = response.token;

            // Salvar no localStorage
            localStorage.setItem("auth_token", response.token);
            localStorage.setItem("auth_user", JSON.stringify(response.user));

            showNotification("Conta criada com sucesso!", "success");

            // Redirecionar para home
            router.push("/");

            return response;
        } catch (err) {
            error.value = err.response?.data?.message || "Erro ao criar conta";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Fazer logout
    async function logout() {
        loading.value = true;

        try {
            // Tentar fazer logout no servidor
            if (token.value) {
                await authService.logout();
            }
        } catch (err) {
            console.error("Erro ao fazer logout no servidor:", err);
        } finally {
            // Limpar dados locais mesmo se falhar no servidor
            user.value = null;
            token.value = null;

            localStorage.removeItem("auth_token");
            localStorage.removeItem("auth_user");

            showNotification("Logout realizado com sucesso!", "success");
            router.push("/login");

            loading.value = false;
        }
    }

    // Renovar token
    async function refreshToken() {
        try {
            const response = await authService.refresh();

            token.value = response.token;
            user.value = response.user;

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
        if (!token.value) return null;

        try {
            const response = await authService.getUser();
            user.value = response.user;
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
        if (!token.value) return false;

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
        token.value = newToken;
        localStorage.setItem("auth_token", newToken);
    }

    // Definir usuário
    function setUser(newUser) {
        user.value = newUser;
        localStorage.setItem("auth_user", JSON.stringify(newUser));
    }

    // Inicializar ao criar o composable
    initUser();

    return {
        user,
        loading,
        error,
        token,
        isAuthenticated,
        login,
        register,
        logout,
        refreshToken,
        fetchUser,
        checkAuth,
        setToken,
        setUser,
    };
}
