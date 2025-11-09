<template>
    <div class="health-check-page">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl font-bold text-gray-900 mb-2 text-center">
                    <i class="fas fa-heartbeat text-blue-600 mr-3"></i>
                    Health Check
                </h1>
                <p class="text-lg text-gray-600 mb-8 text-center">
                    Monitoramento do status do sistema EstampariaPro
                </p>

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between mb-8"
                    >
                        <h2
                            class="text-2xl font-semibold text-gray-800 mb-4 sm:mb-0"
                        >
                            Status do Sistema
                        </h2>
                        <button
                            @click="checkHealth"
                            :disabled="loading"
                            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 disabled:from-blue-400 disabled:to-blue-400 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl disabled:transform-none"
                        >
                            <i
                                v-if="loading"
                                class="fas fa-spinner fa-spin mr-2"
                            ></i>
                            <i v-else class="fas fa-sync-alt mr-2"></i>
                            {{
                                loading ? "Verificando..." : "Verificar Status"
                            }}
                        </button>
                    </div>

                    <Transition name="fade" mode="out-in">
                        <div v-if="healthData" class="space-y-6">
                            <!-- Status Principal -->
                            <div class="text-center mb-8">
                                <div
                                    class="inline-flex items-center justify-center w-24 h-24 rounded-full mb-4"
                                    :class="
                                        healthData.status === 'ok'
                                            ? 'bg-green-100'
                                            : 'bg-red-100'
                                    "
                                >
                                    <i
                                        :class="
                                            healthData.status === 'ok'
                                                ? 'fas fa-check-circle text-4xl text-green-600'
                                                : 'fas fa-times-circle text-4xl text-red-600'
                                        "
                                        class="animate-pulse"
                                    ></i>
                                </div>
                                <h3
                                    class="text-2xl font-bold mb-2"
                                    :class="
                                        healthData.status === 'ok'
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    Sistema
                                    {{
                                        healthData.status === "ok"
                                            ? "Online"
                                            : "Offline"
                                    }}
                                </h3>
                                <p class="text-gray-600">
                                    Última verificação:
                                    {{ formatDate(healthData.timestamp) }}
                                </p>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                            >
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                                >
                                    <!-- Database -->
                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200 hover:shadow-lg transition-all duration-300 transform hover:scale-105"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-4"
                                        >
                                            <div class="flex items-center">
                                                <i
                                                    class="fas fa-database text-2xl text-blue-600 mr-3"
                                                ></i>
                                                <h3
                                                    class="text-lg font-semibold text-gray-800"
                                                >
                                                    Database
                                                </h3>
                                            </div>
                                            <i
                                                :class="
                                                    healthData.database
                                                        .status === 'ok'
                                                        ? 'fas fa-check-circle text-green-500'
                                                        : 'fas fa-times-circle text-red-500'
                                                "
                                                class="text-xl"
                                            ></i>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">
                                            Status:
                                            {{
                                                healthData.database.status ===
                                                "ok"
                                                    ? "Conectado"
                                                    : "Erro"
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Tempo de resposta:
                                            {{
                                                healthData.database
                                                    .response_time
                                            }}ms
                                        </p>
                                    </div>

                                    <!-- API -->
                                    <div
                                        class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200 hover:shadow-lg transition-all duration-300 transform hover:scale-105"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-4"
                                        >
                                            <div class="flex items-center">
                                                <i
                                                    class="fas fa-server text-2xl text-green-600 mr-3"
                                                ></i>
                                                <h3
                                                    class="text-lg font-semibold text-gray-800"
                                                >
                                                    API
                                                </h3>
                                            </div>
                                            <i
                                                :class="
                                                    healthData.api.status ===
                                                    'ok'
                                                        ? 'fas fa-check-circle text-green-500'
                                                        : 'fas fa-times-circle text-red-500'
                                                "
                                                class="text-xl"
                                            ></i>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">
                                            Status:
                                            {{
                                                healthData.api.status === "ok"
                                                    ? "Online"
                                                    : "Offline"
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Tempo de resposta:
                                            {{ healthData.api.response_time }}ms
                                        </p>
                                    </div>

                                    <!-- Cache -->
                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200 hover:shadow-lg transition-all duration-300 transform hover:scale-105"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-4"
                                        >
                                            <div class="flex items-center">
                                                <i
                                                    class="fas fa-memory text-2xl text-purple-600 mr-3"
                                                ></i>
                                                <h3
                                                    class="text-lg font-semibold text-gray-800"
                                                >
                                                    Cache
                                                </h3>
                                            </div>
                                            <i
                                                :class="
                                                    healthData.cache.status ===
                                                    'ok'
                                                        ? 'fas fa-check-circle text-green-500'
                                                        : 'fas fa-times-circle text-red-500'
                                                "
                                                class="text-xl"
                                            ></i>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">
                                            Status:
                                            {{
                                                healthData.cache.status === "ok"
                                                    ? "Funcionando"
                                                    : "Erro"
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Hit rate:
                                            {{ healthData.cache.hit_rate }}%
                                        </p>
                                    </div>

                                    <!-- Queue -->
                                    <div
                                        class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border border-yellow-200 hover:shadow-lg transition-all duration-300 transform hover:scale-105"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-4"
                                        >
                                            <div class="flex items-center">
                                                <i
                                                    class="fas fa-tasks text-2xl text-yellow-600 mr-3"
                                                ></i>
                                                <h3
                                                    class="text-lg font-semibold text-gray-800"
                                                >
                                                    Queue
                                                </h3>
                                            </div>
                                            <i
                                                :class="
                                                    healthData.queue.status ===
                                                    'ok'
                                                        ? 'fas fa-check-circle text-green-500'
                                                        : 'fas fa-times-circle text-red-500'
                                                "
                                                class="text-xl"
                                            ></i>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">
                                            Status:
                                            {{
                                                healthData.queue.status === "ok"
                                                    ? "Processando"
                                                    : "Parado"
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Jobs pendentes:
                                            {{ healthData.queue.pending_jobs }}
                                        </p>
                                    </div>

                                    <!-- Storage -->
                                    <div
                                        class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border border-indigo-200 hover:shadow-lg transition-all duration-300 transform hover:scale-105"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-4"
                                        >
                                            <div class="flex items-center">
                                                <i
                                                    class="fas fa-hdd text-2xl text-indigo-600 mr-3"
                                                ></i>
                                                <h3
                                                    class="text-lg font-semibold text-gray-800"
                                                >
                                                    Storage
                                                </h3>
                                            </div>
                                            <i
                                                :class="
                                                    healthData.storage
                                                        .status === 'ok'
                                                        ? 'fas fa-check-circle text-green-500'
                                                        : 'fas fa-times-circle text-red-500'
                                                "
                                                class="text-xl"
                                            ></i>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">
                                            Status:
                                            {{
                                                healthData.storage.status ===
                                                "ok"
                                                    ? "Disponível"
                                                    : "Erro"
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Espaço usado:
                                            {{ healthData.storage.used_space }}%
                                        </p>
                                    </div>

                                    <!-- Email -->
                                    <div
                                        class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl p-6 border border-pink-200 hover:shadow-lg transition-all duration-300 transform hover:scale-105"
                                    >
                                        <div
                                            class="flex items-center justify-between mb-4"
                                        >
                                            <div class="flex items-center">
                                                <i
                                                    class="fas fa-envelope text-2xl text-pink-600 mr-3"
                                                ></i>
                                                <h3
                                                    class="text-lg font-semibold text-gray-800"
                                                >
                                                    Email
                                                </h3>
                                            </div>
                                            <i
                                                :class="
                                                    healthData.email.status ===
                                                    'ok'
                                                        ? 'fas fa-check-circle text-green-500'
                                                        : 'fas fa-times-circle text-red-500'
                                                "
                                                class="text-xl"
                                            ></i>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">
                                            Status:
                                            {{
                                                healthData.email.status === "ok"
                                                    ? "Funcionando"
                                                    : "Erro"
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Último envio:
                                            {{
                                                formatDate(
                                                    healthData.email.last_sent,
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Métricas do Sistema -->
                                <div class="mt-8">
                                    <h3
                                        class="text-xl font-semibold text-gray-800 mb-4"
                                    >
                                        <i
                                            class="fas fa-chart-line text-blue-600 mr-2"
                                        ></i>
                                        Métricas do Sistema
                                    </h3>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                                    >
                                        <div
                                            class="bg-gray-50 rounded-lg p-4 text-center"
                                        >
                                            <div
                                                class="text-2xl font-bold text-blue-600"
                                            >
                                                {{
                                                    healthData.metrics
                                                        .cpu_usage
                                                }}%
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                CPU
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gray-50 rounded-lg p-4 text-center"
                                        >
                                            <div
                                                class="text-2xl font-bold text-green-600"
                                            >
                                                {{
                                                    healthData.metrics
                                                        .memory_usage
                                                }}%
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                Memória
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gray-50 rounded-lg p-4 text-center"
                                        >
                                            <div
                                                class="text-2xl font-bold text-purple-600"
                                            >
                                                {{
                                                    healthData.metrics
                                                        .disk_usage
                                                }}%
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                Disco
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gray-50 rounded-lg p-4 text-center"
                                        >
                                            <div
                                                class="text-2xl font-bold text-orange-600"
                                            >
                                                {{
                                                    healthData.metrics
                                                        .active_users
                                                }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                Usuários Ativos
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informações da Versão -->
                                <div
                                    class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6"
                                >
                                    <h3
                                        class="text-xl font-semibold text-gray-800 mb-4"
                                    >
                                        <i
                                            class="fas fa-code-branch text-gray-600 mr-2"
                                        ></i>
                                        Informações da Versão
                                    </h3>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-3 gap-4"
                                    >
                                        <div>
                                            <span class="text-sm text-gray-600"
                                                >Versão:</span
                                            >
                                            <div class="font-semibold">
                                                {{ healthData.version.app }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600"
                                                >Commit:</span
                                            >
                                            <div class="font-semibold text-xs">
                                                {{ healthData.version.commit }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-600"
                                                >Ambiente:</span
                                            >
                                            <div class="font-semibold">
                                                {{
                                                    healthData.version
                                                        .environment
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Transition>

                    <div v-if="!loading" class="text-center py-8">
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

const formatDate = (timestamp) => {
    if (!timestamp) return "N/A";
    return new Date(timestamp).toLocaleString("pt-BR");
};
</script>

<style scoped>
.health-check-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
