<template>
    <div class="banner-form">
        <!-- Botão para criar novo banner -->
        <div v-if="!showForm" class="mb-6">
            <button
                @click="openCreateForm()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
            >
                <i class="fas fa-plus mr-2"></i>
                Novo Banner
            </button>
        </div>

        <!-- Formulário -->
        <div v-else class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ isEditing ? "Editar Banner" : "Novo Banner" }}
                </h3>
                <button
                    @click="closeForm"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Informações básicas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            for="title"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Título do Banner *
                        </label>
                        <input
                            id="title"
                            v-model="formData.title"
                            type="text"
                            required
                            placeholder="Título do banner"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>

                    <div>
                        <label
                            for="position"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Posição *
                        </label>
                        <select
                            id="position"
                            v-model="formData.position"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        >
                            <option value="">Selecione uma posição</option>
                            <option value="hero">Hero (principal)</option>
                            <option value="sidebar">Sidebar</option>
                            <option value="footer">Rodapé</option>
                            <option value="category">Categoria</option>
                            <option value="product">Produto</option>
                        </select>
                    </div>
                </div>

                <!-- Imagem -->
                <div>
                    <label
                        for="image"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        URL da Imagem *
                    </label>
                    <input
                        id="image"
                        v-model="formData.image"
                        type="url"
                        required
                        placeholder="https://exemplo.com/imagem.jpg"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        :disabled="loading"
                    />
                    <p class="text-xs text-gray-500 mt-1">
                        URL da imagem do banner. Recomendado: 1920x600px para
                        hero, 300x250px para sidebar.
                    </p>
                </div>

                <!-- Link e texto alternativo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            for="link"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Link (Opcional)
                        </label>
                        <input
                            id="link"
                            v-model="formData.link"
                            type="url"
                            placeholder="https://exemplo.com/pagina"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            URL para onde o banner deve redirecionar.
                        </p>
                    </div>

                    <div>
                        <label
                            for="alt_text"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Texto Alternativo
                        </label>
                        <input
                            id="alt_text"
                            v-model="formData.alt_text"
                            type="text"
                            placeholder="Descrição da imagem"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            Texto exibido quando a imagem não carrega.
                        </p>
                    </div>
                </div>

                <!-- Descrição e conteúdo -->
                <div>
                    <label
                        for="description"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Descrição
                    </label>
                    <textarea
                        id="description"
                        v-model="formData.description"
                        rows="3"
                        placeholder="Descrição do banner..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        :disabled="loading"
                        maxlength="300"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ formData.description.length }}/300 caracteres
                    </p>
                </div>

                <!-- Configurações -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label
                            for="order"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Ordem
                        </label>
                        <input
                            id="order"
                            v-model.number="formData.order"
                            type="number"
                            min="0"
                            placeholder="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            Ordem de exibição.
                        </p>
                    </div>

                    <div>
                        <label
                            for="start_date"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Data de Início
                        </label>
                        <input
                            id="start_date"
                            v-model="formData.start_date"
                            type="datetime-local"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>

                    <div>
                        <label
                            for="end_date"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Data de Fim
                        </label>
                        <input
                            id="end_date"
                            v-model="formData.end_date"
                            type="datetime-local"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>
                </div>

                <!-- Status e configurações -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Configurações
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input
                                v-model="formData.is_active"
                                type="checkbox"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                :disabled="loading"
                            />
                            <span class="ml-2 text-sm text-gray-700"
                                >Banner ativo</span
                            >
                        </label>
                        <label class="flex items-center">
                            <input
                                v-model="formData.open_in_new_tab"
                                type="checkbox"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                :disabled="loading"
                            />
                            <span class="ml-2 text-sm text-gray-700"
                                >Abrir link em nova aba</span
                            >
                        </label>
                        <label class="flex items-center">
                            <input
                                v-model="formData.show_on_mobile"
                                type="checkbox"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                :disabled="loading"
                            />
                            <span class="ml-2 text-sm text-gray-700"
                                >Mostrar no mobile</span
                            >
                        </label>
                    </div>
                </div>

                <!-- Preview do banner -->
                <div v-if="formData.image" class="border-t pt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">
                        Preview
                    </h4>
                    <div class="bg-gray-100 p-4 rounded-md">
                        <div
                            class="relative overflow-hidden rounded-md bg-white"
                        >
                            <img
                                :src="formData.image"
                                :alt="formData.alt_text || formData.title"
                                class="w-full h-32 object-cover"
                                @error="imageError = true"
                                v-show="!imageError"
                            />
                            <div
                                v-if="imageError"
                                class="w-full h-32 bg-gray-200 flex items-center justify-center"
                            >
                                <i
                                    class="fas fa-image text-gray-400 text-2xl"
                                ></i>
                            </div>
                            <div
                                v-if="formData.title"
                                class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center"
                            >
                                <h3
                                    class="text-white text-lg font-bold text-center px-4"
                                >
                                    {{ formData.title }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="flex gap-3 pt-6 border-t">
                    <button
                        type="button"
                        @click="closeForm"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                        :disabled="loading"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        :disabled="!canSubmit || loading"
                    >
                        <i
                            v-if="loading"
                            class="fas fa-spinner fa-spin mr-2"
                        ></i>
                        <i v-else class="fas fa-save mr-2"></i>
                        {{
                            loading
                                ? "Salvando..."
                                : isEditing
                                  ? "Atualizar Banner"
                                  : "Criar Banner"
                        }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    banner: {
        type: Object,
        default: null,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["create-banner", "update-banner", "close-form"]);

const showForm = ref(false);
const isEditing = ref(false);
const imageError = ref(false);

const formData = ref({
    title: "",
    image: "",
    link: "",
    alt_text: "",
    description: "",
    position: "",
    order: 0,
    start_date: "",
    end_date: "",
    is_active: true,
    open_in_new_tab: false,
    show_on_mobile: true,
});

// Computed properties
const canSubmit = computed(() => {
    return (
        formData.value.title.trim() !== "" &&
        formData.value.image.trim() !== "" &&
        formData.value.position !== "" &&
        !props.loading
    );
});

// Methods
const openCreateForm = () => {
    resetForm();
    isEditing.value = false;
    showForm.value = true;
};

const openEditForm = (banner) => {
    formData.value = {
        title: banner.title || "",
        image: banner.image || "",
        link: banner.link || "",
        alt_text: banner.alt_text || "",
        description: banner.description || "",
        position: banner.position || "",
        order: banner.order || 0,
        start_date: banner.start_date
            ? new Date(banner.start_date).toISOString().slice(0, 16)
            : "",
        end_date: banner.end_date
            ? new Date(banner.end_date).toISOString().slice(0, 16)
            : "",
        is_active: banner.is_active !== false,
        open_in_new_tab: banner.open_in_new_tab || false,
        show_on_mobile: banner.show_on_mobile !== false,
    };
    imageError.value = false;
    isEditing.value = true;
    showForm.value = true;
};

const closeForm = () => {
    showForm.value = false;
    emit("close-form");
};

const resetForm = () => {
    formData.value = {
        title: "",
        image: "",
        link: "",
        alt_text: "",
        description: "",
        position: "",
        order: 0,
        start_date: "",
        end_date: "",
        is_active: true,
        open_in_new_tab: false,
        show_on_mobile: true,
    };
    imageError.value = false;
};

const handleSubmit = () => {
    if (!canSubmit.value) return;

    const bannerData = {
        ...formData.value,
        // Converter datas para formato ISO se fornecidas
        start_date: formData.value.start_date || null,
        end_date: formData.value.end_date || null,
    };

    if (isEditing.value && props.banner) {
        emit("update-banner", { id: props.banner.id, ...bannerData });
    } else {
        emit("create-banner", bannerData);
    }
};

// Watch for prop changes
watch(
    () => props.banner,
    (newBanner) => {
        if (newBanner && !showForm.value) {
            openEditForm(newBanner);
        }
    },
    { immediate: true },
);
</script>
