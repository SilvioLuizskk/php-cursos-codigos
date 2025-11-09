import apiClient from "./axios";

export const orderService = {
  /**
   * Listar pedidos do usuário
   * GET /api/orders
   */
  async getOrders(page = 1, perPage = 10) {
    const response = await apiClient.get("/orders", {
      params: { page, per_page: perPage },
    });
    return response.data;
  },

  /**
   * Obter detalhes de um pedido
   * GET /api/orders/{id}
   */
  async getOrder(id) {
    const response = await apiClient.get(`/orders/${id}`);
    return response.data;
  },

  /**
   * Criar novo pedido
   * POST /api/orders
   */
  async createOrder(data) {
    const response = await apiClient.post("/orders", data);
    return response.data;
  },

  /**
   * Cancelar pedido
   * POST /api/orders/{id}/cancel
   */
  async cancelOrder(id, reason = null) {
    const response = await apiClient.post(`/orders/${id}/cancel`, { reason });
    return response.data;
  },

  /**
   * Obter rastreamento de pedido
   * GET /api/orders/{id}/tracking
   */
  async getTracking(id) {
    const response = await apiClient.get(`/orders/${id}/tracking`);
    return response.data;
  },
};
