<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">
                Dashboard de Métricas
            </h1>
            <div class="flex items-center space-x-4">
                <select
                    v-model="selectedPeriod"
                    @change="loadMetrics"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="today">Hoje</option>
                    <option value="week">Esta Semana</option>
                    <option value="month">Este Mês</option>
                    <option value="year">Este Ano</option>
                </select>
                <button
                    @click="loadMetrics"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
                >
                    Atualizar
                </button>
                <button
                    @click="exportReport"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition ml-2"
                >
                    📊 Exportar Relatório
                </button>
            </div>
        </div>

        <!-- Cards de Métricas Principais -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg
                            class="w-6 h-6 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                            ></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">
                            Vendas Totais
                        </p>
                        <p class="text-2xl font-bold text-gray-900">
                            R$ {{ formatCurrency(metrics.totalSales) }}
                        </p>
                        <p class="text-sm text-green-600">
                            +12% vs período anterior
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg
                            class="w-6 h-6 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                            ></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pedidos</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ metrics.totalOrders }}
                        </p>
                        <p class="text-sm text-green-600">
                            +8% vs período anterior
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg
                            class="w-6 h-6 text-yellow-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            ></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">
                            Visitantes
                        </p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ metrics.totalVisitors }}
                        </p>
                        <p class="text-sm text-red-600">
                            -3% vs período anterior
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg
                            class="w-6 h-6 text-purple-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            ></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">
                            Conversão
                        </p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ metrics.conversionRate }}%
                        </p>
                        <p class="text-sm text-green-600">
                            +5% vs período anterior
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos e Análises -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Vendas por Período -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Vendas por Período
                </h3>
                <div
                    class="h-64 flex items-center justify-center bg-gray-50 rounded"
                >
                    <div class="text-center">
                        <svg
                            class="w-12 h-12 text-gray-400 mx-auto mb-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            ></path>
                        </svg>
                        <p class="text-gray-500">
                            Gráfico de vendas (simulado)
                        </p>
                        <p class="text-sm text-gray-400 mt-1">
                            Integração com Google Analytics ou similar
                        </p>
                    </div>
                </div>
            </div>

            <!-- Produtos Mais Vendidos -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Produtos Mais Vendidos
                </h3>
                <div class="space-y-3">
                    <div
                        v-for="product in metrics.topProducts"
                        :key="product.id"
                        class="flex items-center justify-between"
                    >
                        <div class="flex items-center space-x-3">
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="w-10 h-10 object-cover rounded"
                            />
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ product.name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ product.sales }} vendas
                                </p>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-gray-900"
                            >R$ {{ formatCurrency(product.revenue) }}</span
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas de Carrinho e Conversão -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Abandono de Carrinho
                </h3>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">
                        {{ metrics.cartAbandonment }}%
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Taxa de abandono</p>
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div
                                class="bg-red-600 h-2 rounded-full"
                                :style="{
                                    width: metrics.cartAbandonment + '%',
                                }"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Tempo Médio na Página
                </h3>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">
                        {{ metrics.avgSessionTime }}
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Minutos por sessão</p>
                    <p class="text-sm text-green-600 mt-2">
                        +15% vs período anterior
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Taxa de Rejeição
                </h3>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">
                        {{ metrics.bounceRate }}%
                    </div>
                    <p class="text-sm text-gray-600 mt-1">
                        Visitantes que saem imediatamente
                    </p>
                    <p class="text-sm text-red-600 mt-2">
                        -8% vs período anterior
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabela de Pedidos Recentes -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    Pedidos Recentes
                </h3>
            </div>
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
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="order in metrics.recentOrders"
                            :key="order.id"
                        >
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                            >
                                #{{ order.id }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                            >
                                {{ order.customer }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                            >
                                R$ {{ formatCurrency(order.total) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'px-2 py-1 rounded text-xs',
                                        getStatusClass(order.status),
                                    ]"
                                >
                                    {{ order.status }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ formatDate(order.date) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useNotification } from "@/composables/useNotification";
import { adminService } from "@/services/adminService";
import { apiClient } from "@/services/api";

const { showNotification } = useNotification();

const selectedPeriod = ref("month");
const loading = ref(false);

const metrics = ref({
    totalSales: 15420.5,
    totalOrders: 127,
    totalVisitors: 2847,
    conversionRate: 4.5,
    cartAbandonment: 68,
    avgSessionTime: 3.2,
    bounceRate: 42,
    topProducts: [
        {
            id: 1,
            name: "Chinelo Praia Azul",
            image: "https://via.placeholder.com/40x40?text=P1",
            sales: 45,
            revenue: 2250.0,
        },
        {
            id: 2,
            name: "Chinelo Casual Preto",
            image: "https://via.placeholder.com/40x40?text=P2",
            sales: 38,
            revenue: 1900.0,
        },
        {
            id: 3,
            name: "Chinelo Esportivo Branco",
            image: "https://via.placeholder.com/40x40?text=P3",
            sales: 32,
            revenue: 2240.0,
        },
        {
            id: 4,
            name: "Chinelo Infantil Colorido",
            image: "https://via.placeholder.com/40x40?text=P4",
            sales: 28,
            revenue: 1120.0,
        },
        {
            id: 5,
            name: "Chinelo Slide Conforto",
            image: "https://via.placeholder.com/40x40?text=P5",
            sales: 25,
            revenue: 1500.0,
        },
    ],
    recentOrders: [
        {
            id: "00123",
            customer: "João Silva",
            total: 89.9,
            status: "Entregue",
            date: "2024-01-15",
        },
        {
            id: "00122",
            customer: "Maria Santos",
            total: 145.5,
            status: "Em trânsito",
            date: "2024-01-14",
        },
        {
            id: "00121",
            customer: "Pedro Oliveira",
            total: 67.8,
            status: "Processando",
            date: "2024-01-14",
        },
        {
            id: "00120",
            customer: "Ana Costa",
            total: 234.0,
            status: "Entregue",
            date: "2024-01-13",
        },
        {
            id: "00119",
            customer: "Carlos Lima",
            total: 98.7,
            status: "Cancelado",
            date: "2024-01-13",
        },
    ],
});

const loadMetrics = async () => {
    loading.value = true;
    try {
        // Calcular datas baseado no período selecionado
        const now = new Date();
        let startDate, endDate;

        switch (selectedPeriod.value) {
            case "today":
                startDate = new Date(
                    now.getFullYear(),
                    now.getMonth(),
                    now.getDate(),
                );
                endDate = now;
                break;
            case "week":
                startDate = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                endDate = now;
                break;
            case "month":
                startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                endDate = now;
                break;
            case "year":
                startDate = new Date(now.getFullYear(), 0, 1);
                endDate = now;
                break;
            default:
                startDate = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
                endDate = now;
        }

        const params = {
            start_date: startDate.toISOString().split("T")[0],
            end_date: endDate.toISOString().split("T")[0],
        };

        const response = await adminService.getMetrics(params);
        // adminService.getMetrics retorna response.data — mas alguns serviços
        // podem já devolver o objeto diretamente. Normalizar para `data`.
        const data = response?.data ?? response ?? {};
        metrics.value = data;

        console.log(
            `Métricas carregadas para o período: ${selectedPeriod.value}`,
        );
    } catch (error) {
        console.error("Erro ao carregar métricas:", error);
        showNotification("Erro ao carregar métricas", "error");
    } finally {
        loading.value = false;
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
    return new Date(dateString).toLocaleDateString("pt-BR");
};

const getStatusClass = (status) => {
    const classes = {
        Entregue: "bg-green-100 text-green-800",
        "Em trânsito": "bg-blue-100 text-blue-800",
        Processando: "bg-yellow-100 text-yellow-800",
        Cancelado: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

const exportReport = async () => {
    try {
        const params = {
            start_date: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000)
                .toISOString()
                .split("T")[0],
            end_date: new Date().toISOString().split("T")[0],
            type: "general",
        };

        // Fazer a requisição diretamente para obter blob (download CSV)
        const res = await apiClient.get("/admin/metrics/export", {
            params,
            responseType: "blob",
        });

        // Criar link para download
        const url = window.URL.createObjectURL(new Blob([res.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute(
            "download",
            `relatorio-metricas-${selectedPeriod.value}-${new Date().toISOString().split("T")[0]}.csv`,
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

onMounted(() => {
    loadMetrics();
});
</script>

<style scoped>
.space-y-6 > * + * {
    margin-top: 1.5rem;
}
</style>
