<template>
    <div class="user-orders-page">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">
                    Meus Pedidos - EstampariaPro
                </h1>

                <!-- Estatísticas dos Pedidos -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">
                        Resumo dos Pedidos
                    </h2>

                    <button
                        @click="fetchOrderStats"
                        :disabled="loading"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg transition-colors mb-4"
                    >
                        {{
                            loading ? "Carregando..." : "Atualizar Estatísticas"
                        }}
                    </button>

                    <div
                        v-if="orderStats"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4"
                    >
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">
                                {{ orderStats.total_orders }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Total de Pedidos
                            </p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">
                                {{ orderStats.completed_orders }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Pedidos Concluídos
                            </p>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ orderStats.pending_orders }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Pedidos Pendentes
                            </p>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <p class="text-2xl font-bold text-red-600">
                                {{ orderStats.cancelled_orders }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Pedidos Cancelados
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Lista de Pedidos -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Histórico de Pedidos
                        </h2>
                        <button
                            @click="fetchOrders"
                            :disabled="loading"
                            class="bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white px-4 py-2 rounded-lg transition-colors"
                        >
                            {{ loading ? "Carregando..." : "Atualizar Lista" }}
                        </button>
                    </div>

                    <div v-if="orders.length > 0" class="space-y-4">
                        <div
                            v-for="order in orders"
                            :key="order.id"
                            class="border rounded-lg p-4 hover:shadow-md transition-shadow"
                        >
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-medium text-gray-900">
                                        Pedido #{{ order.id }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ formatDate(order.created_at) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span
                                        :class="getStatusClass(order.status)"
                                        class="px-2 py-1 rounded-full text-xs font-medium"
                                    >
                                        {{ getStatusText(order.status) }}
                                    </span>
                                    <p
                                        class="text-lg font-bold text-gray-900 mt-1"
                                    >
                                        R$ {{ order.total.toFixed(2) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    {{ order.items_count }} item(ns)
                                </div>
                                <div class="space-x-2">
                                    <button
                                        @click="viewOrder(order.id)"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                    >
                                        Ver Detalhes
                                    </button>
                                    <button
                                        v-if="canCancel(order)"
                                        @click="cancelOrder(order.id)"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors"
                                    >
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else-if="!loading" class="text-center py-12">
                        <p class="text-gray-500">
                            Você ainda não fez nenhum pedido.
                        </p>
                        <router-link
                            to="/produtos"
                            class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors"
                        >
                            Começar a Comprar
                        </router-link>
                    </div>
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
import { useRouter } from "vue-router";
import { useOrders } from "@/composables/useOrders";
import { useNotification } from "@/composables/useNotification";

const router = useRouter();
const { showNotification } = useNotification();
const {
    orders,
    orderStats,
    loading,
    error,
    fetchOrders,
    fetchOrderStats,
    cancelOrder: cancelOrderComposable,
} = useOrders();

const viewOrder = (orderId) => {
    router.push(`/pedidos/${orderId}`);
};

const cancelOrderHandler = async (orderId) => {
    if (confirm("Tem certeza que deseja cancelar este pedido?")) {
        try {
            await cancelOrderComposable(orderId);
            // Recarregar pedidos após cancelamento
            await fetchOrders();
            await fetchOrderStats();
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

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("pt-BR");
};

// Carregar dados ao montar o componente
onMounted(async () => {
    await fetchOrders();
    await fetchOrderStats();
});
</script>

<style scoped>
.user-orders-page {
    min-height: 100vh;
    background-color: #f9fafb;
}
</style>
