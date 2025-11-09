<template>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">
                Administração A/B Testing - EstampariaPro
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Estatísticas dos Testes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">
                        Estatísticas dos Testes
                    </h2>

                    <div class="space-y-4">
                        <div class="mb-4">
                            <input
                                v-model="testName"
                                type="text"
                                placeholder="Nome do teste (ex: homepage_button)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                            <button
                                @click="loadWeights"
                                :disabled="loading || !testName"
                                class="w-full mt-2 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white px-4 py-2 rounded-lg transition-colors"
                            >
                                {{ loading ? 'Carregando...' : 'Carregar Pesos' }}
                            </button>
                        </div>

                        <div v-if="weights" class="space-y-4">
                            <div class="border rounded-lg p-4">
                                <h3 class="font-medium text-gray-900 mb-3">{{ testName }}</h3>
                                <div class="space-y-2">
                                    <div v-for="(weight, variant) in weights" :key="variant" class="flex items-center space-x-2">
                                        <label class="flex-1 text-sm">{{ variant }}:</label>
                                        <input
                                            v-model.number="weights[variant]"
                                            type="number"
                                            min="0"
                                            max="100"
                                            class="w-20 px-2 py-1 border border-gray-300 rounded"
                                        />
                                        <span class="text-sm text-gray-600">%</span>
                                    </div>
                                </div>
                                <button
                                    @click="saveWeights"
                                    class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm"
                                >
                                    Salvar Pesos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard de A/B Testing -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">
                        Dashboard A/B Testing
                    </h2>

                    <div v-if="dashboardLoading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-600">Carregando dados...</p>
                    </div>

                    <div v-else-if="dashboardError" class="text-center py-8">
                        <p class="text-red-600">{{ dashboardError }}</p>
                        <button
                            @click="fetchDashboardData"
                            class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        >
                            Tentar Novamente
                        </button>
                    </div>

                    <div v-else-if="activeTests.length > 0" class="space-y-4">
                        <div v-for="test in activeTests" :key="test.id" class="border rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">{{ test.name }}</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Status:</span>
                                    <span class="ml-1 font-medium">{{ test.status }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Conversões:</span>
                                    <span class="ml-1 font-medium">{{ test.conversions }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-8 text-gray-500">
                        Nenhum teste ativo encontrado
                    </div>
                </div>
            </div>

            <!-- Estatísticas de Conversão -->
            <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">
                    Estatísticas de Conversão
                </h2>

                <div v-if="conversionRates.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="rate in conversionRates" :key="rate.test" class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ rate.rate }}%</div>
                        <div class="text-sm text-gray-600">{{ rate.test }}</div>
                    </div>
                </div>

                <div v-else class="text-center py-8 text-gray-500">
                    Nenhuma estatística de conversão disponível
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { abTestService } from '@/services/monitoringService';
import { useNotification } from '@/composables/useNotification';
import { useABDashboard } from '@/composables/useABDashboard';

const { showNotification } = useNotification();
const {
    activeTests,
    conversionRates,
    loading: dashboardLoading,
    error: dashboardError,
    fetchDashboardData
} = useABDashboard();

const testName = ref("");
const weights = ref(null);
const loading = ref(false);

const loadWeights = async () => {
    if (!testName.value) return;

    loading.value = true;
    try {
        const response = await abTestService.getWeights();
        if (response[testName.value]) {
            weights.value = response[testName.value];
        } else {
            weights.value = { A: 50, B: 50 };
        }
        showNotification('Pesos carregados!', 'success');
    } catch (err) {
        showNotification('Erro ao carregar pesos', 'error');
    } finally {
        loading.value = false;
    }
};

const saveWeights = async () => {
    if (!weights.value || !testName.value) return;

    try {
        await abTestService.setWeights(testName.value, weights.value);
        showNotification('Pesos salvos com sucesso!', 'success');
    } catch (err) {
        showNotification('Erro ao salvar pesos', 'error');
    }
};

onMounted(async () => {
    try {
        await fetchDashboardData();
    } catch (e) {
        console.warn("Dashboard data fetch failed", e);
    }
});
</script>

<style scoped>
.container {
    min-height: 100vh;
    background-color: #f9fafb;
}
</style>
