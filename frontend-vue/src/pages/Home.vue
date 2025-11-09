<template>
  <div class="home">
    <!-- Hero Section -->
    <section
      class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">EstampariaPro</h1>
        <p class="text-xl md:text-2xl mb-8 opacity-90">
          Chinelos personalizados com qualidade e estilo único
        </p>
        <div class="space-x-4">
          <router-link
            to="/produtos"
            class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
          >
            Ver Produtos
          </router-link>
          <router-link
            to="/login"
            class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors inline-block"
          >
            Começar Agora
          </router-link>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">
            Por que escolher a EstampariaPro?
          </h2>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Oferecemos chinelos de alta qualidade com personalização exclusiva,
            entrega rápida e atendimento excepcional.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center p-6">
            <div
              class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4"
            >
              <i class="fas fa-palette text-2xl text-blue-600"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
              Personalização Única
            </h3>
            <p class="text-gray-600">
              Crie seu design exclusivo com nossa ferramenta de personalização
              intuitiva.
            </p>
          </div>

          <div class="text-center p-6">
            <div
              class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
            >
              <i class="fas fa-award text-2xl text-green-600"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
              Qualidade Premium
            </h3>
            <p class="text-gray-600">
              Materiais de primeira linha garantem durabilidade e conforto
              superior.
            </p>
          </div>

          <div class="text-center p-6">
            <div
              class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"
            >
              <i class="fas fa-shipping-fast text-2xl text-purple-600"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
              Entrega Rápida
            </h3>
            <p class="text-gray-600">
              Receba seus chinelos personalizados em casa rapidamente e com
              segurança.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Products Preview -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">
            Produtos em Destaque
          </h2>
          <p class="text-lg text-gray-600">
            Confira alguns dos nossos chinelos mais populares
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="product in featuredProducts"
            :key="product.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
          >
            <div
              class="aspect-square bg-gray-200 flex items-center justify-center"
            >
              <i class="fas fa-flip-flops text-4xl text-gray-400"></i>
            </div>
            <div class="p-4">
              <h3 class="font-semibold text-gray-900 mb-2">
                {{ product.name }}
              </h3>
              <p class="text-gray-600 text-sm mb-2">
                {{ product.description }}
              </p>
              <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-blue-600"
                  >R$ {{ product.price }}</span
                >
                <button
                  class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors"
                >
                  Ver Mais
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="text-center mt-8">
          <router-link
            to="/produtos"
            class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-block"
          >
            Ver Todos os Produtos
          </router-link>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-blue-600 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">
          Pronto para criar seu chinelo personalizado?
        </h2>
        <p class="text-xl mb-8 opacity-90">
          Junte-se a milhares de clientes satisfeitos e comece sua
          personalização agora!
        </p>
        <router-link
          to="/produtos"
          class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
        >
          Começar Personalização
        </router-link>
      </div>
    </section>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";

export default {
  name: "Home",
  setup() {
    const featuredProducts = ref([
      {
        id: 1,
        name: "Chinelo Havaianas Brasil",
        description: "Chinelo clássico brasileiro",
        price: "29.99",
      },
      {
        id: 2,
        name: "Chinelo Ipanema Fashion",
        description: "Style carioca autêntico",
        price: "39.99",
      },
      {
        id: 3,
        name: "Chinelo Rider Energy",
        description: "Conforto e durabilidade",
        price: "45.99",
      },
      {
        id: 4,
        name: "Chinelo Personalizado",
        description: "Seu design único",
        price: "55.99",
      },
    ]);

    onMounted(() => {
      // Aqui você pode carregar produtos da API
      console.log("Home page loaded");
    });

    return {
      featuredProducts,
    };
  },
};
</script>

<style scoped>
.home {
  min-height: 100vh;
}

.aspect-square {
  aspect-ratio: 1 / 1;
}
</style>
