<template>
  <div
    class="not-found min-h-screen flex items-center justify-center bg-gray-50"
  >
    <div class="text-center">
      <div class="mb-8">
        <i
          class="fas fa-exclamation-triangle text-8xl text-yellow-500 mb-4"
        ></i>
        <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-2">
          Página não encontrada
        </h2>
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
          Oops! A página que você está procurando não existe ou foi movida.
        </p>
      </div>

      <div class="space-y-4">
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            to="/"
            class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-flex items-center justify-center"
          >
            <i class="fas fa-home mr-2"></i>
            Voltar ao Início
          </router-link>

          <router-link
            to="/produtos"
            class="border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 hover:text-white transition-colors inline-flex items-center justify-center"
          >
            <i class="fas fa-shopping-bag mr-2"></i>
            Ver Produtos
          </router-link>
        </div>

        <button
          @click="goBack"
          class="text-gray-600 hover:text-gray-800 font-medium inline-flex items-center"
        >
          <i class="fas fa-arrow-left mr-2"></i>
          Voltar à página anterior
        </button>
      </div>

      <!-- Popular Links -->
      <div class="mt-12">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
          Links populares:
        </h3>
        <div class="flex flex-wrap justify-center gap-4 text-sm">
          <router-link
            to="/"
            class="text-blue-600 hover:text-blue-800 underline"
          >
            Página Inicial
          </router-link>
          <router-link
            to="/produtos"
            class="text-blue-600 hover:text-blue-800 underline"
          >
            Produtos
          </router-link>
          <router-link
            to="/login"
            class="text-blue-600 hover:text-blue-800 underline"
          >
            Login
          </router-link>
          <router-link
            to="/carrinho"
            class="text-blue-600 hover:text-blue-800 underline"
          >
            Carrinho
          </router-link>
        </div>
      </div>

      <!-- Contact Support -->
      <div class="mt-8 p-4 bg-white rounded-lg shadow-sm max-w-md mx-auto">
        <h4 class="font-semibold text-gray-900 mb-2">Precisa de ajuda?</h4>
        <p class="text-sm text-gray-600 mb-3">
          Se você acha que isso é um erro, entre em contato conosco.
        </p>
        <a
          href="mailto:suporte@estampariapro.com"
          class="text-blue-600 hover:text-blue-800 font-medium text-sm inline-flex items-center"
        >
          <i class="fas fa-envelope mr-2"></i>
          suporte@estampariapro.com
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "NotFound",
  setup() {
    const goBack = () => {
      if (window.history.length > 1) {
        window.history.back();
      } else {
        window.location.href = "/";
      }
    };

    return {
      goBack,
    };
  },
};
</script>

<style scoped>
/* Custom animations */
.not-found {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
