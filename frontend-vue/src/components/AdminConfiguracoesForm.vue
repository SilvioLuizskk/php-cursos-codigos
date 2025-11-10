<template>
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                Configurações do Sistema
            </h2>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Configurações Gerais -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">
                        Configurações Gerais
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Nome da Loja
                            </label>
                            <input
                                v-model="form.store_name"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Nome da sua loja"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Email de Contato
                            </label>
                            <input
                                v-model="form.contact_email"
                                type="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="contato@exemplo.com"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Telefone
                            </label>
                            <input
                                v-model="form.phone"
                                type="tel"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="(11) 99999-9999"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Moeda
                            </label>
                            <select
                                v-model="form.currency"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="BRL">Real (R$)</option>
                                <option value="USD">Dólar ($)</option>
                                <option value="EUR">Euro (€)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Configurações de Frete -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">
                        Configurações de Frete
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Frete Grátis Acima de (R$)
                            </label>
                            <input
                                v-model.number="form.free_shipping_threshold"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="100.00"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Taxa de Frete Padrão (R$)
                            </label>
                            <input
                                v-model.number="form.default_shipping_rate"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="15.00"
                            />
                        </div>
                    </div>
                </div>

                <!-- Configurações de Pagamento -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">
                        Configurações de Pagamento
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input
                                v-model="form.enable_credit_card"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label class="ml-2 block text-sm text-gray-700">
                                Habilitar pagamento com cartão de crédito
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input
                                v-model="form.enable_pix"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label class="ml-2 block text-sm text-gray-700">
                                Habilitar pagamento com PIX
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input
                                v-model="form.enable_boleto"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label class="ml-2 block text-sm text-gray-700">
                                Habilitar pagamento com boleto
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Configurações de Email -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">
                        Configurações de Email
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                SMTP Host
                            </label>
                            <input
                                v-model="form.smtp_host"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="smtp.gmail.com"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                SMTP Porta
                            </label>
                            <input
                                v-model.number="form.smtp_port"
                                type="number"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="587"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                SMTP Usuário
                            </label>
                            <input
                                v-model="form.smtp_username"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="seu-email@gmail.com"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                SMTP Senha
                            </label>
                            <input
                                v-model="form.smtp_password"
                                type="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="••••••••"
                            />
                        </div>
                    </div>
                </div>

                <!-- Configurações de Segurança -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">
                        Configurações de Segurança
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tentativas de Login Máximas
                            </label>
                            <input
                                v-model.number="form.max_login_attempts"
                                type="number"
                                min="1"
                                max="10"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="5"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tempo de Bloqueio (minutos)
                            </label>
                            <input
                                v-model.number="form.lockout_duration"
                                type="number"
                                min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="15"
                            />
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-4 pt-6">
                    <button
                        type="button"
                        @click="resetForm"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="loading">Salvando...</span>
                        <span v-else>Salvar Configurações</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useNotification } from "@/composables/useNotification";
import { adminService } from "@/services/adminService";

const { showNotification } = useNotification();

const loading = ref(false);

const form = ref({
    store_name: "",
    contact_email: "",
    phone: "",
    currency: "BRL",
    free_shipping_threshold: 0,
    default_shipping_rate: 0,
    enable_credit_card: true,
    enable_pix: true,
    enable_boleto: true,
    smtp_host: "",
    smtp_port: 587,
    smtp_username: "",
    smtp_password: "",
    max_login_attempts: 5,
    lockout_duration: 15,
});

const loadSettings = async () => {
    try {
        loading.value = true;
        const response = await adminService.getSettings();
        Object.assign(form.value, response.data);
    } catch (error) {
        console.error("Erro ao carregar configurações:", error);
        showNotification("Erro ao carregar configurações", "error");
    } finally {
        loading.value = false;
    }
};

const handleSubmit = async () => {
    try {
        loading.value = true;
        await adminService.updateSettings(form.value);
        showNotification("Configurações salvas com sucesso!", "success");
    } catch (error) {
        console.error("Erro ao salvar configurações:", error);
        showNotification("Erro ao salvar configurações", "error");
    } finally {
        loading.value = false;
    }
};

const resetForm = () => {
    loadSettings();
};

onMounted(() => {
    loadSettings();
});
</script>
