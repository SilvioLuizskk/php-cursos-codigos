<template>
    <div class="max-w-6xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                Métricas e Analytics
            </h2>

            <!-- Filtros de Data -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">
                    Período
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Data Inicial
                        </label>
                        <input
                            v-model="dateRange.start"
                            @change="loadMetrics"
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
                            v-model="dateRange.end"
                            @change="loadMetrics"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>

                    <div class="flex items-end">
                        <button
                            @click="loadMetrics"
                            :disabled="loading"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
                        >
                            <span v-if="loading">Carregando...</span>
                            <span v-else>Atualizar</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cards de Métricas Principais -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
            >
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Total de Usuários
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ metrics.totalUsers }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Total de Pedidos
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ metrics.totalOrders }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"
                                    ></path>
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Receita Total
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                R$ {{ metrics.totalRevenue.toFixed(2) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">
                                Produtos Vendidos
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ metrics.totalProductsSold }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos e Análises Detalhadas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Gráfico de Vendas por Período -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        Vendas por Período
                    </h3>
                    <div
                        class="h-64 flex items-center justify-center text-gray-500"
                    >
                        <div class="text-center">
                            <svg
                                class="w-12 h-12 mx-auto mb-4 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                            <p>Gráfico de vendas será implementado aqui</p>
                            <p class="text-sm mt-2">
                                Integração com biblioteca de gráficos
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Top Produtos -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        Produtos Mais Vendidos
                    </h3>
                    <div class="space-y-4">
                        <div
                            v-for="(product, index) in metrics.topProducts"
                            :key="product.id"
                            class="flex items-center justify-between"
                        >
                            <div class="flex items-center">
                                <span
                                    class="w-6 h-6 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-sm font-medium mr-3"
                                >
                                    {{ index + 1 }}
                                </span>
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ product.name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ product.sales }} vendas
                                    </p>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-gray-900"
                                >R$ {{ product.revenue.toFixed(2) }}</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Status dos Pedidos -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        Status dos Pedidos
                    </h3>
                    <div class="space-y-3">
                        <div
                            v-for="status in metrics.orderStatuses"
                            :key="status.status"
                            class="flex items-center justify-between"
                        >
                            <div class="flex items-center">
                                <div
                                    :class="getStatusColor(status.status)"
                                    class="w-3 h-3 rounded-full mr-3"
                                ></div>
                                <span class="text-sm text-gray-700">{{
                                    getStatusText(status.status)
                                }}</span>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-sm font-medium text-gray-900"
                                    >{{ status.count }}</span
                                >
                                <span class="text-xs text-gray-500 ml-1"
                                    >({{ status.percentage }}%)</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receita por Categoria -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        Receita por Categoria
                    </h3>
                    <div class="space-y-4">
                        <div
                            v-for="category in metrics.revenueByCategory"
                            :key="category.category"
                            class="flex items-center justify-between"
                        >
                            <span class="text-sm text-gray-700">{{
                                category.category
                            }}</span>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-900"
                                    >R$ {{ category.revenue.toFixed(2) }}</span
                                >
                                <div
                                    class="w-full bg-gray-200 rounded-full h-2 mt-1"
                                >
                                    <div
                                        :style="{
                                            width: category.percentage + '%',
                                        }"
                                        class="bg-blue-600 h-2 rounded-full"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Relatórios Exportáveis -->
            <div class="mt-8 bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">
                    Relatórios Exportáveis
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button
                        @click="exportReport('sales')"
                        class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        Relatório de Vendas
                    </button>

                    <button
                        @click="exportReport('products')"
                        class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        Relatório de Produtos
                    </button>

                    <button
                        @click="exportReport('customers')"
                        class="flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        Relatório de Clientes
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useNotification } from "@/composables/useNotification";
import { adminService } from "@/services/adminService";

const { showNotification } = useNotification();

const loading = ref(false);
const dateRange = ref({
    start: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000)
        .toISOString()
        .split("T")[0], // 30 dias atrás
    end: new Date().toISOString().split("T")[0], // hoje
});

const metrics = ref({
    totalUsers: 0,
    totalOrders: 0,
    totalRevenue: 0,
    totalProductsSold: 0,
    topProducts: [],
    orderStatuses: [],
    revenueByCategory: [],
});

const loadMetrics = async () => {
    try {
        loading.value = true;
        const params = {
            start_date: dateRange.value.start,
            end_date: dateRange.value.end,
        };

        const response = await adminService.getMetrics(params);
        metrics.value = response.data;
    } catch (error) {
        console.error("Erro ao carregar métricas:", error);
        showNotification("Erro ao carregar métricas", "error");
    } finally {
        loading.value = false;
    }
};

const exportReport = async (type) => {
    try {
        const params = {
            start_date: dateRange.value.start,
            end_date: dateRange.value.end,
            type: type,
        };

        const response = await adminService.exportReport(params);

        // Criar link para download
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute(
            "download",
            `relatorio-${type}-${dateRange.value.start}-${dateRange.value.end}.csv`,
        );
        document.body.appendChild(link);
        link.click();
        link.remove();

        showNotification("Relatório exportado com sucesso!", "success");
    } catch (error) {
        console.error("Erro ao exportar relatório:", error);
        showNotification("Erro ao exportar relatório", "error");
    }
};

const getStatusColor = (status) => {
    const colors = {
        pending: "bg-yellow-400",
        processing: "bg-blue-400",
        shipped: "bg-purple-400",
        delivered: "bg-green-400",
        cancelled: "bg-red-400",
    };
    return colors[status] || "bg-gray-400";
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

onMounted(() => {
    loadMetrics();
});
</script>
