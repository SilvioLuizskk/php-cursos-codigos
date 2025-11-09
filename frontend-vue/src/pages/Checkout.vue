<template>
    <div class="checkout-page max-w-2xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6 text-center">Finalizar Compra</h1>
        <div class="bg-white rounded-lg shadow p-6">
            <div v-if="step === 1">
                <h2 class="font-semibold text-lg mb-4">
                    1. Endereço de Entrega
                </h2>
                <form @submit.prevent="nextStep">
                    <div class="mb-4">
                        <label class="block mb-1 font-medium"
                            >Nome Completo</label
                        >
                        <input
                            v-model="address.name"
                            required
                            class="input"
                            type="text"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Endereço</label>
                        <input
                            v-model="address.street"
                            required
                            class="input"
                            type="text"
                        />
                    </div>
                    <div class="mb-4 flex gap-2">
                        <div class="flex-1">
                            <label class="block mb-1 font-medium">Cidade</label>
                            <input
                                v-model="address.city"
                                required
                                class="input"
                                type="text"
                            />
                        </div>
                        <div class="w-32">
                            <label class="block mb-1 font-medium">UF</label>
                            <input
                                v-model="address.state"
                                required
                                class="input"
                                maxlength="2"
                            />
                        </div>
                        <div class="w-32">
                            <label class="block mb-1 font-medium">CEP</label>
                            <input
                                v-model="address.zip"
                                required
                                class="input"
                                maxlength="9"
                            />
                        </div>
                    </div>
                    <button class="btn btn-primary w-full mt-4" type="submit">
                        Próximo
                    </button>
                </form>
            </div>
            <div v-else-if="step === 2">
                <h2 class="font-semibold text-lg mb-4">2. Frete</h2>
                <form @submit.prevent="nextStep">
                    <div class="mb-4">
                        <label class="block mb-1 font-medium"
                            >Opções de Frete</label
                        >
                        <select v-model="shipping" class="input" required>
                            <option value="">Selecione</option>
                            <option value="normal">
                                PAC - 7 a 10 dias - R$ 20,00
                            </option>
                            <option value="expresso">
                                Sedex - 2 a 4 dias - R$ 40,00
                            </option>
                        </select>
                    </div>
                    <div class="flex justify-between mt-4">
                        <button
                            class="btn btn-outline"
                            @click="prevStep"
                            type="button"
                        >
                            Voltar
                        </button>
                        <button class="btn btn-primary" type="submit">
                            Próximo
                        </button>
                    </div>
                </form>
            </div>
            <div v-else-if="step === 3">
                <h2 class="font-semibold text-lg mb-4">3. Pagamento</h2>
                <form @submit.prevent="nextStep">
                    <div class="mb-4">
                        <label class="block mb-1 font-medium"
                            >Forma de Pagamento</label
                        >
                        <select v-model="payment" class="input" required>
                            <option value="">Selecione</option>
                            <option value="pix">Pix</option>
                            <option value="boleto">Boleto</option>
                            <option value="cartao">Cartão de Crédito</option>
                        </select>
                    </div>
                    <div v-if="payment === 'cartao'">
                        <div class="mb-2">
                            <label class="block mb-1 font-medium"
                                >Número do Cartão</label
                            >
                            <input
                                v-model="card.number"
                                class="input"
                                maxlength="19"
                            />
                        </div>
                        <div class="mb-2 flex gap-2">
                            <div class="flex-1">
                                <label class="block mb-1 font-medium"
                                    >Validade</label
                                >
                                <input
                                    v-model="card.expiry"
                                    class="input"
                                    maxlength="5"
                                    placeholder="MM/AA"
                                />
                            </div>
                            <div class="w-24">
                                <label class="block mb-1 font-medium"
                                    >CVV</label
                                >
                                <input
                                    v-model="card.cvv"
                                    class="input"
                                    maxlength="4"
                                />
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="block mb-1 font-medium"
                                >Nome no Cartão</label
                            >
                            <input v-model="card.name" class="input" />
                        </div>
                    </div>
                    <div class="flex justify-between mt-4">
                        <button
                            class="btn btn-outline"
                            @click="prevStep"
                            type="button"
                        >
                            Voltar
                        </button>
                        <button class="btn btn-primary" type="submit">
                            Próximo
                        </button>
                    </div>
                </form>
            </div>
            <div v-else-if="step === 4">
                <h2 class="font-semibold text-lg mb-4">4. Resumo do Pedido</h2>
                <div class="mb-4">
                    <div class="font-medium">Endereço:</div>
                    <div class="text-sm text-gray-600">
                        {{ address.name }}, {{ address.street }},
                        {{ address.city }}-{{ address.state }},
                        {{ address.zip }}
                    </div>
                </div>
                <div class="mb-4">
                    <div class="font-medium">Frete:</div>
                    <div class="text-sm text-gray-600">
                        {{
                            shipping === "normal"
                                ? "PAC - R$ 20,00"
                                : "Sedex - R$ 40,00"
                        }}
                    </div>
                </div>
                <div class="mb-4">
                    <div class="font-medium">Pagamento:</div>
                    <div class="text-sm text-gray-600">
                        <span v-if="payment === 'pix'">Pix</span>
                        <span v-else-if="payment === 'boleto'">Boleto</span>
                        <span v-else>Cartão de Crédito</span>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="font-medium">Produtos:</div>
                    <ul class="text-sm text-gray-700">
                        <li v-for="item in cart" :key="item.id">
                            {{ item.name }} x{{ item.qty }} - R$
                            {{ (item.price * item.qty).toFixed(2) }}
                        </li>
                    </ul>
                </div>
                <div
                    class="flex justify-between items-center font-bold text-lg mt-4"
                >
                    <span>Total:</span>
                    <span>R$ {{ total.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between mt-6">
                    <button
                        class="btn btn-outline"
                        @click="prevStep"
                        type="button"
                    >
                        Voltar
                    </button>
                    <button class="btn btn-primary" @click="submitOrder">
                        Finalizar Pedido
                    </button>
                </div>
            </div>
            <div v-else-if="step === 5">
                <div class="text-center py-12">
                    <i
                        class="fas fa-check-circle text-green-500 text-5xl mb-4"
                    ></i>
                    <h2 class="text-2xl font-bold mb-2">
                        Pedido realizado com sucesso!
                    </h2>
                    <p class="text-gray-600 mb-4">
                        Você receberá um e-mail com os detalhes do pedido.
                    </p>
                    <router-link to="/" class="btn btn-primary"
                        >Voltar para a loja</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useRouter } from "vue-router";

const step = ref(1);
const address = ref({ name: "", street: "", city: "", state: "", zip: "" });
const shipping = ref("");
const payment = ref("");
const card = ref({ number: "", expiry: "", cvv: "", name: "" });
const cart = ref([]);
const router = useRouter();

const CART_KEY = "ecommerce_cart";

const loadCart = () => {
    const saved = localStorage.getItem(CART_KEY);
    cart.value = saved ? JSON.parse(saved) : [];
};

const total = computed(() => {
    let subtotal = cart.value.reduce(
        (sum, item) => sum + item.price * item.qty,
        0,
    );
    let frete =
        shipping.value === "expresso"
            ? 40
            : shipping.value === "normal"
              ? 20
              : 0;
    return subtotal + frete;
});

const nextStep = () => {
    step.value++;
};
const prevStep = () => {
    step.value--;
};

const submitOrder = () => {
    // Aqui você integraria com a API de pedidos
    localStorage.removeItem(CART_KEY);
    step.value = 5;
};

loadCart();
</script>

<style scoped>
.input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    outline: none;
    transition: box-shadow 0.2s;
}
.input:focus {
    box-shadow: 0 0 0 2px #2563eb33;
    border-color: #2563eb;
}
.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 600;
    transition:
        background 0.2s,
        color 0.2s;
}
.btn-primary {
    background: #2563eb;
    color: #fff;
}
.btn-primary:hover {
    background: #1d4ed8;
}
.btn-outline {
    border: 1px solid #2563eb;
    color: #2563eb;
    background: #fff;
}
.btn-outline:hover {
    background: #f0f6ff;
}
</style>
