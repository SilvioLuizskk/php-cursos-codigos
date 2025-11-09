<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section com design moderno -->
    <section
      class="relative bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-800 text-white overflow-hidden"
    >
      <!-- Elementos de fundo decorativos -->
      <div class="absolute inset-0">
        <div
          class="absolute top-10 left-10 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"
        ></div>
        <div
          class="absolute top-10 right-10 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"
        ></div>
        <div
          class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"
        ></div>
      </div>

      <div class="relative container mx-auto px-4 py-24 text-center">
        <div class="max-w-4xl mx-auto">
          <h1
            class="text-6xl md:text-7xl font-black mb-6 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent"
          >
            Karibe Presente Personalizado
          </h1>
          <p
            class="text-xl md:text-2xl mb-8 text-gray-200 leading-relaxed max-w-2xl mx-auto"
          >
            Transforme suas ideias em presentes únicos. Camisetas, canecas e
            muito mais personalizados com qualidade profissional.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <router-link
              to="/produtos"
              class="bg-white text-purple-900 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 shadow-2xl"
            >
              🛍️ Explorar Produtos
            </router-link>
            <button
              class="border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-purple-900 transform hover:scale-105 transition-all duration-300"
            >
              ✨ Criar Personalizado
            </button>
          </div>
        </div>
      </div>

      <!-- Indicador de scroll -->
      <div
        class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce"
      >
        <svg
          class="w-6 h-6 text-white"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M19 14l-7 7m0 0l-7-7m7 7V3"
          ></path>
        </svg>
      </div>
    </section>

    <!-- Categorias -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-black text-gray-900 mb-4">
            O que você quer personalizar?
          </h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Escolha entre nossa variedade de produtos e deixe sua criatividade
            fluir
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="group cursor-pointer">
            <div
              class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-8 text-white text-center transform group-hover:scale-105 transition-all duration-300 shadow-lg group-hover:shadow-2xl"
            >
              <div class="text-6xl mb-4">👕</div>
              <h3 class="text-2xl font-bold mb-2">Camisetas</h3>
              <p class="opacity-90">
                Personalize com suas imagens e textos favoritos
              </p>
            </div>
          </div>

          <div class="group cursor-pointer">
            <div
              class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl p-8 text-white text-center transform group-hover:scale-105 transition-all duration-300 shadow-lg group-hover:shadow-2xl"
            >
              <div class="text-6xl mb-4">☕</div>
              <h3 class="text-2xl font-bold mb-2">Canecas</h3>
              <p class="opacity-90">Canecas únicas para momentos especiais</p>
            </div>
          </div>

          <div class="group cursor-pointer">
            <div
              class="bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl p-8 text-white text-center transform group-hover:scale-105 transition-all duration-300 shadow-lg group-hover:shadow-2xl"
            >
              <div class="text-6xl mb-4">🎁</div>
              <h3 class="text-2xl font-bold mb-2">Presentes</h3>
              <p class="opacity-90">Itens especiais para ocasiões marcantes</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Produtos em Destaque -->
    <section class="py-16 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-black text-gray-900 mb-4">
            ✨ Produtos em Destaque
          </h2>
          <p class="text-xl text-gray-600">
            Nossos produtos mais populares e bem avaliados
          </p>
        </div>

        <div v-if="loading" class="flex justify-center py-12">
          <div
            class="animate-spin rounded-full h-16 w-16 border-b-2 border-purple-600"
          ></div>
        </div>

        <div
          v-else
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8"
        >
          <ProductCard
            v-for="product in featured"
            :key="product.id"
            :product="product"
            @add-to-cart="handleAddToCart(product)"
          />
        </div>

        <div class="text-center mt-12">
          <router-link
            to="/produtos"
            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-full hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-300 shadow-lg"
          >
            Ver Todos os Produtos
            <svg
              class="w-5 h-5 ml-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M17 8l4 4m0 0l-4 4m4-4H3"
              ></path>
            </svg>
          </router-link>
        </div>
      </div>
    </section>

    <!-- Seção de Benefícios -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-gray-900 mb-4 text-center">
            Por que escolher a Karibe Presente Personalizado?
          </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            class="text-center p-6 rounded-xl hover:bg-gray-50 transition-colors"
          >
            <div
              class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4"
            >
              <svg
                class="w-8 h-8 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 13l4 4L19 7"
                ></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Qualidade Premium</h3>
            <p class="text-gray-600">
              Materiais de alta qualidade e impressão durável
            </p>
          </div>

          <div
            class="text-center p-6 rounded-xl hover:bg-gray-50 transition-colors"
          >
            <div
              class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4"
            >
              <svg
                class="w-8 h-8 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 10V3L4 14h7v7l9-11h-7z"
                ></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Entrega Rápida</h3>
            <p class="text-gray-600">
              Processamento ágil e entrega em todo o Brasil
            </p>
          </div>

          <div
            class="text-center p-6 rounded-xl hover:bg-gray-50 transition-colors"
          >
            <div
              class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4"
            >
              <svg
                class="w-8 h-8 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                ></path>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Atendimento Personalizado</h3>
            <p class="text-gray-600">
              Suporte dedicado para realizar seus projetos
            </p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useProducts } from "@/composables/useProducts";
import { useCart } from "@/composables/useCart";
import ProductCard from "@/components/product/ProductCard.vue";
import LoadingSpinner from "@/components/common/LoadingSpinner.vue";

const { featured, loading, fetchFeatured } = useProducts();
const { addToCart } = useCart();

onMounted(async () => {
  await fetchFeatured();
});

async function handleAddToCart(product) {
  try {
    await addToCart(product.id, 1);
    // Mostrar notificação de sucesso
  } catch (error) {
    console.error("Erro ao adicionar ao carrinho:", error);
  }
}
</script>
