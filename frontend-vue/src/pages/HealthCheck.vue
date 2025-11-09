<template>
    <div class="health-check-page">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">
                    Health Check - EstampariaPro
                </h1>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Status do Sistema
                        </h2>
                        <button
                            @click="checkHealth"
                            :disabled="loading"
                            class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg transition-colors"
                        >
                            {{
                                loading ? "Verificando..." : "Verificar Status"
                            }}
                        </button>
                    </div>

                    <div v-if="healthData" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-900 mb-2">
                                    Status
                                </h3>
                                <span
                                    :class="
                                        healthData.status === 'ok'
                                            ? 'text-green-600 bg-green-100'
                                            : 'text-red-600 bg-red-100'
                                    "
                                    class="px-2 py-1 rounded-full text-sm font-medium"
                                >
                                    {{
                                        healthData.status === "ok"
                                            ? "Online"
                                            : "Offline"
                                    }}
                                </span>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-900 mb-2">
                                    Versão
                                </h3>
                                <p class="text-gray-600">
                                    {{ healthData.version }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-900 mb-2">
                                    Mensagem
                                </h3>
                                <p class="text-gray-600">
                                    {{ healthData.message }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-900 mb-2">
                                    Timestamp
                                </h3>
                                <p class="text-gray-600">
                                    {{ formatTimestamp(healthData.timestamp) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-else-if="!loading" class="text-center py-8">
                        <p class="text-gray-500">
                            Clique em "Verificar Status" para obter informações
                            do sistema.
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
import { ref } from "vue";
import { monitoringService } from "@/services/monitoringService";
import { useNotification } from "@/composables/useNotification";

const { showNotification } = useNotification();

const healthData = ref(null);
const loading = ref(false);
const error = ref(null);

const checkHealth = async () => {
    loading.value = true;
    error.value = null;

    try {
        const response = await monitoringService.getHealth();
        healthData.value = response;
        showNotification("Status verificado com sucesso!", "success");
    } catch (err) {
        error.value =
            err.response?.data?.message ||
            "Erro ao verificar status do sistema";
        showNotification(error.value, "error");
    } finally {
        loading.value = false;
    }
};

const formatTimestamp = (timestamp) => {
    if (!timestamp) return "N/A";
    return new Date(timestamp).toLocaleString("pt-BR");
};
</script>

<style scoped>
.health-check-page {
    min-height: 100vh;
    background-color: #f9fafb;
}
</style>
