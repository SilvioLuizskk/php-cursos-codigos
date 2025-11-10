<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Finalizar Compra
                </h1>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span
                        :class="step >= 1 ? 'text-blue-600 font-semibold' : ''"
                        >Carrinho</span
                    >
                    <span>→</span>
                    <span
                        :class="step >= 2 ? 'text-blue-600 font-semibold' : ''"
                        >Entrega</span
                    >
                    <span>→</span>
                    <span
                        :class="step >= 3 ? 'text-blue-600 font-semibold' : ''"
                        >Pagamento</span
                    >
                    <span>→</span>
                    <span
                        :class="step >= 4 ? 'text-blue-600 font-semibold' : ''"
                        >Confirmação</span
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Formulário Principal -->
                <div class="lg:col-span-2">
                    <!-- Etapa 1: Revisão do Carrinho -->
                    <div
                        v-if="step === 1"
                        class="bg-white rounded-lg shadow p-6"
                    >
                        <h2 class="text-xl font-semibold mb-6">
                            Revisar Pedido
                        </h2>

                        <div class="space-y-4">
                            <div
                                v-for="item in cartItems"
                                :key="item.id"
                                class="flex items-center space-x-4 p-4 border rounded-lg"
                            >
                                <img
                                    :src="item.image"
                                    :alt="item.name"
                                    class="w-16 h-16 object-cover rounded"
                                />
                                <div class="flex-1">
                                    <h3 class="font-medium">{{ item.name }}</h3>
                                    <p class="text-sm text-gray-600">
                                        Quantidade: {{ item.quantity }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">
                                        R$
                                        {{
                                            (
                                                item.price * item.quantity
                                            ).toFixed(2)
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between">
                            <router-link
                                to="/carrinho"
                                class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                            >
                                ← Voltar ao Carrinho
                            </router-link>
                            <button
                                @click="step = 2"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                            >
                                Continuar →
                            </button>
                        </div>
                    </div>

                    <!-- Etapa 2: Dados de Entrega -->
                    <div
                        v-if="step === 2"
                        class="bg-white rounded-lg shadow p-6"
                    >
                        <h2 class="text-xl font-semibold mb-6">
                            Dados de Entrega
                        </h2>

                        <form
                            @submit.prevent="proceedToPayment"
                            class="space-y-6"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Nome Completo *
                                    </label>
                                    <input
                                        v-model="shippingForm.name"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Email *
                                    </label>
                                    <input
                                        v-model="shippingForm.email"
                                        type="email"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Telefone *
                                </label>
                                <input
                                    v-model="shippingForm.phone"
                                    type="tel"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    CEP *
                                </label>
                                <input
                                    v-model="shippingForm.zipCode"
                                    type="text"
                                    required
                                    @blur="searchZipCode"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Endereço *
                                    </label>
                                    <input
                                        v-model="shippingForm.address"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Número *
                                    </label>
                                    <input
                                        v-model="shippingForm.number"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Complemento
                                </label>
                                <input
                                    v-model="shippingForm.complement"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Bairro *
                                    </label>
                                    <input
                                        v-model="shippingForm.neighborhood"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Cidade *
                                    </label>
                                    <input
                                        v-model="shippingForm.city"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Estado *
                                    </label>
                                    <select
                                        v-model="shippingForm.state"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                        <option value="">Selecione...</option>
                                        <option value="AC">Acre</option>
                                        <option value="AL">Alagoas</option>
                                        <option value="AP">Amapá</option>
                                        <option value="AM">Amazonas</option>
                                        <option value="BA">Bahia</option>
                                        <option value="CE">Ceará</option>
                                        <option value="DF">
                                            Distrito Federal
                                        </option>
                                        <option value="ES">
                                            Espírito Santo
                                        </option>
                                        <option value="GO">Goiás</option>
                                        <option value="MA">Maranhão</option>
                                        <option value="MT">Mato Grosso</option>
                                        <option value="MS">
                                            Mato Grosso do Sul
                                        </option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="PA">Pará</option>
                                        <option value="PB">Paraíba</option>
                                        <option value="PR">Paraná</option>
                                        <option value="PE">Pernambuco</option>
                                        <option value="PI">Piauí</option>
                                        <option value="RJ">
                                            Rio de Janeiro
                                        </option>
                                        <option value="RN">
                                            Rio Grande do Norte
                                        </option>
                                        <option value="RS">
                                            Rio Grande do Sul
                                        </option>
                                        <option value="RO">Rondônia</option>
                                        <option value="RR">Roraima</option>
                                        <option value="SC">
                                            Santa Catarina
                                        </option>
                                        <option value="SP">São Paulo</option>
                                        <option value="SE">Sergipe</option>
                                        <option value="TO">Tocantins</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <button
                                    type="button"
                                    @click="step = 1"
                                    class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                                >
                                    ← Voltar
                                </button>
                                <button
                                    type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                                >
                                    Continuar para Pagamento →
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Etapa 3: Método de Pagamento -->
                    <div
                        v-if="step === 3"
                        class="bg-white rounded-lg shadow p-6"
                    >
                        <h2 class="text-xl font-semibold mb-6">
                            Método de Pagamento
                        </h2>

                        <form @submit.prevent="reviewOrder" class="space-y-6">
                            <div class="space-y-4">
                                <div class="border rounded-lg p-4">
                                    <label class="flex items-center">
                                        <input
                                            v-model="paymentForm.method"
                                            type="radio"
                                            value="credit_card"
                                            class="mr-3"
                                        />
                                        <span class="font-medium"
                                            >Cartão de Crédito</span
                                        >
                                    </label>
                                    <div
                                        v-if="
                                            paymentForm.method === 'credit_card'
                                        "
                                        class="mt-4 space-y-4"
                                    >
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Número do Cartão *
                                            </label>
                                            <input
                                                v-model="paymentForm.cardNumber"
                                                type="text"
                                                placeholder="1234 5678 9012 3456"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            />
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-1"
                                                >
                                                    Validade *
                                                </label>
                                                <input
                                                    v-model="paymentForm.expiry"
                                                    type="text"
                                                    placeholder="MM/AA"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                />
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-1"
                                                >
                                                    CVV *
                                                </label>
                                                <input
                                                    v-model="paymentForm.cvv"
                                                    type="text"
                                                    placeholder="123"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                />
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1"
                                            >
                                                Nome no Cartão *
                                            </label>
                                            <input
                                                v-model="paymentForm.cardName"
                                                type="text"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="border rounded-lg p-4">
                                    <label class="flex items-center">
                                        <input
                                            v-model="paymentForm.method"
                                            type="radio"
                                            value="pix"
                                            class="mr-3"
                                        />
                                        <span class="font-medium">PIX</span>
                                    </label>
                                    <p
                                        v-if="paymentForm.method === 'pix'"
                                        class="mt-2 text-sm text-gray-600"
                                    >
                                        Você receberá o código PIX após
                                        confirmar o pedido.
                                    </p>
                                </div>

                                <div class="border rounded-lg p-4">
                                    <label class="flex items-center">
                                        <input
                                            v-model="paymentForm.method"
                                            type="radio"
                                            value="boleto"
                                            class="mr-3"
                                        />
                                        <span class="font-medium"
                                            >Boleto Bancário</span
                                        >
                                    </label>
                                    <p
                                        v-if="paymentForm.method === 'boleto'"
                                        class="mt-2 text-sm text-gray-600"
                                    >
                                        O boleto será gerado após a confirmação
                                        do pedido.
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <button
                                    type="button"
                                    @click="step = 2"
                                    class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                                >
                                    ← Voltar
                                </button>
                                <button
                                    type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                                >
                                    Revisar Pedido →
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Etapa 4: Revisão e Confirmação -->
                    <div
                        v-if="step === 4"
                        class="bg-white rounded-lg shadow p-6"
                    >
                        <h2 class="text-xl font-semibold mb-6">
                            Revisar e Confirmar
                        </h2>

                        <div class="space-y-6">
                            <!-- Resumo do Pedido -->
                            <div>
                                <h3 class="font-medium mb-4">
                                    Itens do Pedido
                                </h3>
                                <div class="space-y-2">
                                    <div
                                        v-for="item in cartItems"
                                        :key="item.id"
                                        class="flex justify-between text-sm"
                                    >
                                        <span
                                            >{{ item.name }} ({{
                                                item.quantity
                                            }}x)</span
                                        >
                                        <span
                                            >R$
                                            {{
                                                (
                                                    item.price * item.quantity
                                                ).toFixed(2)
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Endereço de Entrega -->
                            <div>
                                <h3 class="font-medium mb-2">
                                    Endereço de Entrega
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ shippingForm.name }}<br />
                                    {{ shippingForm.address }},
                                    {{ shippingForm.number }}<br />
                                    {{ shippingForm.complement ?
                                    shippingForm.complement + '<br />' : '' }}
                                    {{ shippingForm.neighborhood }},
                                    {{ shippingForm.city }} -
                                    {{ shippingForm.state }}<br />
                                    CEP: {{ shippingForm.zipCode }}
                                </p>
                            </div>

                            <!-- Método de Pagamento -->
                            <div>
                                <h3 class="font-medium mb-2">
                                    Método de Pagamento
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{
                                        paymentForm.method === "credit_card"
                                            ? "Cartão de Crédito"
                                            : paymentForm.method === "pix"
                                              ? "PIX"
                                              : "Boleto Bancário"
                                    }}
                                </p>
                            </div>

                            <div class="border-t pt-4">
                                <div
                                    class="flex justify-between text-lg font-semibold"
                                >
                                    <span>Total:</span>
                                    <span>R$ {{ total.toFixed(2) }}</span>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <button
                                    @click="step = 3"
                                    class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                                >
                                    ← Voltar
                                </button>
                                <button
                                    @click="placeOrder"
                                    :disabled="placingOrder"
                                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50"
                                >
                                    {{
                                        placingOrder
                                            ? "Processando..."
                                            : "Finalizar Compra"
                                    }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumo do Pedido (Lateral) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                        <h3 class="text-lg font-semibold mb-4">
                            Resumo do Pedido
                        </h3>

                        <div class="space-y-3 mb-4">
                            <div
                                v-for="item in cartItems"
                                :key="item.id"
                                class="flex justify-between text-sm"
                            >
                                <span class="truncate mr-2">{{
                                    item.name
                                }}</span>
                                <span>{{ item.quantity }}x</span>
                            </div>
                        </div>

                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal:</span>
                                <span>R$ {{ subtotal.toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Frete:</span>
                                <span>R$ {{ shipping.toFixed(2) }}</span>
                            </div>
                            <div
                                class="flex justify-between text-lg font-semibold border-t pt-2"
                            >
                                <span>Total:</span>
                                <span>R$ {{ total.toFixed(2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useCart } from "@/composables/useCart";
import { useAuth } from "@/composables/useAuth";
import { useNotification } from "@/composables/useNotification";

const router = useRouter();
const { cartItems, clearCart } = useCart();
const { user } = useAuth();
const { showNotification } = useNotification();

// Estado do checkout
const step = ref(1);
const placingOrder = ref(false);

// Formulários
const shippingForm = ref({
    name: "",
    email: "",
    phone: "",
    zipCode: "",
    address: "",
    number: "",
    complement: "",
    neighborhood: "",
    city: "",
    state: "",
});

const paymentForm = ref({
    method: "credit_card",
    cardNumber: "",
    expiry: "",
    cvv: "",
    cardName: "",
});

// Cálculos
const subtotal = computed(() => {
    return cartItems.value.reduce(
        (total, item) => total + item.price * item.quantity,
        0,
    );
});

const shipping = computed(() => {
    // Lógica simples de frete - pode ser expandida
    return subtotal.value > 100 ? 0 : 15.0;
});

const total = computed(() => {
    return subtotal.value + shipping.value;
});

// Métodos
const proceedToPayment = () => {
    step.value = 3;
};

const reviewOrder = () => {
    step.value = 4;
};

const searchZipCode = async () => {
    // Implementar busca de CEP via API
    // Por enquanto, apenas placeholder
    console.log("Buscando CEP:", shippingForm.value.zipCode);
};

const placeOrder = async () => {
    placingOrder.value = true;

    try {
        // Aqui seria a chamada para a API para criar o pedido
        const orderData = {
            items: cartItems.value,
            shipping: shippingForm.value,
            payment: paymentForm.value,
            total: total.value,
        };

        console.log("Criando pedido:", orderData);

        // Simular chamada da API
        await new Promise((resolve) => setTimeout(resolve, 2000));

        // Limpar carrinho e redirecionar
        clearCart();
        showNotification("Pedido realizado com sucesso!", "success");
        router.push("/pedidos");
    } catch (error) {
        console.error("Erro ao criar pedido:", error);
        showNotification("Erro ao processar pedido. Tente novamente.", "error");
    } finally {
        placingOrder.value = false;
    }
};

// Preencher dados do usuário logado
onMounted(() => {
    if (user.value) {
        shippingForm.value.name = user.value.name || "";
        shippingForm.value.email = user.value.email || "";
    }

    // Redirecionar se carrinho estiver vazio
    if (cartItems.value.length === 0) {
        router.push("/carrinho");
    }
});
</script>

<style scoped>
/* Estilos adicionais se necessário */
</style>
