<template>
  <div class="cart py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Seu Carrinho</h1>
        <p class="text-lg text-gray-600">
          Revise seus itens antes de finalizar a compra
        </p>
      </div>

      <!-- Empty Cart -->
      <div v-if="cartItems.length === 0" class="text-center py-12">
        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">
          Seu carrinho está vazio
        </h3>
        <p class="text-gray-500 mb-6">
          Adicione alguns produtos incríveis para começar!
        </p>
        <router-link
          to="/produtos"
          class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-block"
        >
          Continuar Comprando
        </router-link>
      </div>

      <!-- Cart Content -->
      <div v-else class="lg:grid lg:grid-cols-12 lg:gap-x-12">
        <!-- Cart Items -->
        <div class="lg:col-span-8">
          <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">
                Itens do Carrinho
              </h3>
            </div>

            <ul class="divide-y divide-gray-200">
              <li v-for="item in cartItems" :key="item.id" class="p-6">
                <div class="flex items-center">
                  <!-- Product Image -->
                  <div
                    class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded-md flex items-center justify-center"
                  >
                    <i class="fas fa-flip-flops text-2xl text-gray-400"></i>
                  </div>

                  <!-- Product Details -->
                  <div class="ml-6 flex-1">
                    <div class="flex justify-between">
                      <div>
                        <h4 class="text-lg font-semibold text-gray-900">
                          {{ item.product.name }}
                        </h4>
                        <p class="text-sm text-gray-600">
                          {{ item.product.description }}
                        </p>
                        <div v-if="item.customization" class="mt-2">
                          <span
                            class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded"
                          >
                            Personalizado
                          </span>
                        </div>
                      </div>
                      <div class="text-right">
                        <p class="text-lg font-semibold text-gray-900">
                          R$
                          {{ (item.product.price * item.quantity).toFixed(2) }}
                        </p>
                        <p class="text-sm text-gray-500">
                          R$ {{ item.product.price }} cada
                        </p>
                      </div>
                    </div>

                    <!-- Quantity and Actions -->
                    <div class="mt-4 flex items-center justify-between">
                      <div class="flex items-center space-x-3">
                        <label class="text-sm font-medium text-gray-700"
                          >Quantidade:</label
                        >
                        <div
                          class="flex items-center border border-gray-300 rounded-md"
                        >
                          <button
                            @click="updateQuantity(item.id, item.quantity - 1)"
                            :disabled="item.quantity <= 1"
                            class="px-3 py-1 text-gray-600 hover:text-gray-800 disabled:opacity-50"
                          >
                            <i class="fas fa-minus"></i>
                          </button>
                          <span class="px-4 py-1 text-center min-w-[3rem]">{{
                            item.quantity
                          }}</span>
                          <button
                            @click="updateQuantity(item.id, item.quantity + 1)"
                            :disabled="item.quantity >= item.product.stock"
                            class="px-3 py-1 text-gray-600 hover:text-gray-800 disabled:opacity-50"
                          >
                            <i class="fas fa-plus"></i>
                          </button>
                        </div>
                        <span class="text-sm text-gray-500">
                          ({{ item.product.stock }} disponíveis)
                        </span>
                      </div>

                      <button
                        @click="removeFromCart(item.id)"
                        class="text-red-600 hover:text-red-800 text-sm font-medium"
                      >
                        <i class="fas fa-trash mr-1"></i>
                        Remover
                      </button>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>

          <!-- Continue Shopping -->
          <div class="mt-6">
            <router-link
              to="/produtos"
              class="text-blue-600 hover:text-blue-800 font-medium"
            >
              <i class="fas fa-arrow-left mr-2"></i>
              Continuar Comprando
            </router-link>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-4 mt-8 lg:mt-0">
          <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
              Resumo do Pedido
            </h3>

            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-600"
                  >Subtotal ({{ totalItems }}
                  {{ totalItems === 1 ? "item" : "itens" }})</span
                >
                <span class="font-semibold">R$ {{ subtotal.toFixed(2) }}</span>
              </div>

              <div class="flex justify-between">
                <span class="text-gray-600">Frete</span>
                <span class="font-semibold">R$ {{ shipping.toFixed(2) }}</span>
              </div>

              <div
                v-if="discount > 0"
                class="flex justify-between text-green-600"
              >
                <span>Desconto</span>
                <span>-R$ {{ discount.toFixed(2) }}</span>
              </div>

              <hr class="my-4" />

              <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span>R$ {{ total.toFixed(2) }}</span>
              </div>
            </div>

            <!-- Coupon Code -->
            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-700 mb-2"
                >Cupom de Desconto</label
              >
              <div class="flex space-x-2">
                <input
                  v-model="couponCode"
                  type="text"
                  placeholder="Digite o código"
                  class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <button
                  @click="applyCoupon"
                  :disabled="!couponCode || applyingCoupon"
                  class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors disabled:opacity-50"
                >
                  {{ applyingCoupon ? "Aplicando..." : "Aplicar" }}
                </button>
              </div>
              <p
                v-if="couponMessage"
                :class="[
                  'text-sm mt-2',
                  couponMessage.type === 'success'
                    ? 'text-green-600'
                    : 'text-red-600',
                ]"
              >
                {{ couponMessage.text }}
              </p>
            </div>

            <!-- Checkout Button -->
            <button
              @click="proceedToCheckout"
              class="w-full mt-6 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
            >
              <i class="fas fa-credit-card mr-2"></i>
              Finalizar Compra
            </button>

            <!-- Security -->
            <div class="mt-4 text-center">
              <div
                class="flex items-center justify-center text-sm text-gray-500"
              >
                <i class="fas fa-lock mr-2"></i>
                Compra segura e protegida
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";

export default {
  name: "Cart",
  setup() {
    const cartItems = ref([]);
    const couponCode = ref("");
    const applyingCoupon = ref(false);
    const couponMessage = ref(null);
    const appliedCoupon = ref(null);

    // Mock cart items
    const mockCartItems = [
      {
        id: 1,
        product: {
          id: 1,
          name: "Chinelo Havaianas Brasil",
          description: "Chinelo clássico brasileiro",
          price: 29.99,
          stock: 50,
        },
        quantity: 2,
        customization: {
          size: "M",
          color: "Azul",
        },
      },
      {
        id: 2,
        product: {
          id: 2,
          name: "Chinelo Ipanema Fashion",
          description: "Style carioca autêntico",
          price: 39.99,
          stock: 30,
        },
        quantity: 1,
        customization: null,
      },
    ];

    const totalItems = computed(() => {
      return cartItems.value.reduce((total, item) => total + item.quantity, 0);
    });

    const subtotal = computed(() => {
      return cartItems.value.reduce((total, item) => {
        return total + item.product.price * item.quantity;
      }, 0);
    });

    const shipping = computed(() => {
      return subtotal.value > 100 ? 0 : 15.99;
    });

    const discount = computed(() => {
      if (appliedCoupon.value) {
        return subtotal.value * (appliedCoupon.value.percentage / 100);
      }
      return 0;
    });

    const total = computed(() => {
      return subtotal.value + shipping.value - discount.value;
    });

    const updateQuantity = (itemId, newQuantity) => {
      if (newQuantity <= 0) return;

      const item = cartItems.value.find((item) => item.id === itemId);
      if (item && newQuantity <= item.product.stock) {
        item.quantity = newQuantity;
      }
    };

    const removeFromCart = (itemId) => {
      if (confirm("Tem certeza que deseja remover este item do carrinho?")) {
        cartItems.value = cartItems.value.filter((item) => item.id !== itemId);
      }
    };

    const applyCoupon = async () => {
      if (!couponCode.value) return;

      applyingCoupon.value = true;
      couponMessage.value = null;

      try {
        // Simular chamada API
        await new Promise((resolve) => setTimeout(resolve, 1000));

        // Mock coupon validation
        if (couponCode.value.toUpperCase() === "DESCONTO10") {
          appliedCoupon.value = {
            code: couponCode.value,
            percentage: 10,
          };
          couponMessage.value = {
            type: "success",
            text: "Cupom aplicado com sucesso! 10% de desconto",
          };
        } else {
          couponMessage.value = {
            type: "error",
            text: "Cupom inválido ou expirado",
          };
        }
      } catch (error) {
        couponMessage.value = {
          type: "error",
          text: "Erro ao aplicar cupom. Tente novamente.",
        };
      } finally {
        applyingCoupon.value = false;
      }
    };

    const proceedToCheckout = () => {
      // Simular checkout
      alert("Redirecionando para o checkout...");
      // router.push('/checkout')
    };

    onMounted(() => {
      // Load cart items from API or localStorage
      cartItems.value = mockCartItems;
    });

    return {
      cartItems,
      couponCode,
      applyingCoupon,
      couponMessage,
      totalItems,
      subtotal,
      shipping,
      discount,
      total,
      updateQuantity,
      removeFromCart,
      applyCoupon,
      proceedToCheckout,
    };
  },
};
</script>

<style scoped>
/* Custom styles */
</style>
