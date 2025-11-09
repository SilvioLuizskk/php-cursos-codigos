<template>
    <div
        class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2"
    >
        <!-- Imagem -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 h-64"
        >
            <img
                :src="product.image || '/placeholder-image.jpg'"
                :alt="product.name"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            />

            <!-- Badge de desconto -->
            <div
                v-if="product.discount_price"
                class="absolute top-3 right-3 bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg"
            >
                -{{ discountPercentage }}%
            </div>

            <!-- Badge "Novo" ou "Popular" -->
            <div
                class="absolute top-3 left-3 bg-gradient-to-r from-green-400 to-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg"
            >
                ✨ Popular
            </div>

            <!-- Overlay com botões de ação -->
            <div
                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center"
            >
                <div
                    class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex gap-2"
                >
                    <button
                        class="bg-white text-gray-800 p-2 rounded-full shadow-lg hover:bg-gray-100 transition-colors"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            ></path>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            ></path>
                        </svg>
                    </button>
                    <button
                        class="bg-white text-red-500 p-2 rounded-full shadow-lg hover:bg-gray-100 transition-colors"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="p-6">
            <!-- Nome -->
            <h3
                class="font-bold text-lg text-gray-900 mb-2 group-hover:text-purple-600 transition-colors"
            >
                {{ product.name }}
            </h3>

            <!-- Rating -->
            <div class="flex items-center mb-3">
                <div class="flex text-yellow-400">
                    <span v-for="i in 5" :key="i">
                        <svg
                            :class="{
                                'opacity-30':
                                    i > Math.round(product.rating || 4),
                            }"
                            class="w-4 h-4 fill-current"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"
                            />
                        </svg>
                    </span>
                </div>
                <span class="text-sm text-gray-500 ml-2 font-medium">
                    ({{
                        product.review_count ||
                        Math.floor(Math.random() * 50) + 10
                    }})
                </span>
            </div>

            <!-- Preço -->
            <div class="mb-4 flex items-baseline gap-2">
                <span
                    class="text-2xl font-black text-transparent bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text"
                >
                    R$
                    {{
                        formatPrice(
                            product.discount_price || product.price || 29.9
                        )
                    }}
                </span>
                <span
                    v-if="product.discount_price"
                    class="text-sm text-gray-400 line-through font-medium"
                >
                    R$ {{ formatPrice(product.price || 39.9) }}
                </span>
            </div>

            <!-- Botão -->
            <button
                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 px-6 rounded-xl font-bold hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                @click="handleAddToCart"
                :disabled="loading || product.stock === 0"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5L17 18"
                    ></path>
                </svg>
                <span v-if="loading">Adicionando...</span>
                <span v-else>Adicionar ao Carrinho</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { useCart } from "@/composables/useCart";
import { useAuth } from "@/composables/useAuth";
import { useRouter } from "vue-router";
import { useNotification } from "@/composables/useNotification";

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const loading = ref(false);
const { addToCart } = useCart();
const { isAuthenticated } = useAuth();
const router = useRouter();
const { showSuccess, showError } = useNotification();

const discountPercentage = computed(() => {
    if (!props.product.discount_price) return 0;
    return Math.round(
        ((props.product.price - props.product.discount_price) /
            props.product.price) *
            100
    );
});

function formatPrice(value) {
    return new Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "BRL",
    }).format(value);
}

async function handleAddToCart() {
    if (!isAuthenticated.value) {
        router.push({ name: "Login", query: { redirect: "/produtos" } });
        return;
    }
    loading.value = true;
    try {
        await addToCart(props.product.id, 1);
        showSuccess("Produto adicionado ao carrinho!");
    } catch (error) {
        showError(
            error.response?.data?.message || "Erro ao adicionar ao carrinho"
        );
    } finally {
        loading.value = false;
    }
}
</script>
