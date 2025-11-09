<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">
                Configurações da Loja
            </h1>
            <button
                @click="saveSettings"
                :disabled="saving"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50 transition"
            >
                {{ saving ? "Salvando..." : "Salvar Alterações" }}
            </button>
        </div>

        <!-- Configurações Gerais -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-blue-50">
                <h2 class="text-lg font-semibold text-gray-900">
                    Informações Gerais
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nome da Loja</label
                        >
                        <input
                            v-model="settings.storeName"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Email de Contato</label
                        >
                        <input
                            v-model="settings.contactEmail"
                            type="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Telefone</label
                        >
                        <input
                            v-model="settings.phone"
                            type="tel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >WhatsApp</label
                        >
                        <input
                            v-model="settings.whatsapp"
                            type="tel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Endereço</label
                    >
                    <textarea
                        v-model="settings.address"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ></textarea>
                </div>
            </div>
        </div>

        <!-- Configurações de Frete -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-green-50">
                <h2 class="text-lg font-semibold text-gray-900">
                    Frete e Entrega
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Valor mínimo para frete grátis</label
                        >
                        <input
                            v-model.number="settings.freeShippingThreshold"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            Deixe em branco para desabilitar
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Prazo padrão de entrega (dias)</label
                        >
                        <input
                            v-model.number="settings.defaultDeliveryDays"
                            type="number"
                            min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                </div>

                <div class="flex items-center">
                    <input
                        v-model="settings.enablePickup"
                        type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label class="ml-2 block text-sm text-gray-900"
                        >Permitir retirada na loja</label
                    >
                </div>
            </div>
        </div>

        <!-- Configurações de Pagamento -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-purple-50">
                <h2 class="text-lg font-semibold text-gray-900">Pagamentos</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input
                            v-model="settings.paymentMethods.pix"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label class="ml-2 block text-sm text-gray-900"
                            >PIX</label
                        >
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="settings.paymentMethods.creditCard"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label class="ml-2 block text-sm text-gray-900"
                            >Cartão de Crédito</label
                        >
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="settings.paymentMethods.debitCard"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label class="ml-2 block text-sm text-gray-900"
                            >Cartão de Débito</label
                        >
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="settings.paymentMethods.bankTransfer"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label class="ml-2 block text-sm text-gray-900"
                            >Transferência Bancária</label
                        >
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="settings.paymentMethods.cash"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label class="ml-2 block text-sm text-gray-900"
                            >Dinheiro na Entrega</label
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Configurações de Tema -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-yellow-50">
                <h2 class="text-lg font-semibold text-gray-900">Aparência</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Cor Primária</label
                        >
                        <input
                            v-model="settings.theme.primaryColor"
                            type="color"
                            class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Cor Secundária</label
                        >
                        <input
                            v-model="settings.theme.secondaryColor"
                            type="color"
                            class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer"
                        />
                    </div>
                </div>

                <div>
                    <ImageUpload
                        v-model="settings.theme.logoUrl"
                        label="Logo da Loja"
                        preview-alt="Preview do logo"
                    />
                </div>
            </div>
        </div>

        <!-- Configurações de SEO -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-indigo-50">
                <h2 class="text-lg font-semibold text-gray-900">
                    SEO e Redes Sociais
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Meta Description</label
                    >
                    <textarea
                        v-model="settings.seo.metaDescription"
                        rows="3"
                        maxlength="160"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ settings.seo.metaDescription.length }}/160 caracteres
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Facebook</label
                        >
                        <input
                            v-model="settings.social.facebook"
                            type="url"
                            placeholder="https://facebook.com/loja"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Instagram</label
                        >
                        <input
                            v-model="settings.social.instagram"
                            type="url"
                            placeholder="https://instagram.com/loja"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useNotification } from "@/composables/useNotification";
import ImageUpload from "@/components/ImageUpload.vue";

const { showNotification } = useNotification();

const saving = ref(false);

const settings = ref({
    storeName: "Chinelos Karibe",
    contactEmail: "contato@chineloskaribe.com",
    phone: "(11) 99999-9999",
    whatsapp: "(11) 99999-9999",
    address: "Rua dos Chinelos, 123 - São Paulo, SP",

    freeShippingThreshold: 150.0,
    defaultDeliveryDays: 7,
    enablePickup: true,

    paymentMethods: {
        pix: true,
        creditCard: true,
        debitCard: true,
        bankTransfer: false,
        cash: true,
    },

    theme: {
        primaryColor: "#3B82F6",
        secondaryColor: "#10B981",
        logoUrl: "https://via.placeholder.com/200x80?text=Chinelos+Karibe",
    },

    seo: {
        metaDescription:
            "Chinelos Karibe - Conforto e estilo para seus pés. Grandes ofertas em chinelos de qualidade com entrega rápida.",
    },

    social: {
        facebook: "https://facebook.com/chineloskaribe",
        instagram: "https://instagram.com/chineloskaribe",
    },
});

// Simulação de carregamento das configurações
const loadSettings = async () => {
    try {
        // Simulação - substitua pela chamada real da API
        // const response = await api.get('/settings')
        // settings.value = response.data
        console.log("Configurações carregadas:", settings.value);
    } catch (error) {
        console.error("Erro ao carregar configurações:", error);
        showNotification("Erro ao carregar configurações", "error");
    }
};

const saveSettings = async () => {
    saving.value = true;
    try {
        // Simulação - substitua pela chamada real da API
        // await api.put('/settings', settings.value)

        // Simular delay de salvamento
        await new Promise((resolve) => setTimeout(resolve, 1000));

        showNotification("Configurações salvas com sucesso!", "success");
    } catch (error) {
        console.error("Erro ao salvar configurações:", error);
        showNotification("Erro ao salvar configurações", "error");
    } finally {
        saving.value = false;
    }
};

onMounted(() => {
    loadSettings();
});
</script>

<style scoped>
.space-y-6 > * + * {
    margin-top: 1.5rem;
}
</style>
