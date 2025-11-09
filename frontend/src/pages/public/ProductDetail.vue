<template>
  <div class="container mx-auto px-4 py-8">
    <div v-if="loading" class="flex justify-center">
      <LoadingSpinner />
    </div>

    <div
      v-else-if="currentProduct"
      class="grid grid-cols-1 lg:grid-cols-2 gap-8"
    >
      <!-- Imagem do Produto -->
      <div class="space-y-4">
        <img
          :src="currentProduct.image || '/placeholder-image.jpg'"
          :alt="currentProduct.name"
          class="w-full h-96 object-cover rounded-lg"
        />
      </div>

      <!-- Detalhes do Produto -->
      <div class="space-y-6">
        <h1 class="text-3xl font-bold">{{ currentProduct.name }}</h1>

        <div class="flex items-baseline gap-3">
          <span class="text-2xl font-bold text-blue-600">
            R$
            {{
              formatPrice(currentProduct.discount_price || currentProduct.price)
            }}
          </span>
          <span
            v-if="currentProduct.discount_price"
            class="text-lg text-gray-500 line-through"
          >
            R$ {{ formatPrice(currentProduct.price) }}
          </span>
        </div>

        <p class="text-gray-600">{{ currentProduct.description }}</p>

        <button
          class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors"
          @click="handleAddToCart"
        >
          Adicionar ao Carrinho
        </button>
      </div>
    </div>

    <div v-else class="text-center py-12">
      <p class="text-gray-500 text-lg">Produto não encontrado</p>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useRoute } from "vue-router";
import { useProducts } from "@/composables/useProducts";
import { useCart } from "@/composables/useCart";
import { formatPrice } from "@/utils/formatters";
import LoadingSpinner from "@/components/common/LoadingSpinner.vue";

const route = useRoute();
const { currentProduct, loading, fetchProduct } = useProducts();
const { addToCart } = useCart();

onMounted(async () => {
  await fetchProduct(route.params.id);
});

async function handleAddToCart() {
  try {
    await addToCart(currentProduct.value.id, 1);
  } catch (error) {
    console.error("Erro ao adicionar ao carrinho:", error);
  }
}
</script>
