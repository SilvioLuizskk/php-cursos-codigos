<template>
    <div
        class="product-detail-page min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <router-link
                            to="/"
                            class="text-gray-700 hover:text-blue-600 transition-colors"
                        >
                            <i class="fas fa-home mr-2"></i>
                            Início
                        </router-link>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i
                                class="fas fa-chevron-right text-gray-400 mx-2"
                            ></i>
                            <router-link
                                to="/produtos"
                                class="text-gray-700 hover:text-blue-600 transition-colors"
                            >
                                Produtos
                            </router-link>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i
                                class="fas fa-chevron-right text-gray-400 mx-2"
                            ></i>
                            <span class="text-gray-500">{{
                                product.name
                            }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Galeria de Imagens -->
                <div class="product-gallery space-y-4">
                    <!-- Imagem Principal -->
                    <div class="relative group">
                        <div
                            class="aspect-square bg-white rounded-2xl shadow-2xl overflow-hidden border-4 border-white"
                        >
                            <Transition name="image" mode="out-in">
                                <img
                                    :key="selectedImage"
                                    :src="selectedImage"
                                    class="w-full h-full object-contain cursor-zoom-in hover:scale-105 transition-transform duration-500"
                                    alt="Imagem do Produto"
                                    @click="openLightbox"
                                />
                            </Transition>
                            <div
                                v-if="product.is_new"
                                class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg"
                            >
                                Novo
                            </div>
                            <div
                                v-if="product.discount_percentage"
                                class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg"
                            >
                                -{{ product.discount_percentage }}%
                            </div>
                        </div>
                        <button
                            @click="openLightbox"
                            class="absolute inset-0 w-full h-full bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100"
                        >
                            <i
                                class="fas fa-search-plus text-white text-3xl"
                            ></i>
                        </button>
                    </div>

                    <!-- Miniaturas -->
                    <div class="flex space-x-3 overflow-x-auto pb-2">
                        <div
                            v-for="(img, idx) in product.images"
                            :key="idx"
                            class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-4 cursor-pointer transition-all duration-300 hover:scale-110"
                            :class="
                                selectedImage === img
                                    ? 'border-blue-500 shadow-lg'
                                    : 'border-gray-200 hover:border-gray-300'
                            "
                            @click="selectedImage = img"
                        >
                            <img
                                :src="img"
                                class="w-full h-full object-cover"
                                alt="Miniatura"
                            />
                        </div>
                    </div>
                </div>
                <!-- Informações do Produto -->
                <div class="product-info space-y-6">
                    <!-- Título e Preço -->
                    <div class="space-y-4">
                        <h1
                            class="text-4xl font-bold text-gray-900 leading-tight"
                        >
                            {{ product.name }}
                        </h1>

                        <!-- Preços -->
                        <div class="flex items-center space-x-4">
                            <div class="flex flex-col">
                                <span
                                    v-if="product.discount_price"
                                    class="text-2xl font-bold text-blue-600"
                                >
                                    R$
                                    {{
                                        (
                                            parseFloat(
                                                product.discount_price,
                                            ) || 0
                                        ).toFixed(2)
                                    }}
                                </span>
                                <span
                                    :class="
                                        product.discount_price
                                            ? 'text-lg text-gray-500 line-through'
                                            : 'text-2xl font-bold text-blue-600'
                                    "
                                >
                                    R$
                                    {{
                                        (
                                            parseFloat(product.price) || 0
                                        ).toFixed(2)
                                    }}
                                </span>
                            </div>
                            <div
                                v-if="product.discount_percentage"
                                class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold"
                            >
                                {{ product.discount_percentage }}% OFF
                            </div>
                        </div>

                        <!-- Avaliações -->
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-1">
                                <span
                                    v-for="n in 5"
                                    :key="n"
                                    class="text-yellow-400 text-lg"
                                >
                                    <i
                                        :class="
                                            n <= (product.rating || 0)
                                                ? 'fas fa-star'
                                                : 'far fa-star'
                                        "
                                        class="transition-colors duration-200"
                                    ></i>
                                </span>
                            </div>
                            <span class="text-gray-600">
                                {{ product.rating || 0 }} ({{
                                    product.reviews?.length || 0
                                }}
                                avaliações)
                            </span>
                        </div>
                    </div>

                    <!-- Descrição Curta -->
                    <div
                        class="bg-white rounded-xl p-6 shadow-lg border border-gray-100"
                    >
                        <p class="text-gray-700 text-lg leading-relaxed">
                            {{ product.shortDescription }}
                        </p>
                    </div>

                    <!-- Seletor de Variantes -->
                    <div
                        v-if="product.variants && product.variants.length > 0"
                        class="space-y-4"
                    >
                        <h3 class="text-lg font-semibold text-gray-900">
                            Opções Disponíveis
                        </h3>

                        <!-- Cores -->
                        <div
                            v-if="product.colors && product.colors.length > 0"
                            class="space-y-2"
                        >
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Cor</label
                            >
                            <div class="flex space-x-3">
                                <button
                                    v-for="color in product.colors"
                                    :key="color.id"
                                    @click="selectedColor = color"
                                    :class="[
                                        'w-10 h-10 rounded-full border-4 transition-all duration-300 transform hover:scale-110',
                                        selectedColor?.id === color.id
                                            ? 'border-blue-500 shadow-lg'
                                            : 'border-gray-300',
                                    ]"
                                    :style="{ backgroundColor: color.hex }"
                                    :title="color.name"
                                ></button>
                            </div>
                        </div>

                        <!-- Tamanhos -->
                        <div
                            v-if="product.sizes && product.sizes.length > 0"
                            class="space-y-2"
                        >
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Tamanho</label
                            >
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="size in product.sizes"
                                    :key="size"
                                    @click="selectedSize = size"
                                    :class="[
                                        'px-4 py-2 rounded-lg border-2 font-medium transition-all duration-300',
                                        selectedSize === size
                                            ? 'border-blue-500 bg-blue-50 text-blue-700 shadow-md'
                                            : 'border-gray-300 text-gray-700 hover:border-gray-400',
                                    ]"
                                >
                                    {{ size }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quantidade e Ações -->
                    <div class="space-y-4">
                        <!-- Seletor de Quantidade -->
                        <div class="flex items-center space-x-4">
                            <label class="text-sm font-medium text-gray-700"
                                >Quantidade:</label
                            >
                            <div
                                class="flex items-center border-2 border-gray-300 rounded-lg overflow-hidden"
                            >
                                <button
                                    @click="
                                        quantity = Math.max(1, quantity - 1)
                                    "
                                    class="px-4 py-3 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors"
                                >
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span
                                    class="px-6 py-3 bg-gray-50 font-semibold min-w-[4rem] text-center"
                                    >{{ quantity }}</span
                                >
                                <button
                                    @click="
                                        quantity = Math.min(
                                            product.stock || 99,
                                            quantity + 1,
                                        )
                                    "
                                    class="px-4 py-3 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors"
                                >
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <span class="text-sm text-gray-500"
                                >({{ product.stock || 0 }} disponíveis)</span
                            >
                        </div>

                        <!-- Botões de Ação -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button
                                @click="addToCart"
                                :disabled="!canAddToCart"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 disabled:from-gray-400 disabled:to-gray-500 text-white py-4 px-8 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 disabled:transform-none disabled:cursor-not-allowed"
                            >
                                <i class="fas fa-cart-plus mr-2"></i>
                                {{
                                    isAddingToCart
                                        ? "Adicionando..."
                                        : "Adicionar ao Carrinho"
                                }}
                            </button>
                            <button
                                @click="addToWishlist"
                                class="p-4 border-2 border-gray-300 text-gray-700 rounded-xl hover:border-red-300 hover:text-red-600 transition-all duration-300 transform hover:scale-105"
                                :title="
                                    isInWishlist
                                        ? 'Remover dos favoritos'
                                        : 'Adicionar aos favoritos'
                                "
                            >
                                <i
                                    :class="
                                        isInWishlist
                                            ? 'fas fa-heart text-red-500'
                                            : 'far fa-heart'
                                    "
                                ></i>
                            </button>
                        </div>
                    </div>

                    <!-- Badges de Confiança -->
                    <div
                        class="bg-white rounded-xl p-6 shadow-lg border border-gray-100"
                    >
                        <div
                            class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center"
                        >
                            <div class="flex flex-col items-center space-y-2">
                                <i
                                    class="fas fa-shield-alt text-2xl text-green-600"
                                ></i>
                                <span class="text-sm font-medium text-gray-700"
                                    >Compra Segura</span
                                >
                            </div>
                            <div class="flex flex-col items-center space-y-2">
                                <i
                                    class="fas fa-truck text-2xl text-blue-600"
                                ></i>
                                <span class="text-sm font-medium text-gray-700"
                                    >Frete Grátis</span
                                >
                            </div>
                            <div class="flex flex-col items-center space-y-2">
                                <i
                                    class="fas fa-undo text-2xl text-purple-600"
                                ></i>
                                <span class="text-sm font-medium text-gray-700"
                                    >Devolução Fácil</span
                                >
                            </div>
                            <div class="flex flex-col items-center space-y-2">
                                <i
                                    class="fas fa-award text-2xl text-yellow-600"
                                ></i>
                                <span class="text-sm font-medium text-gray-700"
                                    >Garantia</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <h2 class="font-semibold mb-2">Descrição</h2>
                <p class="text-gray-600">{{ product.description }}</p>
            </div>
            <div class="mt-6">
                <h2 class="font-semibold mb-2">Perguntas e Respostas</h2>
                <div
                    v-if="product.questions.length === 0"
                    class="text-gray-400"
                >
                    Nenhuma pergunta ainda.
                </div>
                <ul>
                    <li
                        v-for="(q, idx) in product.questions"
                        :key="idx"
                        class="mb-2"
                    >
                        <span class="font-medium">Q:</span> {{ q.question
                        }}<br />
                        <span class="font-medium">A:</span>
                        {{ q.answer || "Aguardando resposta..." }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Descrição Detalhada -->
        <div
            class="mt-16 bg-white rounded-2xl p-8 shadow-xl border border-gray-100"
        >
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                Descrição do Produto
            </h2>
            <div
                class="prose prose-lg max-w-none text-gray-700 leading-relaxed"
            >
                <p>{{ product.description }}</p>
            </div>
        </div>

        <!-- Especificações -->
        <div
            v-if="product.specifications && product.specifications.length > 0"
            class="mt-8 bg-white rounded-2xl p-8 shadow-xl border border-gray-100"
        >
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-list-check text-green-600 mr-3"></i>
                Especificações
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                    v-for="spec in product.specifications"
                    :key="spec.name"
                    class="flex justify-between py-2 border-b border-gray-100"
                >
                    <span class="font-medium text-gray-700">{{
                        spec.name
                    }}</span>
                    <span class="text-gray-600">{{ spec.value }}</span>
                </div>
            </div>
        </div>

        <!-- Perguntas e Respostas -->
        <div
            class="mt-8 bg-white rounded-2xl p-8 shadow-xl border border-gray-100"
        >
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-question-circle text-purple-600 mr-3"></i>
                Perguntas e Respostas
            </h2>
            <div
                v-if="product.questions && product.questions.length === 0"
                class="text-center py-8"
            >
                <i class="fas fa-comments text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">
                    Nenhuma pergunta ainda. Seja o primeiro a perguntar!
                </p>
            </div>
            <div v-else class="space-y-6">
                <div
                    v-for="(q, idx) in product.questions"
                    :key="idx"
                    class="border-l-4 border-blue-500 pl-6 py-4 bg-gray-50 rounded-r-lg"
                >
                    <div class="mb-2">
                        <span class="font-semibold text-blue-700">Q:</span>
                        <span class="text-gray-800 ml-2">{{ q.question }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-green-700">A:</span>
                        <span class="text-gray-700 ml-2">{{
                            q.answer || "Aguardando resposta..."
                        }}</span>
                    </div>
                </div>
            </div>

            <!-- Avaliações -->
            <div class="mt-8">
                <ProductReviews
                    :product-id="product.id"
                    :initial-reviews="product.reviews"
                />
            </div>

            <!-- Lightbox Modal -->
            <Transition name="lightbox">
                <div
                    v-if="lightboxOpen"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90"
                    @click="closeLightbox"
                >
                    <div class="relative max-w-4xl max-h-full p-4">
                        <img
                            :src="selectedImage"
                            class="max-w-full max-h-full object-contain"
                            alt="Imagem ampliada"
                        />
                        <button
                            @click="closeLightbox"
                            class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 transition-colors"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </Transition>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import { useCart } from "@/composables/useCart";
import { useNotification } from "@/composables/useNotification";
import productService from "@/services/productService";
import ProductReviews from "@/components/product/ProductReviews.vue";
import TrustBadges from "@/components/common/TrustBadges.vue";

const route = useRoute();
const { addProduct } = useCart();
const { notify } = useNotification();

const product = ref({
    id: null,
    name: "",
    price: 0,
    discount_price: null,
    discount_percentage: null,
    images: [],
    rating: 0,
    reviews: [],
    shortDescription: "",
    description: "",
    questions: [],
    stock: 0,
    is_new: false,
    variants: [],
    colors: [],
    sizes: [],
    specifications: [],
});

const selectedImage = ref("");
const selectedColor = ref(null);
const selectedSize = ref(null);
const quantity = ref(1);
const lightboxOpen = ref(false);
const isAddingToCart = ref(false);
const isInWishlist = ref(false);

const canAddToCart = computed(() => {
    return product.value.stock > 0 && quantity.value <= product.value.stock;
});

const fetchProduct = async () => {
    const slug = route.params.slug;
    try {
        const data = await productService.getProduct(slug);
        product.value = data;
        selectedImage.value = data.images?.[0] || "";

        // Set default selections
        if (data.colors?.length > 0) selectedColor.value = data.colors[0];
        if (data.sizes?.length > 0) selectedSize.value = data.sizes[0];
    } catch (error) {
        console.error("Erro ao carregar produto:", error);
        notify("Erro ao carregar produto", "error");
    }
};

const addToCart = async () => {
    if (!canAddToCart.value) return;

    isAddingToCart.value = true;
    try {
        const cartItem = {
            ...product.value,
            quantity: quantity.value,
            selectedColor: selectedColor.value,
            selectedSize: selectedSize.value,
        };

        await addProduct(cartItem);
        notify(
            `Produto adicionado ao carrinho! (${quantity.value}x)`,
            "success",
        );
    } catch (error) {
        notify("Erro ao adicionar ao carrinho", "error");
    } finally {
        isAddingToCart.value = false;
    }
};

const addToWishlist = () => {
    isInWishlist.value = !isInWishlist.value;
    notify(
        isInWishlist.value
            ? "Produto adicionado à lista de desejos!"
            : "Produto removido da lista de desejos!",
        "info",
    );
};

const openLightbox = () => {
    lightboxOpen.value = true;
};

const closeLightbox = () => {
    lightboxOpen.value = false;
};

onMounted(fetchProduct);
</script>

<style scoped>
/* Transições de imagem */
.image-enter-active,
.image-leave-active {
    transition: all 0.5s ease;
}

.image-enter-from {
    opacity: 0;
    transform: scale(0.95);
}

.image-leave-to {
    opacity: 0;
    transform: scale(1.05);
}

/* Transições do lightbox */
.lightbox-enter-active,
.lightbox-leave-active {
    transition: all 0.3s ease;
}

.lightbox-enter-from,
.lightbox-leave-to {
    opacity: 0;
}

.lightbox-enter-from > div,
.lightbox-leave-to > div {
    transform: scale(0.9);
}

/* Estilos da galeria */
.product-gallery img {
    transition: transform 0.3s ease;
}

.product-gallery img:hover {
    transform: scale(1.02);
}

/* Botões de variante */
.variant-btn {
    transition: all 0.2s ease;
}

.variant-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Animações de loading */
@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.loading {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Estilos de texto */
.prose {
    color: #374151;
}

.prose p {
    margin-bottom: 1rem;
}

/* Scrollbar customizado para miniaturas */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
