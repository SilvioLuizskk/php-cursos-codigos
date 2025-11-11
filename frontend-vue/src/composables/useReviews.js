import { ref } from "vue";
import reviewService from "@/services/reviewService";
import { useNotification } from "@/composables/useNotification";

export function useReviews() {
    const { showSuccess, showError } = useNotification();

    const reviews = ref([]);
    const loading = ref(false);
    const submitting = ref(false);

    // Buscar reviews de um produto
    async function fetchProductReviews(productId) {
        loading.value = true;
        try {
            const response = await reviewService.getProductReviews(productId);
            reviews.value = response.data || response;
            return reviews.value;
        } catch (error) {
            showError("Erro ao carregar avaliações");
            console.error("Erro ao buscar reviews:", error);
            throw error;
        } finally {
            loading.value = false;
        }
    }

    // Criar nova review
    async function createReview(productId, reviewData) {
        submitting.value = true;
        try {
            const response = await reviewService.createReview(
                productId,
                reviewData,
            );
            showSuccess("Avaliação enviada com sucesso!");
            return response.data || response;
        } catch (error) {
            const message =
                error.response?.data?.message || "Erro ao enviar avaliação";
            showError(message);
            console.error("Erro ao criar review:", error);
            throw error;
        } finally {
            submitting.value = false;
        }
    }

    // Atualizar review
    async function updateReview(reviewId, reviewData) {
        submitting.value = true;
        try {
            const response = await reviewService.updateReview(
                reviewId,
                reviewData,
            );
            showSuccess("Avaliação atualizada!");
            return response.data || response;
        } catch (error) {
            const message =
                error.response?.data?.message || "Erro ao atualizar avaliação";
            showError(message);
            console.error("Erro ao atualizar review:", error);
            throw error;
        } finally {
            submitting.value = false;
        }
    }

    // Deletar review
    async function deleteReview(reviewId) {
        try {
            await reviewService.deleteReview(reviewId);
            showSuccess("Avaliação removida!");
            return true;
        } catch (error) {
            const message =
                error.response?.data?.message || "Erro ao remover avaliação";
            showError(message);
            console.error("Erro ao deletar review:", error);
            throw error;
        }
    }

    // Marcar review como útil
    async function markReviewHelpful(reviewId) {
        try {
            await reviewService.markHelpful(reviewId);
            showSuccess("Obrigado pelo feedback!");
            return true;
        } catch (error) {
            showError("Erro ao marcar avaliação como útil");
            console.error("Erro ao marcar review como útil:", error);
            throw error;
        }
    }

    return {
        reviews,
        loading,
        submitting,
        fetchProductReviews,
        createReview,
        updateReview,
        deleteReview,
        markReviewHelpful,
    };
}
