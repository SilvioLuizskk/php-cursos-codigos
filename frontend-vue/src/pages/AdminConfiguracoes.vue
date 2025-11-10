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
                            v-model="settings.store_name"
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
                            v-model="settings.contact_email"
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
                            v-model.number="settings.free_shipping_threshold"
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
                            v-model.number="settings.default_delivery_days"
                            type="number"
                            min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                </div>

                <div class="flex items-center">
                    <input
                        v-model="settings.enable_pickup"
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
                            v-model="settings.enable_pix"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label class="ml-2 block text-sm text-gray-900"
                            >PIX</label
                        >
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="settings.enable_credit_card"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label class="ml-2 block text-sm text-gray-900"
                            >Cartão de Crédito</label
                        >
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="settings.enable_boleto"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label class="ml-2 block text-sm text-gray-900"
                            >Boleto</label
                        >
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="settings.payment_methods.bank_transfer"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label class="ml-2 block text-sm text-gray-900"
                            >Transferência Bancária</label
                        >
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="settings.payment_methods.cash"
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
                            v-model="settings.theme.primary_color"
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
                            v-model="settings.theme.secondary_color"
                            type="color"
                            class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer"
                        />
                    </div>
                </div>

                <div>
                    <ImageUpload
                        v-model="settings.theme.logo_url"
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
                        v-model="settings.seo.meta_description"
                        rows="3"
                        maxlength="160"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ settings.seo.meta_description.length }}/160
                        caracteres
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
import { adminService } from "@/services/adminService";
import ImageUpload from "@/components/ImageUpload.vue";

const { showNotification } = useNotification();

const saving = ref(false);

const settings = ref({
    store_name: "Chinelos Karibe",
    contact_email: "contato@chineloskaribe.com",
    phone: "(11) 99999-9999",
    currency: "BRL",
    free_shipping_threshold: 150.0,
    default_shipping_rate: 15.0,
    enable_credit_card: true,
    enable_pix: true,
    enable_boleto: false,
    smtp_host: "",
    smtp_port: 587,
    smtp_username: "",
    smtp_password: "",
    max_login_attempts: 5,
    lockout_duration: 15,
    whatsapp: "(11) 99999-9999",
    address: "Rua dos Chinelos, 123 - São Paulo, SP",
    default_delivery_days: 7,
    enable_pickup: true,
    payment_methods: {
        pix: true,
        credit_card: true,
        debit_card: true,
        bank_transfer: false,
        cash: true,
    },
    theme: {
        primary_color: "#3B82F6",
        secondary_color: "#10B981",
        logo_url: "https://via.placeholder.com/200x80?text=Chinelos+Karibe",
    },
    seo: {
        meta_description:
            "Chinelos Karibe - Conforto e estilo para seus pés. Grandes ofertas em chinelos de qualidade com entrega rápida.",
    },
    social: {
        facebook: "https://facebook.com/chineloskaribe",
        instagram: "https://instagram.com/chineloskaribe",
    },
});

const loadSettings = async () => {
    try {
        const response = await adminService.getSettings();
        const data = response.data || {};

        // Mapear campos do backend para os campos da interface
        settings.value = {
            ...settings.value,
            ...data,
            // Mapear campos específicos se necessário
            store_name: data.store_name || settings.value.store_name,
            contact_email: data.contact_email || settings.value.contact_email,
            phone: data.phone || settings.value.phone,
            currency: data.currency || settings.value.currency,
            free_shipping_threshold:
                data.free_shipping_threshold ||
                settings.value.free_shipping_threshold,
            default_shipping_rate:
                data.default_shipping_rate ||
                settings.value.default_shipping_rate,
            enable_credit_card:
                data.enable_credit_card ??
                settings.value.enable_credit_card,
            enable_pix: data.enable_pix ?? settings.value.enable_pix,
            enable_boleto:
                data.enable_boleto ?? settings.value.enable_boleto,
            // Mapear campos aninhados
            theme: {
                ...settings.value.theme,
                logo_url: data.logo_url || settings.value.theme.logo_url,
                primary_color:
                    data.primary_color ||
                    settings.value.theme.primary_color,
                secondary_color:
                    data.secondary_color ||
                    settings.value.theme.secondary_color,
            },
            seo: {
                ...settings.value.seo,
                meta_description:
                    data.meta_description ||
                    settings.value.seo.meta_description,
            },
            social: {
                ...settings.value.social,
                facebook: data.facebook_url || settings.value.social.facebook,
                instagram: data.instagram_url || settings.value.social.instagram,
            },
            whatsapp: data.whatsapp || settings.value.whatsapp,
            address: data.address || settings.value.address,
            default_delivery_days:
                data.default_delivery_days ||
                settings.value.default_delivery_days,
            enable_pickup:
                data.enable_pickup ?? settings.value.enable_pickup,
            payment_methods: {
                ...settings.value.payment_methods,
                ...(data.payment_methods || {}),
            },
        };
        console.log("Configurações carregadas:", settings.value);
    } catch (error) {
        console.error("Erro ao carregar configurações:", error);
        showNotification("Erro ao carregar configurações", "error");
    }
};

const saveSettings = async () => {
    saving.value = true;
    try {
        // Preparar dados para enviar ao backend
        const dataToSend = {
            store_name: settings.value.store_name,
            contact_email: settings.value.contact_email,
            phone: settings.value.phone,
            currency: settings.value.currency,
            free_shipping_threshold: settings.value.free_shipping_threshold,
            default_shipping_rate: settings.value.default_shipping_rate,
            enable_credit_card: settings.value.enable_credit_card,
            enable_pix: settings.value.enable_pix,
            enable_boleto: settings.value.enable_boleto,
            smtp_host: settings.value.smtp_host,
            smtp_port: settings.value.smtp_port,
            smtp_username: settings.value.smtp_username,
            smtp_password: settings.value.smtp_password,
            max_login_attempts: settings.value.max_login_attempts,
            lockout_duration: settings.value.lockout_duration,
            // Campos de imagem e tema
            logo_url: settings.value.theme?.logoUrl || "",
            primary_color: settings.value.theme?.primaryColor || "#3B82F6",
            secondary_color: settings.value.theme?.secondaryColor || "#10B981",
            // Campos de SEO
            meta_description: settings.value.seo?.metaDescription || "",
            // Campos sociais
            facebook_url: settings.value.social?.facebook || "",
            instagram_url: settings.value.social?.instagram || "",
            // Outros campos
            whatsapp: settings.value.whatsapp || "",
            address: settings.value.address || "",
            default_delivery_days: settings.value.default_delivery_days || 7,
            enable_pickup: settings.value.enable_pickup || false,
            payment_methods: settings.value.payment_methods || {
                pix: true,
                credit_card: true,
                debit_card: true,
                bank_transfer: false,
                cash: true,
            },
        };

        await adminService.updateSettings(dataToSend);
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
