<template>
    <div class="cart py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Seu Carrinho
                </h1>
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
                            <h3
                                class="text-lg font-semibold text-gray-900 flex items-center"
                            >
                                <i
                                    class="fas fa-shopping-cart mr-2 text-blue-600"
                                ></i>
                                Itens do Carrinho ({{ cartItems.length }})
                            </h3>
                        </div>

                        <TransitionGroup
                            name="cart-item"
                            tag="ul"
                            class="divide-y divide-gray-200"
                        >
                            <li
                                v-for="item in cartItems"
                                :key="item.id"
                                class="p-6 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-300 transform hover:scale-[1.02] border-l-4 border-transparent hover:border-blue-400"
                            >
                                <div class="flex items-center">
                                    <!-- Product Image -->
                                    <div
                                        class="flex-shrink-0 w-20 h-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300"
                                    >
                                        <i
                                            class="fas fa-flip-flops text-3xl text-blue-500 group-hover:text-purple-600 transition-colors duration-300"
                                        ></i>
                                    </div>
                                    <!-- Product Details -->
                                    <div class="ml-6 flex-1">
                                        <div class="flex justify-between">
                                            <div>
                                                <h4
                                                    class="text-lg font-semibold text-gray-900 hover:text-blue-600 transition-colors cursor-pointer"
                                                >
                                                    {{ item.product.name }}
                                                </h4>
                                                <p
                                                    class="text-sm text-gray-600 line-clamp-2"
                                                >
                                                    {{
                                                        item.product.description
                                                    }}
                                                </p>
                                                <div
                                                    v-if="item.customization"
                                                    class="mt-2"
                                                >
                                                    <span
                                                        class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full"
                                                    >
                                                        Personalizado
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p
                                                    class="text-lg font-semibold text-gray-900"
                                                >
                                                    R$
                                                    {{
                                                        (
                                                            item.product.price *
                                                            item.quantity
                                                        ).toFixed(2)
                                                    }}
                                                </p>
                                                <p
                                                    class="text-sm text-gray-500"
                                                >
                                                    R$
                                                    {{ item.product.price }}
                                                    cada
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Quantity and Actions -->
                                        <div
                                            class="mt-4 flex items-center justify-between"
                                        >
                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <label
                                                    class="text-sm font-medium text-gray-700"
                                                    >Quantidade:</label
                                                >
                                                <div
                                                    class="flex items-center border border-gray-300 rounded-lg overflow-hidden"
                                                >
                                                    <button
                                                        @click="
                                                            updateQuantity(
                                                                item.id,
                                                                item.quantity -
                                                                    1,
                                                            )
                                                        "
                                                        :disabled="
                                                            item.quantity <= 1
                                                        "
                                                        class="px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                                    >
                                                        <i
                                                            class="fas fa-minus text-sm"
                                                        ></i>
                                                    </button>
                                                    <span
                                                        class="px-4 py-2 text-center min-w-[3rem] bg-gray-50 font-medium"
                                                        >{{
                                                            item.quantity
                                                        }}</span
                                                    >
                                                    <button
                                                        @click="
                                                            updateQuantity(
                                                                item.id,
                                                                item.quantity +
                                                                    1,
                                                            )
                                                        "
                                                        :disabled="
                                                            item.quantity >=
                                                            item.product.stock
                                                        "
                                                        class="px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                                    >
                                                        <i
                                                            class="fas fa-plus text-sm"
                                                        ></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <button
                                                @click="removeItem(item.id)"
                                                class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Remover item"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </TransitionGroup>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-4 mt-8 lg:mt-0">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-4">
                        <h3
                            class="text-lg font-semibold text-gray-900 mb-6 flex items-center"
                        >
                            <i class="fas fa-receipt mr-2 text-blue-600"></i>
                            Resumo do Pedido
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 text-sm"
                                    >Subtotal ({{ totalItems }}
                                    {{
                                        totalItems === 1 ? "item" : "itens"
                                    }})</span
                                >
                                <span class="font-semibold text-gray-900"
                                    >R$ {{ subtotal.toFixed(2) }}</span
                                >
                            </div>

                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 text-sm">Frete</span>
                                <span class="font-semibold text-gray-900"
                                    >R$ {{ shipping.toFixed(2) }}</span
                                >
                            </div>

                            <div
                                v-if="discount > 0"
                                class="flex justify-between items-center py-2 text-green-600 bg-green-50 px-3 rounded-lg"
                            >
                                <span class="font-medium">Desconto</span>
                                <span class="font-bold"
                                    >-R$ {{ discount.toFixed(2) }}</span
                                >
                            </div>

                            <hr class="my-4 border-gray-200" />

                            <div
                                class="flex justify-between items-center text-xl font-bold text-gray-900 py-2"
                            >
                                <span>Total</span>
                                <span class="text-blue-600"
                                    >R$ {{ total.toFixed(2) }}</span
                                >
                            </div>
                        </div>

                        <!-- Coupon Code -->
                        <div class="mt-6">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-3"
                                >Cupom de Desconto</label
                            >
                            <div class="flex space-x-2">
                                <input
                                    v-model="couponCode"
                                    type="text"
                                    placeholder="Digite o código"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                />
                                <button
                                    @click="applyCoupon"
                                    :disabled="!couponCode || applyingCoupon"
                                    class="bg-gray-600 text-white px-4 py-3 rounded-lg hover:bg-gray-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105"
                                >
                                    <i
                                        v-if="applyingCoupon"
                                        class="fas fa-spinner fa-spin"
                                    ></i>
                                    <i v-else class="fas fa-tag"></i>
                                </button>
                            </div>
                            <p
                                v-if="couponError"
                                class="text-red-600 text-sm mt-2"
                            >
                                {{ couponError }}
                            </p>
                            <p
                                v-if="couponSuccess"
                                class="text-green-600 text-sm mt-2"
                            >
                                {{ couponSuccess }}
                            </p>
                        </div>

                        <!-- Checkout Button -->
                        <button
                            @click="proceedToCheckout"
                            class="w-full mt-6 bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white py-4 px-6 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-1"
                        >
                            <i class="fas fa-credit-card mr-2"></i>
                            Finalizar Compra • R$ {{ total.toFixed(2) }}
                        </button>
                        <!-- Security Badges -->
                        <div
                            class="mt-6 flex justify-center space-x-4 text-gray-400"
                        >
                            <i
                                class="fas fa-shield-alt text-lg"
                                title="Compra Segura"
                            ></i>
                            <i
                                class="fas fa-lock text-lg"
                                title="SSL Protegido"
                            ></i>
                            <i
                                class="fas fa-truck text-lg"
                                title="Entrega Garantida"
                            ></i>
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
            return cartItems.value.reduce(
                (total, item) => total + item.quantity,
                0,
            );
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
            if (
                confirm("Tem certeza que deseja remover este item do carrinho?")
            ) {
                cartItems.value = cartItems.value.filter(
                    (item) => item.id !== itemId,
                );
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
/* Transições para itens do carrinho */
.cart-item-enter-active,
.cart-item-leave-active {
    transition: all 0.4s ease;
}

.cart-item-enter-from {
    opacity: 0;
    transform: translateX(-30px);
}

.cart-item-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

.cart-item-move {
    transition: transform 0.3s ease;
}

/* Animações para botões */
.btn-hover {
    transition: all 0.3s ease;
}

.btn-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Gradientes e sombras */
.cart-summary {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Hover effects */
.product-card:hover .product-image {
    transform: scale(1.05);
}

.quantity-btn {
    transition: all 0.2s ease;
}

.quantity-btn:hover:not(:disabled) {
    background-color: #f3f4f6;
    transform: scale(1.1);
}

.quantity-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
