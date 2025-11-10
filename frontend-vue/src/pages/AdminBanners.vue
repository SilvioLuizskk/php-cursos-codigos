<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">
                Gerenciar Banners & Promoções
            </h1>
            <button
                @click="
                    showModal = true;
                    editingBanner = null;
                    form = {
                        title: '',
                        image: '',
                        link: '',
                        position: 'hero',
                        active: true,
                        order: banners.length + 1,
                    };
                "
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
            >
                + Novo Banner
            </button>
        </div>

        <!-- Lista de Banners por Posição -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Hero Banners -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b bg-blue-50">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Hero (Topo da Página)
                    </h2>
                </div>
                <div class="p-4 space-y-3">
                    <div
                        v-for="banner in heroBanners"
                        :key="banner.id"
                        class="border rounded p-3"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img
                                    :src="banner.image"
                                    :alt="banner.title"
                                    class="w-12 h-12 object-cover rounded"
                                />
                                <div>
                                    <h4 class="font-medium">
                                        {{ banner.title }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ banner.link }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span
                                    :class="[
                                        'px-2 py-1 rounded text-xs',
                                        banner.active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{ banner.active ? "Ativo" : "Inativo" }}
                                </span>
                                <button
                                    @click="editBanner(banner)"
                                    class="text-blue-600 hover:text-blue-800 text-sm"
                                >
                                    Editar
                                </button>
                                <button
                                    @click="deleteBanner(banner.id)"
                                    class="text-red-600 hover:text-red-800 text-sm"
                                >
                                    Excluir
                                </button>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="heroBanners.length === 0"
                        class="text-center text-gray-500 py-4"
                    >
                        Nenhum banner hero cadastrado
                    </div>
                </div>
            </div>

            <!-- Sidebar Banners -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b bg-green-50">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Sidebar (Lateral)
                    </h2>
                </div>
                <div class="p-4 space-y-3">
                    <div
                        v-for="banner in sidebarBanners"
                        :key="banner.id"
                        class="border rounded p-3"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img
                                    :src="banner.image"
                                    :alt="banner.title"
                                    class="w-12 h-12 object-cover rounded"
                                />
                                <div>
                                    <h4 class="font-medium">
                                        {{ banner.title }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        {{ banner.link }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span
                                    :class="[
                                        'px-2 py-1 rounded text-xs',
                                        banner.active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{ banner.active ? "Ativo" : "Inativo" }}
                                </span>
                                <button
                                    @click="editBanner(banner)"
                                    class="text-blue-600 hover:text-blue-800 text-sm"
                                >
                                    Editar
                                </button>
                                <button
                                    @click="deleteBanner(banner.id)"
                                    class="text-red-600 hover:text-red-800 text-sm"
                                >
                                    Excluir
                                </button>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="sidebarBanners.length === 0"
                        class="text-center text-gray-500 py-4"
                    >
                        Nenhum banner lateral cadastrado
                    </div>
                </div>
            </div>
        </div>

        <!-- Carrosséis -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b bg-purple-50">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Carrosséis
                    </h2>
                    <button
                        @click="
                            showModal = true;
                            editingBanner = null;
                            form = {
                                title: '',
                                image: '',
                                link: '',
                                position: 'carousel',
                                active: true,
                                order: carouselBanners.length + 1,
                            };
                        "
                        class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700"
                    >
                        + Novo Slide
                    </button>
                </div>
            </div>
            <div class="p-4">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <div
                        v-for="banner in carouselBanners"
                        :key="banner.id"
                        class="border rounded p-3"
                    >
                        <img
                            :src="banner.image"
                            :alt="banner.title"
                            class="w-full h-24 object-cover rounded mb-2"
                        />
                        <h4 class="font-medium text-sm">{{ banner.title }}</h4>
                        <p class="text-xs text-gray-600 mb-2">
                            {{ banner.link }}
                        </p>
                        <div class="flex justify-between items-center">
                            <span
                                :class="[
                                    'px-2 py-1 rounded text-xs',
                                    banner.active
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800',
                                ]"
                            >
                                {{ banner.active ? "Ativo" : "Inativo" }}
                            </span>
                            <div class="flex space-x-1">
                                <button
                                    @click="editBanner(banner)"
                                    class="text-blue-600 hover:text-blue-800 text-xs"
                                >
                                    Editar
                                </button>
                                <button
                                    @click="deleteBanner(banner.id)"
                                    class="text-red-600 hover:text-red-800 text-xs"
                                >
                                    Excluir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-if="carouselBanners.length === 0"
                    class="text-center text-gray-500 py-8"
                >
                    Nenhum slide de carrossel cadastrado
                </div>
            </div>
        </div>

        <!-- Modal de Criação/Edição -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold mb-4">
                    {{ editingBanner ? "Editar Banner" : "Novo Banner" }}
                </h3>

                <form @submit.prevent="saveBanner" class="space-y-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Título</label
                        >
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <div>
                        <ImageUpload
                            v-model="form.image"
                            label="Imagem do Banner"
                            preview-alt="Preview do banner"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Link (opcional)</label
                        >
                        <input
                            v-model="form.link"
                            type="url"
                            placeholder="https://exemplo.com/pagina"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Posição</label
                        >
                        <select
                            v-model="form.position"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="hero">Hero (Topo)</option>
                            <option value="sidebar">Sidebar (Lateral)</option>
                            <option value="carousel">Carrossel</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <input
                            v-model="form.active"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label class="ml-2 block text-sm text-gray-900"
                            >Ativo</label
                        >
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Ordem</label
                        >
                        <input
                            v-model.number="form.order"
                            type="number"
                            min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="saving"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ saving ? "Salvando..." : "Salvar" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useNotification } from "@/composables/useNotification";
import { adminService } from "@/services/adminService";
import ImageUpload from "@/components/ImageUpload.vue";

const { showNotification } = useNotification();

const banners = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const editingBanner = ref(null);

const form = ref({
    title: "",
    image: "",
    link: "",
    position: "hero",
    active: true,
    order: 1,
});

// Computed properties para filtrar banners por posição
const heroBanners = computed(() =>
    banners.value.filter((b) => b.position === "hero"),
);
const sidebarBanners = computed(() =>
    banners.value.filter((b) => b.position === "sidebar"),
);
const carouselBanners = computed(() =>
    banners.value.filter((b) => b.position === "carousel"),
);

// Carregar banners da API
const fetchBanners = async () => {
    loading.value = true;
    try {
        console.log("Carregando banners...");
        const response = await adminService.getBanners();
        banners.value = response.data || [];
        console.log("Banners carregados:", banners.value);
    } catch (error) {
        console.error("Erro ao carregar banners:", error);
        showNotification("Erro ao carregar banners", "error");
        banners.value = [];
    } finally {
        loading.value = false;
    }
};

const saveBanner = async () => {
    saving.value = true;
    try {
        if (editingBanner.value) {
            // Atualizar banner existente
            const index = banners.value.findIndex(
                (b) => b.id === editingBanner.value.id,
            );
            if (index !== -1) {
                banners.value[index] = {
                    ...editingBanner.value,
                    ...form.value,
                };
            }
            showNotification("Banner atualizado com sucesso!", "success");
        } else {
            // Criar novo banner
            const newBanner = {
                id: Date.now(), // Simulação de ID
                ...form.value,
            };
            banners.value.push(newBanner);
            showNotification("Banner criado com sucesso!", "success");
        }

        showModal.value = false;
        form.value = {
            title: "",
            image: "",
            link: "",
            position: "hero",
            active: true,
            order: banners.value.length + 1,
        };
    } catch (error) {
        console.error("Erro ao salvar banner:", error);
        showNotification("Erro ao salvar banner", "error");
    } finally {
        saving.value = false;
    }
};

const editBanner = (banner) => {
    editingBanner.value = banner;
    form.value = { ...banner };
    showModal.value = true;
};

const deleteBanner = async (id) => {
    if (!confirm("Tem certeza que deseja excluir este banner?")) return;

    try {
        // Simulação - substitua pela chamada real da API
        banners.value = banners.value.filter((b) => b.id !== id);
        showNotification("Banner excluído com sucesso!", "success");
    } catch (error) {
        console.error("Erro ao excluir banner:", error);
        showNotification("Erro ao excluir banner", "error");
    }
};

onMounted(() => {
    fetchBanners();
});
</script>

<style scoped>
.space-y-6 > * + * {
    margin-top: 1.5rem;
}
</style>
