import { apiClient } from "@/services/api";

export const userService = {
    /**
     * Obter perfil do usuário atual
     */
    async getProfile() {
        const response = await apiClient.get("/user/profile");
        return response.data;
    },

    /**
     * Atualizar perfil do usuário
     */
    async updateProfile(userData) {
        const response = await apiClient.put("/user/profile", userData);
        return response.data;
    },

    /**
     * Alterar senha
     */
    async changePassword(passwordData) {
        const response = await apiClient.put(
            "/user/change-password",
            passwordData,
        );
        return response.data;
    },

    /**
     * Enviar email de verificação
     */
    async sendEmailVerification() {
        const response = await apiClient.post(
            "/user/email/verification-notification",
        );
        return response.data;
    },

    /**
     * Verificar email
     */
    async verifyEmail(token) {
        const response = await apiClient.post("/user/email/verify", { token });
        return response.data;
    },

    /**
     * Obter endereços do usuário
     */
    async getAddresses() {
        const response = await apiClient.get("/user/addresses");
        return response.data;
    },

    /**
     * Criar novo endereço
     */
    async createAddress(addressData) {
        const response = await apiClient.post("/user/addresses", addressData);
        return response.data;
    },

    /**
     * Atualizar endereço
     */
    async updateAddress(id, addressData) {
        const response = await apiClient.put(
            `/user/addresses/${id}`,
            addressData,
        );
        return response.data;
    },

    /**
     * Deletar endereço
     */
    async deleteAddress(id) {
        const response = await apiClient.delete(`/user/addresses/${id}`);
        return response.data;
    },

    /**
     * Definir endereço como padrão
     */
    async setDefaultAddress(id) {
        const response = await apiClient.put(`/user/addresses/${id}/default`);
        return response.data;
    },

    /**
     * Obter lista de desejos
     */
    async getWishlist() {
        const response = await apiClient.get("/user/wishlist");
        return response.data;
    },

    /**
     * Adicionar produto à lista de desejos
     */
    async addToWishlist(productId) {
        const response = await apiClient.post("/user/wishlist", {
            product_id: productId,
        });
        return response.data;
    },

    /**
     * Remover produto da lista de desejos
     */
    async removeFromWishlist(productId) {
        const response = await apiClient.delete(`/user/wishlist/${productId}`);
        return response.data;
    },

    /**
     * Verificar se produto está na lista de desejos
     */
    async isInWishlist(productId) {
        const response = await apiClient.get(
            `/user/wishlist/check/${productId}`,
        );
        return response.data;
    },

    /**
     * Obter notificações do usuário
     */
    async getNotifications(params = {}) {
        const response = await apiClient.get("/user/notifications", { params });
        return response.data;
    },

    /**
     * Marcar notificação como lida
     */
    async markNotificationAsRead(id) {
        const response = await apiClient.put(`/user/notifications/${id}/read`);
        return response.data;
    },

    /**
     * Marcar todas as notificações como lidas
     */
    async markAllNotificationsAsRead() {
        const response = await apiClient.put(
            "/user/notifications/mark-all-read",
        );
        return response.data;
    },

    /**
     * Deletar notificação
     */
    async deleteNotification(id) {
        const response = await apiClient.delete(`/user/notifications/${id}`);
        return response.data;
    },

    /**
     * Obter configurações de notificação
     */
    async getNotificationSettings() {
        const response = await apiClient.get("/user/notification-settings");
        return response.data;
    },

    /**
     * Atualizar configurações de notificação
     */
    async updateNotificationSettings(settings) {
        const response = await apiClient.put(
            "/user/notification-settings",
            settings,
        );
        return response.data;
    },
};
