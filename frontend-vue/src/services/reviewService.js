import { apiClient } from "./api.js";

const reviewService = {
    // Buscar reviews de um produto
    async getProductReviews(productId) {
        try {
            const response = await apiClient.get(
                `/products/${productId}/reviews`,
            );
            return response.data;
        } catch (error) {
            console.error("Erro ao buscar reviews:", error);
            throw error;
        }
    },

    // Criar uma nova review
    async createReview(productId, reviewData) {
        try {
            const response = await apiClient.post(
                `/products/${productId}/reviews`,
                reviewData,
            );
            return response.data;
        } catch (error) {
            console.error("Erro ao criar review:", error);
            throw error;
        }
    },

    // Atualizar uma review existente
    async updateReview(reviewId, reviewData) {
        try {
            const response = await apiClient.put(
                `/reviews/${reviewId}`,
                reviewData,
            );
            return response.data;
        } catch (error) {
            console.error("Erro ao atualizar review:", error);
            throw error;
        }
    },

    // Deletar uma review
    async deleteReview(reviewId) {
        try {
            const response = await apiClient.delete(`/reviews/${reviewId}`);
            return response.data;
        } catch (error) {
            console.error("Erro ao deletar review:", error);
            throw error;
        }
    },

    // Verificar se o usuário pode avaliar o produto
    async canReviewProduct(productId) {
        try {
            const response = await apiClient.get(
                `/products/${productId}/can-review`,
            );
            return response.data.canReview;
        } catch (error) {
            console.error("Erro ao verificar se pode avaliar:", error);
            return false;
        }
    },

    // Buscar review do usuário atual para um produto
    async getUserReview(productId) {
        try {
            const response = await apiClient.get(
                `/products/${productId}/user-review`,
            );
            return response.data;
        } catch (error) {
            console.error("Erro ao buscar review do usuário:", error);
            return null;
        }
    },
};

export default reviewService;
