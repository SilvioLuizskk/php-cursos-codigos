import { apiClient, getCsrfToken } from "@/services/api";

export const authService = {
    /**
     * Fazer login
     */
    async login(credentials) {
        await getCsrfToken(); // Obter CSRF token antes do login
        const response = await apiClient.post("/auth/login", credentials);
        return response.data;
    },

    /**
     * Registrar novo usuário
     */
    async register(userData) {
        const response = await apiClient.post("/auth/register", userData);
        return response.data;
    },

    /**
     * Fazer logout
     */
    async logout() {
        const response = await apiClient.post("/auth/logout");
        return response.data;
    },

    /**
     * Renovar token
     */
    async refresh() {
        const response = await apiClient.post("/auth/refresh");
        return response.data;
    },

    /**
     * Obter dados do usuário autenticado
     */
    async getUser() {
        const response = await apiClient.get("/auth/user");
        return response.data;
    },

    /**
     * Fazer logout em todos os dispositivos
     */
    async logoutAll() {
        const response = await apiClient.post("/auth/logout-all");
        return response.data;
    },

    /**
     * Solicitar reset de senha
     */
    async forgotPassword(email) {
        const response = await apiClient.post("/auth/forgot-password", {
            email,
        });
        return response.data;
    },

    /**
     * Redefinir senha
     */
    async resetPassword(data) {
        const response = await apiClient.post("/auth/reset-password", data);
        return response.data;
    },

    /**
     * Verificar email
     */
    async verifyEmail(token) {
        const response = await apiClient.post("/auth/verify-email", { token });
        return response.data;
    },

    /**
     * Reenviar email de verificação
     */
    async resendVerification() {
        const response = await apiClient.post("/auth/resend-verification");
        return response.data;
    },
};
