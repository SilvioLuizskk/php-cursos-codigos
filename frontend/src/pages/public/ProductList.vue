<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header da página -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-16">
      <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-black mb-4">🛍️ Nossos Produtos</h1>
        <p class="text-xl opacity-90 max-w-2xl mx-auto">
          Descubra nossa coleção completa de produtos personalizáveis. Qualidade
          premium, designs únicos e preços incríveis!
        </p>
      </div>
    </div>

    <div class="container mx-auto px-4 py-12">
      <!-- Filtros rápidos -->
      <div class="mb-8 flex flex-wrap gap-4 justify-center">
        <button
          class="bg-white px-6 py-3 rounded-full shadow-lg hover:shadow-xl transition-all font-semibold text-purple-600 border-2 border-purple-600"
        >
          🎯 Todos
        </button>
        <button
          class="bg-white px-6 py-3 rounded-full shadow-lg hover:shadow-xl transition-all font-semibold text-gray-600 hover:text-purple-600"
        >
          👕 Camisetas
        </button>
        <button
          class="bg-white px-6 py-3 rounded-full shadow-lg hover:shadow-xl transition-all font-semibold text-gray-600 hover:text-purple-600"
        >
          ☕ Canecas
        </button>
        <button
          class="bg-white px-6 py-3 rounded-full shadow-lg hover:shadow-xl transition-all font-semibold text-gray-600 hover:text-purple-600"
        >
          🎁 Presentes
        </button>
      </div>

      <!-- Grid de produtos -->
      <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8"
      >
        <div v-if="loading" class="col-span-full flex justify-center py-16">
          <div class="text-center">
            <div
              class="animate-spin rounded-full h-16 w-16 border-b-2 border-purple-600 mx-auto mb-4"
            ></div>
            <p class="text-gray-600 font-medium">
              Carregando produtos incríveis...
            </p>
          </div>
        </div>

        <div
          v-else-if="products.length === 0"
          class="col-span-full text-center py-16"
        >
          <div class="text-6xl mb-4">🔍</div>
          <p class="text-gray-500 text-xl mb-4">Nenhum produto encontrado</p>
          <p class="text-gray-400">
            Tente ajustar os filtros ou volte mais tarde
          </p>
        </div>

        <ProductCard
          v-else
          v-for="product in products"
          :key="product.id"
          :product="product"
          @add-to-cart="handleAddToCart(product)"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useProducts } from "@/composables/useProducts";
import { useCart } from "@/composables/useCart";
import ProductCard from "@/components/product/ProductCard.vue";
import LoadingSpinner from "@/components/common/LoadingSpinner.vue";

const { products, loading, fetchProducts } = useProducts();
const { addToCart } = useCart();

onMounted(async () => {
  await fetchProducts();
});

async function handleAddToCart(product) {
  try {
    await addToCart(product.id, 1);
  } catch (error) {
    console.error("Erro ao adicionar ao carrinho:", error);
  }
}
</script>
