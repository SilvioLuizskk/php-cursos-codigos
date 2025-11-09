import { apiClient } from "@/services/api";

const orderService = {
    /**
     * Obter pedidos do usuário
     */
    async getOrders(params = {}) {
        const response = await apiClient.get("/orders", { params });
        return response.data;
    },

    /**
     * Obter estatísticas dos pedidos
     */
    async getOrderStats() {
        const response = await apiClient.get("/orders/stats");
        return response.data;
    },

    /**
     * Criar novo pedido
     */
    async createOrder(orderData) {
        const response = await apiClient.post("/orders", orderData);
        return response.data;
    },

    /**
     * Obter detalhes do pedido
     */
    async getOrder(orderId) {
        const response = await apiClient.get(`/orders/${orderId}`);
        return response.data;
    },

    /**
     * Cancelar pedido
     */
    async cancelOrder(orderId) {
        const response = await apiClient.patch(`/orders/${orderId}/cancel`);
        return response.data;
    },

    /**
     * Obter rastreamento do pedido
     */
    async getOrderTracking(orderId) {
        const response = await apiClient.get(`/orders/${orderId}/tracking`);
        return response.data;
    },
};

export { orderService };
