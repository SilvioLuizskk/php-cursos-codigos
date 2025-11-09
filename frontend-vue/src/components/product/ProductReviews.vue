<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                Avaliações dos Clientes
            </h2>
            <button
                v-if="canReview"
                @click="showReviewForm = !showReviewForm"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors"
            >
                <i class="fas fa-star mr-2"></i>
                Escrever Avaliação
            </button>
        </div>

        <!-- Review Form -->
        <div
            v-if="showReviewForm"
            class="border border-gray-200 rounded-lg p-4 mb-6"
        >
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                Escrever Avaliação
            </h3>
            <form @submit.prevent="submitReview" class="space-y-4">
                <!-- Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"
                        >Avaliação</label
                    >
                    <div class="flex space-x-1">
                        <button
                            v-for="star in 5"
                            :key="star"
                            type="button"
                            @click="newReview.rating = star"
                            class="text-2xl focus:outline-none"
                            :class="
                                star <= newReview.rating
                                    ? 'text-yellow-400'
                                    : 'text-gray-300'
                            "
                        >
                            <i class="fas fa-star"></i>
                        </button>
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label
                        for="review-title"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Título da Avaliação
                    </label>
                    <input
                        id="review-title"
                        v-model="newReview.title"
                        type="text"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Resuma sua experiência..."
                    />
                </div>

                <!-- Comment -->
                <div>
                    <label
                        for="review-comment"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Comentário
                    </label>
                    <textarea
                        id="review-comment"
                        v-model="newReview.comment"
                        required
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Conte-nos sobre sua experiência com este produto..."
                    ></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <button
                        type="button"
                        @click="showReviewForm = false"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        :disabled="submittingReview"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 transition-colors"
                    >
                        {{
                            submittingReview
                                ? "Enviando..."
                                : "Enviar Avaliação"
                        }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Reviews Summary -->
        <div
            v-if="reviews.length > 0"
            class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8"
        >
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900">
                    {{ averageRating.toFixed(1) }}
                </div>
                <div class="flex justify-center mt-2">
                    <div class="flex">
                        <i
                            v-for="star in 5"
                            :key="star"
                            class="fas fa-star text-lg"
                            :class="
                                star <= Math.round(averageRating)
                                    ? 'text-yellow-400'
                                    : 'text-gray-300'
                            "
                        ></i>
                    </div>
                </div>
                <div class="text-sm text-gray-600 mt-1">
                    {{ reviews.length }} avaliações
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="space-y-2">
                    <div
                        v-for="rating in [5, 4, 3, 2, 1]"
                        :key="rating"
                        class="flex items-center"
                    >
                        <span class="text-sm text-gray-600 w-8">{{
                            rating
                        }}</span>
                        <i class="fas fa-star text-yellow-400 text-sm mr-2"></i>
                        <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                            <div
                                class="bg-yellow-400 h-2 rounded-full"
                                :style="{
                                    width: `${getRatingPercentage(rating)}%`,
                                }"
                            ></div>
                        </div>
                        <span class="text-sm text-gray-600 w-8">{{
                            getRatingCount(rating)
                        }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div v-if="reviews.length > 0" class="space-y-6">
            <div
                v-for="review in paginatedReviews"
                :key="review.id"
                class="border border-gray-200 rounded-lg p-4"
            >
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center"
                        >
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">
                                {{ review.user?.name || "Usuário Anônimo" }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ formatDate(review.created_at) }}
                            </div>
                        </div>
                    </div>
                    <div class="flex">
                        <i
                            v-for="star in 5"
                            :key="star"
                            class="fas fa-star text-lg"
                            :class="
                                star <= review.rating
                                    ? 'text-yellow-400'
                                    : 'text-gray-300'
                            "
                        ></i>
                    </div>
                </div>

                <div v-if="review.title" class="font-medium text-gray-900 mb-2">
                    {{ review.title }}
                </div>
                <p class="text-gray-700 mb-3">{{ review.comment }}</p>

                <!-- Helpful Button -->
                <div class="flex items-center space-x-4">
                    <button
                        @click="markHelpful(review.id)"
                        class="flex items-center space-x-1 text-sm text-gray-600 hover:text-blue-600 transition-colors"
                        :disabled="review.helpfulClicked"
                    >
                        <i class="fas fa-thumbs-up"></i>
                        <span>Útil ({{ review.helpful_count || 0 }})</span>
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex justify-center space-x-2">
                <button
                    @click="currentPage--"
                    :disabled="currentPage <= 1"
                    class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200 disabled:opacity-50"
                >
                    <i class="fas fa-chevron-left mr-1"></i>
                    Anterior
                </button>

                <span class="px-3 py-2 text-sm text-gray-700">
                    Página {{ currentPage }} de {{ totalPages }}
                </span>

                <button
                    @click="currentPage++"
                    :disabled="currentPage >= totalPages"
                    class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200 disabled:opacity-50"
                >
                    Próximo
                    <i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>

        <!-- No Reviews -->
        <div v-else class="text-center py-12">
            <i class="fas fa-star text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                Nenhuma avaliação ainda
            </h3>
            <p class="text-gray-600">Seja o primeiro a avaliar este produto!</p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useAuth } from "@/composables/useAuth";
import { useNotification } from "@/composables/useNotification";
import reviewService from "@/services/reviewService";

const props = defineProps({
    productId: {
        type: [String, Number],
        required: true,
    },
});

const { user } = useAuth();
const { showNotification } = useNotification();

// Dados reativos
const reviews = ref([]);
const loading = ref(false);
const showReviewForm = ref(false);
const submittingReview = ref(false);
const currentPage = ref(1);
const reviewsPerPage = 5;

const newReview = ref({
    rating: 0,
    title: "",
    comment: "",
});

// Computed
const canReview = computed(() => {
    return user.value && !hasUserReviewed.value;
});

const hasUserReviewed = computed(() => {
    return reviews.value.some((review) => review.user_id === user.value?.id);
});

const averageRating = computed(() => {
    if (reviews.value.length === 0) return 0;
    const sum = reviews.value.reduce((acc, review) => acc + review.rating, 0);
    return sum / reviews.value.length;
});

const paginatedReviews = computed(() => {
    const start = (currentPage.value - 1) * reviewsPerPage;
    const end = start + reviewsPerPage;
    return reviews.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(reviews.value.length / reviewsPerPage);
});

// Métodos
const fetchReviews = async () => {
    try {
        loading.value = true;
        const response = await reviewService.getProductReviews(props.productId);
        reviews.value = response.data || [];
    } catch (error) {
        console.error("Erro ao carregar avaliações:", error);
        showNotification("Erro ao carregar avaliações", "error");
    } finally {
        loading.value = false;
    }
};

const submitReview = async () => {
    if (!user.value) {
        showNotification("Você precisa estar logado para avaliar", "error");
        return;
    }

    if (newReview.value.rating === 0) {
        showNotification("Por favor, selecione uma avaliação", "error");
        return;
    }

    try {
        submittingReview.value = true;
        await reviewService.createReview({
            product_id: props.productId,
            rating: newReview.value.rating,
            title: newReview.value.title,
            comment: newReview.value.comment,
        });

        showNotification("Avaliação enviada com sucesso!", "success");
        showReviewForm.value = false;
        newReview.value = { rating: 0, title: "", comment: "" };
        await fetchReviews();
    } catch (error) {
        console.error("Erro ao enviar avaliação:", error);
        showNotification("Erro ao enviar avaliação", "error");
    } finally {
        submittingReview.value = false;
    }
};

const markHelpful = async (reviewId) => {
    try {
        await reviewService.markHelpful(reviewId);
        const review = reviews.value.find((r) => r.id === reviewId);
        if (review) {
            review.helpful_count = (review.helpful_count || 0) + 1;
            review.helpfulClicked = true;
        }
        showNotification("Obrigado pelo feedback!", "success");
    } catch (error) {
        console.error("Erro ao marcar como útil:", error);
        showNotification("Erro ao marcar avaliação como útil", "error");
    }
};

const getRatingCount = (rating) => {
    return reviews.value.filter((review) => review.rating === rating).length;
};

const getRatingPercentage = (rating) => {
    if (reviews.value.length === 0) return 0;
    return (getRatingCount(rating) / reviews.value.length) * 100;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("pt-BR", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

// Watchers
watch(
    () => props.productId,
    () => {
        if (props.productId) {
            fetchReviews();
            currentPage.value = 1;
        }
    },
);

// Lifecycle
onMounted(() => {
    if (props.productId) {
        fetchReviews();
    }
});
</script>

<style scoped>
/* Estilos adicionais se necessário */
</style>
