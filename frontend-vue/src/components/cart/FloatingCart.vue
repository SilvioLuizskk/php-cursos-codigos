<template>
    <div>
        <!-- Botão flutuante do carrinho -->
        <button
            class="fixed bottom-6 right-6 z-50 bg-primary text-white rounded-full shadow-lg p-4 flex items-center hover:bg-primary-dark transition"
            @click="toggleCart"
            aria-label="Abrir carrinho"
        >
            <i class="fas fa-shopping-cart text-2xl"></i>
            <span
                v-if="cartCount > 0"
                class="ml-2 bg-red-500 text-xs rounded-full px-2 py-0.5"
                >{{ cartCount }}</span
            >
        </button>

        <!-- Mini-carrinho popup -->
        <transition name="fade">
            <div
                v-if="showCart"
                class="fixed bottom-20 right-6 z-50 w-80 bg-white rounded-lg shadow-xl p-4 border border-gray-200"
            >
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold text-lg">Carrinho</h3>
                    <button @click="toggleCart" aria-label="Fechar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div
                    v-if="cart.length === 0"
                    class="text-gray-400 text-center py-8"
                >
                    Seu carrinho está vazio.
                </div>
                <div v-else>
                    <ul
                        class="divide-y divide-gray-200 max-h-60 overflow-y-auto"
                    >
                        <li
                            v-for="item in cart"
                            :key="item.id"
                            class="flex items-center py-2"
                        >
                            <img
                                :src="item.images[0]"
                                class="w-12 h-12 object-cover rounded mr-2 border"
                                alt="Produto"
                            />
                            <div class="flex-1">
                                <div class="font-semibold">{{ item.name }}</div>
                                <div class="text-sm text-gray-500">
                                    Qtd:
                                    <button
                                        @click="updateQty(item, item.qty - 1)"
                                        :disabled="item.qty <= 1"
                                        class="px-1"
                                    >
                                        -
                                    </button>
                                    {{ item.qty }}
                                    <button
                                        @click="updateQty(item, item.qty + 1)"
                                        class="px-1"
                                    >
                                        +
                                    </button>
                                </div>
                                <div class="text-sm text-primary">
                                    R$ {{ (item.price * item.qty).toFixed(2) }}
                                </div>
                            </div>
                            <button
                                @click="removeItem(item)"
                                class="ml-2 text-red-500"
                                aria-label="Remover"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </li>
                    </ul>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="font-bold">Total:</span>
                        <span class="text-primary font-bold text-lg"
                            >R$ {{ cartTotal.toFixed(2) }}</span
                        >
                    </div>
                    <button
                        class="btn btn-primary w-full mt-4"
                        @click="goToCheckout"
                    >
                        Finalizar Compra
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { useRouter } from "vue-router";

const CART_KEY = "ecommerce_cart";
const router = useRouter();

const showCart = ref(false);
const cart = ref([]);

const loadCart = () => {
    const saved = localStorage.getItem(CART_KEY);
    cart.value = saved ? JSON.parse(saved) : [];
};

const saveCart = () => {
    localStorage.setItem(CART_KEY, JSON.stringify(cart.value));
};

const cartCount = computed(() =>
    cart.value.reduce((sum, item) => sum + item.qty, 0),
);
const cartTotal = computed(() =>
    cart.value.reduce((sum, item) => sum + item.price * item.qty, 0),
);

const toggleCart = () => {
    showCart.value = !showCart.value;
};

const updateQty = (item, qty) => {
    if (qty < 1) return;
    const idx = cart.value.findIndex((i) => i.id === item.id);
    if (idx !== -1) {
        cart.value[idx].qty = qty;
        saveCart();
    }
};

const removeItem = (item) => {
    cart.value = cart.value.filter((i) => i.id !== item.id);
    saveCart();
};

const goToCheckout = () => {
    showCart.value = false;
    router.push("/checkout");
};

watch(cart, saveCart, { deep: true });

// Inicializar carrinho ao montar
loadCart();

// Permitir que outros componentes adicionem produtos ao carrinho
window.addToCart = (product) => {
    const idx = cart.value.findIndex((i) => i.id === product.id);
    if (idx !== -1) {
        cart.value[idx].qty += 1;
    } else {
        cart.value.push({ ...product, qty: 1 });
    }
    saveCart();
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
