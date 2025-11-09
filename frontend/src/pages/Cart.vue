<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Seu Carrinho</h1>
        <p class="text-xl text-gray-600">
          Revise seus produtos e finalize sua compra
        </p>
      </div>

      <!-- Carrinho vazio -->
      <div v-if="cartItems.length === 0" class="text-center py-16">
        <div
          class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6"
        >
          <svg
            class="w-12 h-12 text-gray-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5-5M7 13l-2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"
            ></path>
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
          Seu carrinho está vazio
        </h2>
        <p class="text-gray-600 mb-8">
          Adicione alguns produtos incríveis para começar!
        </p>
        <router-link
          to="/produtos"
          class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-lg"
        >
          <svg
            class="w-5 h-5 mr-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M7 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
            ></path>
          </svg>
          Continuar Comprando
        </router-link>
      </div>

      <!-- Itens do carrinho -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Lista de produtos -->
        <div class="lg:col-span-2 space-y-6">
          <div
            v-for="item in cartItems"
            :key="item.id"
            class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100"
          >
            <div class="flex items-start space-x-4">
              <!-- Imagem do produto -->
              <div class="flex-shrink-0">
                <img
                  :src="item.image || '/images/placeholder-product.jpg'"
                  :alt="item.name"
                  class="w-24 h-24 object-cover rounded-xl"
                />
              </div>

              <!-- Detalhes do produto -->
              <div class="flex-1 min-w-0">
                <h3 class="text-xl font-bold text-gray-900 mb-2">
                  {{ item.name }}
                </h3>
                <p class="text-gray-600 mb-3">{{ item.description }}</p>
                <div class="flex items-center space-x-4">
                  <span class="text-2xl font-bold text-blue-600">
                    R$ {{ item.price?.toFixed(2) }}
                  </span>
                  <span
                    v-if="
                      item.original_price && item.original_price > item.price
                    "
                    class="text-lg text-gray-400 line-through"
                  >
                    R$ {{ item.original_price.toFixed(2) }}
                  </span>
                </div>
              </div>

              <!-- Controles de quantidade -->
              <div class="flex flex-col items-end space-y-4">
                <button
                  @click="removeFromCart(item.id)"
                  class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors"
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
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    ></path>
                  </svg>
                </button>

                <div
                  class="flex items-center space-x-3 bg-gray-50 rounded-xl p-2"
                >
                  <button
                    @click="updateQuantity(item.id, item.quantity - 1)"
                    :disabled="item.quantity <= 1"
                    class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20 12H4"
                      ></path>
                    </svg>
                  </button>
                  <span class="font-bold text-lg min-w-8 text-center">{{
                    item.quantity
                  }}</span>
                  <button
                    @click="updateQuantity(item.id, item.quantity + 1)"
                    class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                      ></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Resumo do pedido -->
        <div class="lg:col-span-1">
          <div
            class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 sticky top-6"
          >
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
              Resumo do Pedido
            </h2>

            <div class="space-y-4 mb-6">
              <div class="flex justify-between text-lg">
                <span class="text-gray-600"
                  >Subtotal ({{ totalItems }}
                  {{ totalItems === 1 ? "item" : "itens" }})</span
                >
                <span class="font-bold">R$ {{ subtotal.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between text-lg">
                <span class="text-gray-600">Frete</span>
                <span class="font-bold text-green-600">Grátis</span>
              </div>
              <div class="border-t border-gray-200 pt-4">
                <div class="flex justify-between text-2xl font-bold">
                  <span>Total</span>
                  <span class="text-blue-600">R$ {{ total.toFixed(2) }}</span>
                </div>
              </div>
            </div>

            <div class="space-y-4">
              <button
                @click="proceedToCheckout"
                class="w-full bg-gradient-to-r from-green-500 to-blue-600 text-white py-4 px-6 rounded-xl font-bold text-lg hover:from-green-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg"
              >
                <span class="flex items-center justify-center">
                  <svg
                    class="w-5 h-5 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                    ></path>
                  </svg>
                  Finalizar Compra
                </span>
              </button>

              <router-link
                to="/produtos"
                class="w-full block text-center py-3 px-4 border-2 border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-all duration-300"
              >
                Continuar Comprando
              </router-link>
            </div>

            <!-- Selos de segurança -->
            <div class="mt-8 pt-6 border-t border-gray-200">
              <div
                class="flex items-center justify-center space-x-4 text-sm text-gray-500"
              >
                <div class="flex items-center">
                  <svg
                    class="w-4 h-4 mr-1 text-green-500"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                  Compra Segura
                </div>
                <div class="flex items-center">
                  <svg
                    class="w-4 h-4 mr-1 text-blue-500"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                  </svg>
                  Garantia
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useCart } from "@/composables/useCart";
import { useRouter } from "vue-router";

const { cartItems, removeFromCart, updateQuantity } = useCart();
const router = useRouter();

const totalItems = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.quantity, 0);
});

const subtotal = computed(() => {
  return cartItems.value.reduce(
    (total, item) => total + item.price * item.quantity,
    0
  );
});

const total = computed(() => {
  return subtotal.value; // Sem taxa de frete por enquanto
});

function proceedToCheckout() {
  router.push("/checkout");
}
</script>
