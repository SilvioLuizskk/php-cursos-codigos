import { apiClient } from "@/services/api";

export const checkoutService = {
    /**
     * Iniciar checkout
     */
    async initiateCheckout(cartData) {
        const response = await apiClient.post("/checkout/initiate", cartData);
        return response.data;
    },

    /**
     * Calcular frete
     */
    async calculateShipping(addressData) {
        const response = await apiClient.post(
            "/checkout/shipping",
            addressData,
        );
        return response.data;
    },

    /**
     * Processar pagamento
     */
    async processPayment(paymentData) {
        const response = await apiClient.post("/checkout/payment", paymentData);
        return response.data;
    },

    /**
     * Finalizar pedido
     */
    async completeOrder(orderData) {
        const response = await apiClient.post("/checkout/complete", orderData);
        return response.data;
    },

    /**
     * Obter métodos de pagamento disponíveis
     */
    async getPaymentMethods() {
        const response = await apiClient.get("/checkout/payment-methods");
        return response.data;
    },

    /**
     * Validar cupom de desconto
     */
    async validateCoupon(couponCode) {
        const response = await apiClient.post("/checkout/validate-coupon", {
            code: couponCode,
        });
        return response.data;
    },

    /**
     * Obter configurações de checkout
     */
    async getCheckoutSettings() {
        const response = await apiClient.get("/checkout/settings");
        return response.data;
    },
};
