<template>
    <div class="product-detail-page">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Galeria de Imagens -->
            <div class="product-gallery">
                <img
                    :src="selectedImage"
                    class="main-image rounded-lg border"
                    alt="Imagem do Produto"
                />
                <div class="flex mt-4 space-x-2">
                    <img
                        v-for="(img, idx) in product.images"
                        :key="idx"
                        :src="img"
                        class="thumbnail w-16 h-16 object-cover rounded border cursor-pointer"
                        :class="{
                            'ring-2 ring-primary': selectedImage === img,
                        }"
                        @click="selectedImage = img"
                        alt="Miniatura do Produto"
                    />
                </div>
            </div>
            <!-- Informações do Produto -->
            <div class="product-info space-y-4">
                <h1 class="text-2xl font-bold">{{ product.name }}</h1>
                <div class="text-lg text-primary font-semibold">
                    R$ {{ (parseFloat(product.price) || 0).toFixed(2) }}
                </div>
                <div class="flex items-center space-x-2">
                    <span v-for="n in 5" :key="n" class="text-yellow-400">
                        <i
                            :class="
                                n <= product.rating
                                    ? 'fas fa-star'
                                    : 'far fa-star'
                            "
                        ></i>
                    </span>
                    <span class="text-sm text-gray-500"
                        >({{ product.reviews.length }} avaliações)</span
                    >
                </div>
                <p class="text-gray-700">{{ product.shortDescription }}</p>
                <div class="flex space-x-4 mt-4">
                    <button class="btn btn-primary" @click="addToCart">
                        Comprar agora
                    </button>
                    <button class="btn btn-outline" @click="addToWishlist">
                        <i class="far fa-heart"></i> Favoritar
                    </button>
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
        </div>
        <!-- Badges de Confiança -->
        <TrustBadges />
        <!-- Avaliações -->
        <ProductReviews
            :product-id="product.id"
            :initial-reviews="product.reviews"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
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
    images: [],
    rating: 0,
    reviews: [],
    shortDescription: "",
    description: "",
    questions: [],
});
const selectedImage = ref("");

const fetchProduct = async () => {
    const slug = route.params.slug;
    const data = await productService.getProduct(slug);
    product.value = data;
    selectedImage.value = data.images[0] || "";
};

const addToCart = () => {
    addProduct(product.value);
    notify("Produto adicionado ao carrinho!", "success");
};

const addToWishlist = () => {
    notify("Produto adicionado à lista de desejos!", "info");
};

onMounted(fetchProduct);
</script>

<style scoped>
.product-detail-page {
    padding: 2rem 0;
}
.main-image {
    width: 100%;
    max-height: 400px;
    object-fit: contain;
    background: #fff;
}
.thumbnail {
    border: 2px solid transparent;
    transition: border 0.2s;
}
.thumbnail.ring-2 {
    border-color: #3b82f6;
}
</style>
