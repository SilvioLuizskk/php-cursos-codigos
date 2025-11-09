import { apiClient } from "@/services/api";

const cartService = {
    /**
     * Obter carrinho do usuário
     */
    async getCart() {
        const response = await apiClient.get("/cart");
        return response.data;
    },

    /**
     * Obter contagem de itens no carrinho
     */
    async getCartCount() {
        const response = await apiClient.get("/cart/count");
        return response.data;
    },

    /**
     * Adicionar item ao carrinho
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
     * Atualizar item do carrinho
     */
    async updateCartItem(cartItemId, quantity) {
        const response = await apiClient.put(`/cart/${cartItemId}`, {
            quantity,
        });
        return response.data;
    },

    /**
     * Remover item do carrinho
     */
    async removeCartItem(cartItemId) {
        const response = await apiClient.delete(`/cart/${cartItemId}`);
        return response.data;
    },

    /**
     * Aplicar cupom de desconto
     */
    async applyCoupon(couponCode) {
        const response = await apiClient.post("/cart/apply-coupon", {
            coupon_code: couponCode,
        });
        return response.data;
    },

    /**
     * Remover cupom de desconto
     */
    async removeCoupon() {
        const response = await apiClient.delete("/cart/remove-coupon");
        return response.data;
    },
};

export { cartService };
