import { apiClient } from "../services/api";

export const cartService = {
    /**
     * Obter carrinho do usuário
     * GET /api/cart
     */
    async getCart() {
        const response = await apiClient.get("/cart");
        return response.data;
    },

    /**
     * Adicionar produto ao carrinho
     * POST /api/cart/add
     */
    async addToCart(productId, quantity = 1, customization = null) {
        const response = await apiClient.post("/cart/add", {
            product_id: productId,
            quantity,
            customization,
        });
        return response.data;
    },

    /**
     * Atualizar quantidade do item
     * PUT /api/cart/{id}
     */
    async updateCartItem(cartItemId, quantity) {
        const response = await apiClient.put(`/cart/${cartItemId}`, {
            quantity,
        });
        return response.data;
    },

    /**
     * Remover item do carrinho
     * DELETE /api/cart/{id}
     */
    async removeFromCart(cartItemId) {
        const response = await apiClient.delete(`/cart/${cartItemId}`);
        return response.data;
    },

    /**
     * Limpar carrinho
     * DELETE /api/cart
     */
    async clearCart() {
        const response = await apiClient.delete("/cart");
        return response.data;
    },

    /**
     * Aplicar cupom de desconto
     * POST /api/cart/apply-coupon
     */
    async applyCoupon(couponCode) {
        const response = await apiClient.post("/cart/apply-coupon", {
            coupon_code: couponCode,
        });
        return response.data;
    },
};
