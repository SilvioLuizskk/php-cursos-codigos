<template>
    <div class="max-w-6xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    Gerenciar Pedidos
                </h2>
                <button
                    @click="showCreateModal = true"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                    Novo Pedido
                </button>
            </div>

            <!-- Filtros -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">
                    Filtros
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Status
                        </label>
                        <select
                            v-model="filters.status"
                            @change="loadOrders"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Todos</option>
                            <option value="pending">Pendente</option>
                            <option value="processing">Processando</option>
                            <option value="shipped">Enviado</option>
                            <option value="delivered">Entregue</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Data Inicial
                        </label>
                        <input
                            v-model="filters.start_date"
                            @change="loadOrders"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Data Final
                        </label>
                        <input
                            v-model="filters.end_date"
                            @change="loadOrders"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Buscar por ID
                        </label>
                        <input
                            v-model="filters.order_id"
                            @input="debounceSearch"
                            type="text"
                            placeholder="ID do pedido"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                </div>
            </div>

            <!-- Tabela de Pedidos -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                ID
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Cliente
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Total
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
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="order in orders"
                            :key="order.id"
                            class="hover:bg-gray-50"
                        >
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                            >
                                #{{ order.id }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ order.customer_name }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                R$ {{ order.total.toFixed(2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="getStatusClass(order.status)"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                >
                                    {{ getStatusText(order.status) }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ formatDate(order.created_at) }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                            >
                                <button
                                    @click="viewOrder(order)"
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                >
                                    Ver
                                </button>
                                <button
                                    @click="editOrder(order)"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                >
                                    Editar
                                </button>
                                <button
                                    @click="deleteOrder(order)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="flex justify-between items-center mt-6">
                <div class="text-sm text-gray-700">
                    Mostrando {{ (currentPage - 1) * perPage + 1 }} a
                    {{ Math.min(currentPage * perPage, totalOrders) }} de
                    {{ totalOrders }} pedidos
                </div>
                <div class="flex space-x-2">
                    <button
                        @click="changePage(currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="px-3 py-1 border border-gray-300 rounded-md text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
                    >
                        Anterior
                    </button>
                    <button
                        @click="changePage(currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="px-3 py-1 border border-gray-300 rounded-md text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
                    >
                        Próximo
                    </button>
                </div>
            </div>

            <!-- Modal de Visualização/Edição -->
            <div
                v-if="showModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                @click="closeModal"
            >
                <div
                    class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
                    @click.stop
                >
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{
                                editingOrder
                                    ? "Editar Pedido"
                                    : "Detalhes do Pedido"
                            }}
                        </h3>

                        <form
                            v-if="editingOrder"
                            @submit.prevent="updateOrder"
                            class="space-y-4"
                        >
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Status
                                </label>
                                <select
                                    v-model="editForm.status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="pending">Pendente</option>
                                    <option value="processing">
                                        Processando
                                    </option>
                                    <option value="shipped">Enviado</option>
                                    <option value="delivered">Entregue</option>
                                    <option value="cancelled">Cancelado</option>
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Notas Internas
                                </label>
                                <textarea
                                    v-model="editForm.notes"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Notas sobre o pedido..."
                                ></textarea>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    :disabled="loading"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                                >
                                    <span v-if="loading">Salvando...</span>
                                    <span v-else>Salvar</span>
                                </button>
                            </div>
                        </form>

                        <div v-else>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <strong>ID do Pedido:</strong> #{{
                                            selectedOrder.id
                                        }}
                                    </div>
                                    <div>
                                        <strong>Data:</strong>
                                        {{
                                            formatDate(selectedOrder.created_at)
                                        }}
                                    </div>
                                    <div>
                                        <strong>Cliente:</strong>
                                        {{ selectedOrder.customer_name }}
                                    </div>
                                    <div>
                                        <strong>Email:</strong>
                                        {{ selectedOrder.customer_email }}
                                    </div>
                                    <div>
                                        <strong>Status:</strong>
                                        <span
                                            :class="
                                                getStatusClass(
                                                    selectedOrder.status,
                                                )
                                            "
                                            class="px-2 py-1 text-xs rounded-full ml-2"
                                        >
                                            {{
                                                getStatusText(
                                                    selectedOrder.status,
                                                )
                                            }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>Total:</strong> R$
                                        {{ selectedOrder.total.toFixed(2) }}
                                    </div>
                                </div>

                                <div>
                                    <strong>Itens do Pedido:</strong>
                                    <div class="mt-2 space-y-2">
                                        <div
                                            v-for="item in selectedOrder.items"
                                            :key="item.id"
                                            class="flex justify-between border-b pb-2"
                                        >
                                            <span
                                                >{{ item.product_name }} (x{{
                                                    item.quantity
                                                }})</span
                                            >
                                            <span
                                                >R$
                                                {{
                                                    (
                                                        item.price *
                                                        item.quantity
                                                    ).toFixed(2)
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div v-if="selectedOrder.notes">
                                    <strong>Notas:</strong>
                                    <p class="mt-1 text-gray-600">
                                        {{ selectedOrder.notes }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button
                                    @click="closeModal"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                                >
                                    Fechar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useNotification } from "@/composables/useNotification";
import { useDebounce } from "@/composables/useDebounce";
import { adminService } from "@/services/adminService";

const { showNotification } = useNotification();
const { debounceSearch } = useDebounce(() => loadOrders(), 500);

const loading = ref(false);
const orders = ref([]);
const currentPage = ref(1);
const perPage = ref(20);
const totalOrders = ref(0);
const totalPages = ref(0);

const showModal = ref(false);
const showCreateModal = ref(false);
const selectedOrder = ref(null);
const editingOrder = ref(false);

const filters = ref({
    status: "",
    start_date: "",
    end_date: "",
    order_id: "",
});

const editForm = ref({
    status: "",
    notes: "",
});

const loadOrders = async () => {
    try {
        loading.value = true;
        const params = {
            page: currentPage.value,
            per_page: perPage.value,
            ...filters.value,
        };

        const response = await adminService.getOrders(params);
        orders.value = response.data.data;
        totalOrders.value = response.data.total;
        totalPages.value = response.data.last_page;
    } catch (error) {
        console.error("Erro ao carregar pedidos:", error);
        showNotification("Erro ao carregar pedidos", "error");
    } finally {
        loading.value = false;
    }
};

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        loadOrders();
    }
};

const viewOrder = async (order) => {
    try {
        const response = await adminService.getOrder(order.id);
        selectedOrder.value = response.data;
        editingOrder.value = false;
        showModal.value = true;
    } catch (error) {
        console.error("Erro ao carregar detalhes do pedido:", error);
        showNotification("Erro ao carregar detalhes do pedido", "error");
    }
};

const editOrder = (order) => {
    selectedOrder.value = order;
    editForm.value = {
        status: order.status,
        notes: order.notes || "",
    };
    editingOrder.value = true;
    showModal.value = true;
};

const updateOrder = async () => {
    try {
        loading.value = true;
        await adminService.updateOrder(selectedOrder.value.id, editForm.value);
        showNotification("Pedido atualizado com sucesso!", "success");
        closeModal();
        loadOrders();
    } catch (error) {
        console.error("Erro ao atualizar pedido:", error);
        showNotification("Erro ao atualizar pedido", "error");
    } finally {
        loading.value = false;
    }
};

const deleteOrder = async (order) => {
    if (!confirm("Tem certeza que deseja excluir este pedido?")) return;

    try {
        await adminService.deleteOrder(order.id);
        showNotification("Pedido excluído com sucesso!", "success");
        loadOrders();
    } catch (error) {
        console.error("Erro ao excluir pedido:", error);
        showNotification("Erro ao excluir pedido", "error");
    }
};

const closeModal = () => {
    showModal.value = false;
    selectedOrder.value = null;
    editingOrder.value = false;
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

onMounted(() => {
    loadOrders();
});
</script>
