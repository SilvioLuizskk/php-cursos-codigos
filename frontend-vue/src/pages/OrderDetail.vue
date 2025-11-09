<template>
    <div class="order-detail-page">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-6">
                    <button
                        @click="$router.go(-1)"
                        class="flex items-center text-blue-600 hover:text-blue-800 transition-colors"
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
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                        Voltar
                    </button>
                </div>

                <div v-if="order" class="space-y-6">
                    <!-- Cabeçalho do Pedido -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">
                                    Pedido #{{ order.id }}
                                </h1>
                                <p class="text-gray-600">
                                    Realizado em
                                    {{ formatDate(order.created_at) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span
                                    :class="getStatusClass(order.status)"
                                    class="px-3 py-1 rounded-full text-sm font-medium"
                                >
                                    {{ getStatusText(order.status) }}
                                </span>
                                <p
                                    class="text-2xl font-bold text-gray-900 mt-2"
                                >
                                    R$ {{ order.total.toFixed(2) }}
                                </p>
                            </div>
                        </div>

                        <div v-if="canCancel(order)" class="mt-4">
                            <button
                                @click="cancelOrder"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors"
                            >
                                Cancelar Pedido
                            </button>
                        </div>
                    </div>

                    <!-- Itens do Pedido -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">
                            Itens do Pedido
                        </h2>

                        <div class="space-y-4">
                            <div
                                v-for="item in order.items"
                                :key="item.id"
                                class="flex items-center space-x-4 border-b pb-4"
                            >
                                <img
                                    :src="
                                        item.product.image ||
                                        '/placeholder-product.jpg'
                                    "
                                    :alt="item.product.name"
                                    class="w-16 h-16 object-cover rounded-lg"
                                />
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">
                                        {{ item.product.name }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Quantidade: {{ item.quantity }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Preço unitário: R$
                                        {{ (parseFloat(item.price) || 0).toFixed(2) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-900">
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

                        <!-- Totais -->
                        <div class="mt-6 border-t pt-4">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span>Subtotal:</span>
                                    <span
                                        >R$
                                        {{ order.subtotal.toFixed(2) }}</span
                                    >
                                </div>
                                <div
                                    v-if="order.shipping_cost"
                                    class="flex justify-between text-sm"
                                >
                                    <span>Frete:</span>
                                    <span
                                        >R$
                                        {{
                                            order.shipping_cost.toFixed(2)
                                        }}</span
                                    >
                                </div>
                                <div
                                    v-if="order.discount_amount"
                                    class="flex justify-between text-sm text-green-600"
                                >
                                    <span>Desconto:</span>
                                    <span
                                        >-R$
                                        {{
                                            order.discount_amount.toFixed(2)
                                        }}</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between font-bold text-lg border-t pt-2"
                                >
                                    <span>Total:</span>
                                    <span>R$ {{ order.total.toFixed(2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações de Entrega e Pagamento -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Endereço de Entrega -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Endereço de Entrega
                            </h3>
                            <div
                                v-if="order.shipping_address"
                                class="text-gray-700"
                            >
                                <p>{{ order.shipping_address.street }}</p>
                                <p>
                                    {{ order.shipping_address.city }},
                                    {{ order.shipping_address.state }}
                                    {{ order.shipping_address.zip_code }}
                                </p>
                                <p>{{ order.shipping_address.country }}</p>
                            </div>
                            <div v-else class="text-gray-500">
                                Endereço não informado
                            </div>
                        </div>

                        <!-- Informações de Pagamento -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Pagamento
                            </h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Método:</span>
                                    <span class="font-medium">{{
                                        getPaymentMethodText(
                                            order.payment_method,
                                        )
                                    }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span
                                        :class="
                                            getPaymentStatusClass(
                                                order.payment_status,
                                            )
                                        "
                                        class="px-2 py-1 rounded-full text-xs font-medium"
                                    >
                                        {{
                                            getPaymentStatusText(
                                                order.payment_status,
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rastreamento -->
                    <div
                        v-if="tracking"
                        class="bg-white rounded-lg shadow-md p-6"
                    >
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Rastreamento
                        </h3>

                        <div class="space-y-4">
                            <div
                                v-for="(event, index) in tracking.events"
                                :key="index"
                                class="flex items-start space-x-4"
                            >
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-3 h-3 bg-blue-600 rounded-full mt-2"
                                    ></div>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">
                                        {{ event.status }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ event.description }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ formatDate(event.date) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else-if="loading" class="text-center py-12">
                    <p class="text-gray-500">
                        Carregando detalhes do pedido...
                    </p>
                </div>

                <div v-else class="text-center py-12">
                    <p class="text-gray-500">Pedido não encontrado.</p>
                </div>

                <div
                    v-if="error"
                    class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg"
                >
                    <p class="text-red-700">{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useOrders } from "@/composables/useOrders";
import { useNotification } from "@/composables/useNotification";

const route = useRoute();
const { showNotification } = useNotification();
const {
    currentOrder,
    loading,
    error,
    fetchOrder,
    cancelOrder: cancelOrderComposable,
    fetchOrderTracking,
} = useOrders();

const order = ref(null);
const tracking = ref(null);

const cancelOrder = async () => {
    if (confirm("Tem certeza que deseja cancelar este pedido?")) {
        try {
            await cancelOrderComposable(order.value.id);
            order.value.status = "cancelled";
            showNotification("Pedido cancelado com sucesso!", "success");
        } catch (err) {
            // Error já tratado no composable
        }
    }
};

const canCancel = (order) => {
    return ["pending", "processing"].includes(order.status);
};

const getStatusClass = (status) => {
    const classes = {
        pending: "bg-yellow-100 text-yellow-800",
        processing: "bg-blue-100 text-blue-800",
        shipped: "bg-purple-100 text-purple-800",
        delivered: "bg-green-100 text-green-800",
        cancelled: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getStatusText = (status) => {
    const texts = {
        pending: "Pendente",
        processing: "Processando",
        shipped: "Enviado",
        delivered: "Entregue",
        cancelled: "Cancelado",
    };
    return texts[status] || status;
};

const getPaymentMethodText = (method) => {
    const methods = {
        credit_card: "Cartão de Crédito",
        debit_card: "Cartão de Débito",
        pix: "PIX",
        boleto: "Boleto",
    };
    return methods[method] || method;
};

const getPaymentStatusClass = (status) => {
    const classes = {
        pending: "bg-yellow-100 text-yellow-800",
        paid: "bg-green-100 text-green-800",
        failed: "bg-red-100 text-red-800",
        refunded: "bg-blue-100 text-blue-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const getPaymentStatusText = (status) => {
    const texts = {
        pending: "Pendente",
        paid: "Pago",
        failed: "Falhou",
        refunded: "Reembolsado",
    };
    return texts[status] || status;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const loadOrderDetails = async () => {
    const orderId = route.params.id;
    if (!orderId) return;

    try {
        order.value = await fetchOrder(orderId);

        // Carregar rastreamento se o pedido estiver enviado ou entregue
        if (["shipped", "delivered"].includes(order.value.status)) {
            tracking.value = await fetchOrderTracking(orderId);
        }
    } catch (err) {
        // Error já tratado no composable
    }
};

onMounted(() => {
    loadOrderDetails();
});
</script>

<style scoped>
.order-detail-page {
    min-height: 100vh;
    background-color: #f9fafb;
}
</style>
