<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Gerenciar Pedidos</h1>
            <div class="flex items-center space-x-4">
                <select
                    v-model="statusFilter"
                    @change="filterOrders"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Todos os Status</option>
                    <option value="Pendente">Pendente</option>
                    <option value="Processando">Processando</option>
                    <option value="Enviado">Enviado</option>
                    <option value="Entregue">Entregue</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
                <input
                    v-model="searchQuery"
                    @input="filterOrders"
                    type="text"
                    placeholder="Buscar por ID, cliente..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <button
                    @click="exportOrders"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition ml-2"
                >
                    📊 Exportar Pedidos
                </button>
            </div>
        </div>

        <!-- Cards de Resumo -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">
                        {{ orderStats.pending }}
                    </div>
                    <p class="text-sm text-gray-600">Pendentes</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ orderStats.processing }}
                    </div>
                    <p class="text-sm text-gray-600">Processando</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">
                        {{ orderStats.shipped }}
                    </div>
                    <p class="text-sm text-gray-600">Enviados</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">
                        {{ orderStats.delivered }}
                    </div>
                    <p class="text-sm text-gray-600">Entregues</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">
                        {{ orderStats.cancelled }}
                    </div>
                    <p class="text-sm text-gray-600">Cancelados</p>
                </div>
            </div>
        </div>

        <!-- Lista de Pedidos -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Pedido
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Cliente
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Valor
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Data
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Pagamento
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="order in filteredOrders"
                            :key="order.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    #{{ order.id }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ order.items.length }} item(ns)
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ order.customer.name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ order.customer.email }}
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                            >
                                R$ {{ formatCurrency(order.total) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select
                                    :value="order.status"
                                    @change="
                                        updateOrderStatus(
                                            order.id,
                                            $event.target.value,
                                        )
                                    "
                                    class="text-sm border border-gray-300 rounded px-2 py-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="Pendente">Pendente</option>
                                    <option value="Processando">
                                        Processando
                                    </option>
                                    <option value="Enviado">Enviado</option>
                                    <option value="Entregue">Entregue</option>
                                    <option value="Cancelado">Cancelado</option>
                                </select>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ formatDate(order.createdAt) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'px-2 py-1 rounded text-xs',
                                        getPaymentStatusClass(
                                            order.payment.status,
                                        ),
                                    ]"
                                >
                                    {{ order.payment.method }} -
                                    {{ order.payment.status }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                            >
                                <button
                                    @click="viewOrderDetails(order)"
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                >
                                    Ver Detalhes
                                </button>
                                <button
                                    v-if="order.status === 'Enviado'"
                                    @click="addTracking(order)"
                                    class="text-purple-600 hover:text-purple-900 text-xs"
                                >
                                    Rastreamento
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div
                v-if="filteredOrders.length === 0"
                class="text-center py-8 text-gray-500"
            >
                Nenhum pedido encontrado
            </div>
        </div>

        <!-- Modal de Detalhes do Pedido -->
        <div
            v-if="selectedOrder"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div
                class="bg-white rounded-lg w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto"
            >
                <div class="px-6 py-4 border-b">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Pedido #{{ selectedOrder.id }}
                        </h3>
                        <button
                            @click="selectedOrder = null"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Informações do Cliente -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">
                                Dados do Cliente
                            </h4>
                            <div class="space-y-1 text-sm">
                                <p>
                                    <strong>Nome:</strong>
                                    {{ selectedOrder.customer.name }}
                                </p>
                                <p>
                                    <strong>Email:</strong>
                                    {{ selectedOrder.customer.email }}
                                </p>
                                <p>
                                    <strong>Telefone:</strong>
                                    {{ selectedOrder.customer.phone }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">
                                Endereço de Entrega
                            </h4>
                            <div class="space-y-1 text-sm">
                                <p>{{ selectedOrder.shipping.address }}</p>
                                <p>
                                    {{ selectedOrder.shipping.city }},
                                    {{ selectedOrder.shipping.state }} -
                                    {{ selectedOrder.shipping.zipCode }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Itens do Pedido -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">
                            Itens do Pedido
                        </h4>
                        <div class="space-y-3">
                            <div
                                v-for="item in selectedOrder.items"
                                :key="item.id"
                                class="flex items-center justify-between border-b pb-3"
                            >
                                <div class="flex items-center space-x-3">
                                    <img
                                        :src="item.image"
                                        :alt="item.name"
                                        class="w-12 h-12 object-cover rounded"
                                    />
                                    <div>
                                        <p class="font-medium">
                                            {{ item.name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Quantidade: {{ item.quantity }}
                                        </p>
                                    </div>
                                </div>
                                <span class="font-medium"
                                    >R$
                                    {{
                                        formatCurrency(
                                            item.price * item.quantity,
                                        )
                                    }}</span
                                >
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t">
                            <div
                                class="flex justify-between text-lg font-semibold"
                            >
                                <span>Total:</span>
                                <span
                                    >R$
                                    {{
                                        formatCurrency(selectedOrder.total)
                                    }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Informações de Pagamento e Entrega -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">
                                Pagamento
                            </h4>
                            <div class="space-y-1 text-sm">
                                <p>
                                    <strong>Método:</strong>
                                    {{ selectedOrder.payment.method }}
                                </p>
                                <p>
                                    <strong>Status:</strong>
                                    <span
                                        :class="[
                                            'px-2 py-1 rounded text-xs ml-1',
                                            getPaymentStatusClass(
                                                selectedOrder.payment.status,
                                            ),
                                        ]"
                                    >
                                        {{ selectedOrder.payment.status }}
                                    </span>
                                </p>
                                <p v-if="selectedOrder.payment.transactionId">
                                    <strong>ID Transação:</strong>
                                    {{ selectedOrder.payment.transactionId }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">
                                Entrega
                            </h4>
                            <div class="space-y-1 text-sm">
                                <p>
                                    <strong>Método:</strong>
                                    {{ selectedOrder.shipping.method }}
                                </p>
                                <p>
                                    <strong>Status:</strong>
                                    {{ selectedOrder.status }}
                                </p>
                                <p v-if="selectedOrder.trackingCode">
                                    <strong>Código de Rastreamento:</strong>
                                    {{ selectedOrder.trackingCode }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button
                            v-if="
                                selectedOrder.status !== 'Cancelado' &&
                                selectedOrder.status !== 'Entregue'
                            "
                            @click="cancelOrder(selectedOrder.id)"
                            class="px-4 py-2 text-red-600 border border-red-600 rounded hover:bg-red-50"
                        >
                            Cancelar Pedido
                        </button>
                        <button
                            v-if="selectedOrder.status === 'Processando'"
                            @click="markAsShipped(selectedOrder.id)"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            Marcar como Enviado
                        </button>
                        <button
                            v-if="selectedOrder.status === 'Enviado'"
                            @click="markAsDelivered(selectedOrder.id)"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                        >
                            Marcar como Entregue
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Rastreamento -->
        <div
            v-if="showTrackingModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold mb-4">
                    Adicionar Código de Rastreamento
                </h3>
                <div class="space-y-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Código de Rastreamento</label
                        >
                        <input
                            v-model="trackingCode"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="BR123456789BR"
                        />
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button
                            @click="showTrackingModal = false"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="saveTracking"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                        >
                            Salvar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useNotification } from "@/composables/useNotification";

const { showNotification } = useNotification();

const orders = ref([]);
const filteredOrders = ref([]);
const selectedOrder = ref(null);
const showTrackingModal = ref(false);
const trackingOrderId = ref(null);
const trackingCode = ref("");
const statusFilter = ref("");
const searchQuery = ref("");

const orderStats = computed(() => {
    const stats = {
        pending: 0,
        processing: 0,
        shipped: 0,
        delivered: 0,
        cancelled: 0,
    };
    orders.value.forEach((order) => {
        switch (order.status) {
            case "Pendente":
                stats.pending++;
                break;
            case "Processando":
                stats.processing++;
                break;
            case "Enviado":
                stats.shipped++;
                break;
            case "Entregue":
                stats.delivered++;
                break;
            case "Cancelado":
                stats.cancelled++;
                break;
        }
    });
    return stats;
});

// Simulação de dados - substitua por chamadas reais da API
const loadOrders = async () => {
    try {
        orders.value = [
            {
                id: "00123",
                customer: {
                    name: "João Silva",
                    email: "joao@email.com",
                    phone: "(11) 99999-9999",
                },
                items: [
                    {
                        id: 1,
                        name: "Chinelo Praia Azul",
                        price: 49.9,
                        quantity: 1,
                        image: "https://via.placeholder.com/50x50?text=P1",
                    },
                    {
                        id: 2,
                        name: "Chinelo Casual Preto",
                        price: 39.9,
                        quantity: 2,
                        image: "https://via.placeholder.com/50x50?text=P2",
                    },
                ],
                total: 129.7,
                status: "Processando",
                createdAt: "2024-01-15T10:30:00Z",
                payment: {
                    method: "PIX",
                    status: "Aprovado",
                    transactionId: "PIX123456",
                },
                shipping: {
                    method: "PAC",
                    address: "Rua das Flores, 123",
                    city: "São Paulo",
                    state: "SP",
                    zipCode: "01234-567",
                },
                trackingCode: null,
            },
            {
                id: "00122",
                customer: {
                    name: "Maria Santos",
                    email: "maria@email.com",
                    phone: "(11) 88888-8888",
                },
                items: [
                    {
                        id: 3,
                        name: "Chinelo Esportivo Branco",
                        price: 69.9,
                        quantity: 1,
                        image: "https://via.placeholder.com/50x50?text=P3",
                    },
                ],
                total: 69.9,
                status: "Enviado",
                createdAt: "2024-01-14T15:45:00Z",
                payment: {
                    method: "Cartão de Crédito",
                    status: "Aprovado",
                    transactionId: "CC789012",
                },
                shipping: {
                    method: "SEDEX",
                    address: "Av. Paulista, 456",
                    city: "São Paulo",
                    state: "SP",
                    zipCode: "01310-100",
                },
                trackingCode: "BR987654321BR",
            },
            {
                id: "00121",
                customer: {
                    name: "Pedro Oliveira",
                    email: "pedro@email.com",
                    phone: "(11) 77777-7777",
                },
                items: [
                    {
                        id: 4,
                        name: "Chinelo Infantil Colorido",
                        price: 39.9,
                        quantity: 1,
                        image: "https://via.placeholder.com/50x50?text=P4",
                    },
                ],
                total: 39.9,
                status: "Entregue",
                createdAt: "2024-01-13T09:15:00Z",
                payment: {
                    method: "Dinheiro",
                    status: "Pago",
                    transactionId: null,
                },
                shipping: {
                    method: "Retirada na Loja",
                    address: "Rua dos Chinelos, 789",
                    city: "São Paulo",
                    state: "SP",
                    zipCode: "01234-567",
                },
                trackingCode: null,
            },
        ];
        filterOrders();
    } catch (error) {
        console.error("Erro ao carregar pedidos:", error);
        showNotification("Erro ao carregar pedidos", "error");
    }
};

const filterOrders = () => {
    let filtered = orders.value;

    if (statusFilter.value) {
        filtered = filtered.filter(
            (order) => order.status === statusFilter.value,
        );
    }

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(
            (order) =>
                order.id.toLowerCase().includes(query) ||
                order.customer.name.toLowerCase().includes(query) ||
                order.customer.email.toLowerCase().includes(query),
        );
    }

    filteredOrders.value = filtered;
};

const updateOrderStatus = async (orderId, newStatus) => {
    try {
        const order = orders.value.find((o) => o.id === orderId);
        if (order) {
            order.status = newStatus;
            showNotification(
                `Status do pedido #${orderId} atualizado para ${newStatus}`,
                "success",
            );
        }
    } catch (error) {
        console.error("Erro ao atualizar status:", error);
        showNotification("Erro ao atualizar status do pedido", "error");
    }
};

const viewOrderDetails = (order) => {
    selectedOrder.value = order;
};

const addTracking = (order) => {
    trackingOrderId.value = order.id;
    trackingCode.value = order.trackingCode || "";
    showTrackingModal.value = true;
};

const saveTracking = async () => {
    try {
        const order = orders.value.find((o) => o.id === trackingOrderId.value);
        if (order) {
            order.trackingCode = trackingCode.value;
            showNotification(
                "Código de rastreamento adicionado com sucesso!",
                "success",
            );
        }
        showTrackingModal.value = false;
        trackingCode.value = "";
        trackingOrderId.value = null;
    } catch (error) {
        console.error("Erro ao salvar rastreamento:", error);
        showNotification("Erro ao salvar código de rastreamento", "error");
    }
};

const cancelOrder = async (orderId) => {
    if (!confirm("Tem certeza que deseja cancelar este pedido?")) return;

    try {
        const order = orders.value.find((o) => o.id === orderId);
        if (order) {
            order.status = "Cancelado";
            showNotification(
                `Pedido #${orderId} cancelado com sucesso`,
                "success",
            );
        }
    } catch (error) {
        console.error("Erro ao cancelar pedido:", error);
        showNotification("Erro ao cancelar pedido", "error");
    }
};

const markAsShipped = async (orderId) => {
    try {
        const order = orders.value.find((o) => o.id === orderId);
        if (order) {
            order.status = "Enviado";
            showNotification(
                `Pedido #${orderId} marcado como enviado`,
                "success",
            );
        }
    } catch (error) {
        console.error("Erro ao marcar como enviado:", error);
        showNotification("Erro ao atualizar status", "error");
    }
};

const markAsDelivered = async (orderId) => {
    try {
        const order = orders.value.find((o) => o.id === orderId);
        if (order) {
            order.status = "Entregue";
            showNotification(
                `Pedido #${orderId} marcado como entregue`,
                "success",
            );
        }
    } catch (error) {
        console.error("Erro ao marcar como entregue:", error);
        showNotification("Erro ao atualizar status", "error");
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("pt-BR", {
        style: "decimal",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value);
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

const getPaymentStatusClass = (status) => {
    const classes = {
        Aprovado: "bg-green-100 text-green-800",
        Pendente: "bg-yellow-100 text-yellow-800",
        Pago: "bg-green-100 text-green-800",
        Recusado: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const exportOrders = () => {
    try {
        // Filtrar apenas os pedidos que estão sendo exibidos
        const ordersToExport = filteredOrders.value.map((order) => ({
            id: order.id,
            cliente: order.customer.name,
            email: order.customer.email,
            telefone: order.customer.phone,
            valor: order.total,
            status: order.status,
            data: order.createdAt,
            pagamento: {
                metodo: order.payment.method,
                status: order.payment.status,
            },
            entrega: {
                metodo: order.shipping.method,
                endereco: order.shipping.address,
                cidade: order.shipping.city,
                estado: order.shipping.state,
                cep: order.shipping.zipCode,
            },
            rastreamento: order.trackingCode,
            itens: order.items.map((item) => ({
                nome: item.name,
                quantidade: item.quantity,
                preco: item.price,
            })),
        }));

        const exportData = {
            dataGeracao: new Date().toLocaleString("pt-BR"),
            totalPedidos: ordersToExport.length,
            filtroStatus: statusFilter.value || "Todos",
            pedidos: ordersToExport,
        };

        // Converter para JSON e fazer download
        const dataStr = JSON.stringify(exportData, null, 2);
        const dataUri =
            "data:application/json;charset=utf-8," +
            encodeURIComponent(dataStr);

        const exportFileDefaultName = `pedidos-${statusFilter.value || "todos"}-${new Date().toISOString().split("T")[0]}.json`;

        const linkElement = document.createElement("a");
        linkElement.setAttribute("href", dataUri);
        linkElement.setAttribute("download", exportFileDefaultName);
        linkElement.click();

        showNotification(
            `${ordersToExport.length} pedidos exportados com sucesso!`,
            "success",
        );
    } catch (error) {
        console.error("Erro ao exportar pedidos:", error);
        showNotification("Erro ao exportar pedidos", "error");
    }
};

onMounted(() => {
    loadOrders();
});
</script>

<style scoped>
.space-y-6 > * + * {
    margin-top: 1.5rem;
}
</style>
