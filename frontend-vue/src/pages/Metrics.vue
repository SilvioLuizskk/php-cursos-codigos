<template>
    <div class="metrics-page">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">
                    Métricas do Sistema - EstampariaPro
                </h1>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Monitoramento de Performance
                        </h2>
                        <button
                            @click="fetchMetrics"
                            :disabled="loading"
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg transition-colors"
                        >
                            {{
                                loading ? "Carregando..." : "Atualizar Métricas"
                            }}
                        </button>
                    </div>

                    <div v-if="metrics" class="space-y-6">
                        <!-- Métricas de Sistema -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="font-medium text-blue-900 mb-2">
                                    CPU
                                </h3>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ metrics.cpu }}%
                                </p>
                            </div>

                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="font-medium text-green-900 mb-2">
                                    Memória
                                </h3>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ metrics.memory }}%
                                </p>
                            </div>

                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h3 class="font-medium text-purple-900 mb-2">
                                    Disco
                                </h3>
                                <p class="text-2xl font-bold text-purple-600">
                                    {{ metrics.disk }}%
                                </p>
                            </div>
                        </div>

                        <!-- Métricas de Aplicação -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Aplicação
                            </h3>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                            >
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ metrics.requests_total }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Total de Requisições
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p
                                        class="text-2xl font-bold text-green-600"
                                    >
                                        {{ metrics.requests_success }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Requisições OK
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-red-600">
                                        {{ metrics.requests_error }}
                                    </p>
                                    <p class="text-sm text-gray-600">Erros</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ metrics.avg_response_time }}ms
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Tempo Médio
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Métricas de Banco de Dados -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Banco de Dados
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900">
                                        {{ metrics.db_connections }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Conexões Ativas
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p
                                        class="text-2xl font-bold text-yellow-600"
                                    >
                                        {{ metrics.db_queries }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Queries/Minuto
                                    </p>
                                </div>
                                <div class="text-center">
                                    <p
                                        class="text-2xl font-bold text-orange-600"
                                    >
                                        {{ metrics.db_slow_queries }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Queries Lentas
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Última atualização -->
                        <div class="text-right text-sm text-gray-500">
                            Última atualização:
                            {{ formatTimestamp(metrics.timestamp) }}
                        </div>
                    </div>

                    <div v-else-if="!loading" class="text-center py-12">
                        <p class="text-gray-500">
                            Clique em "Atualizar Métricas" para carregar os
                            dados de monitoramento.
                        </p>
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
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { monitoringService } from "@/services/monitoringService";
import { useNotification } from "@/composables/useNotification";

const { showNotification } = useNotification();

const metrics = ref(null);
const loading = ref(false);
const error = ref(null);

const fetchMetrics = async () => {
    loading.value = true;
    error.value = null;

    try {
        const response = await monitoringService.getMetrics();
        metrics.value = response;
        showNotification("Métricas atualizadas com sucesso!", "success");
    } catch (err) {
        error.value =
            err.response?.data?.message || "Erro ao carregar métricas";
        showNotification(error.value, "error");
    } finally {
        loading.value = false;
    }
};

const formatTimestamp = (timestamp) => {
    if (!timestamp) return "N/A";
    return new Date(timestamp).toLocaleString("pt-BR");
};

// Carregar métricas automaticamente ao montar o componente
onMounted(() => {
    fetchMetrics();
});
</script>

<style scoped>
.metrics-page {
    min-height: 100vh;
    background-color: #f9fafb;
}
</style>
