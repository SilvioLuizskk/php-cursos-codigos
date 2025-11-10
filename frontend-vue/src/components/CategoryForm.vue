<template>
    <div class="category-form">
        <!-- Botão para criar nova categoria -->
        <div v-if="!showForm" class="mb-6">
            <button
                @click="openCreateForm()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
            >
                <i class="fas fa-plus mr-2"></i>
                Nova Categoria
            </button>
        </div>

        <!-- Formulário -->
        <div v-else class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ isEditing ? "Editar Categoria" : "Nova Categoria" }}
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
                            for="name"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Nome da Categoria *
                        </label>
                        <input
                            id="name"
                            v-model="formData.name"
                            type="text"
                            required
                            placeholder="Nome da categoria"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>

                    <div>
                        <label
                            for="slug"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Slug *
                        </label>
                        <input
                            id="slug"
                            v-model="formData.slug"
                            type="text"
                            required
                            placeholder="slug-da-categoria"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            Usado na URL. Apenas letras minúsculas, números e
                            hífens.
                        </p>
                    </div>
                </div>

                <!-- Descrição -->
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
                        placeholder="Descrição da categoria..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        :disabled="loading"
                        maxlength="500"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ formData.description.length }}/500 caracteres
                    </p>
                </div>

                <!-- Categoria pai -->
                <div>
                    <label
                        for="parent_id"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Categoria Pai
                    </label>
                    <select
                        id="parent_id"
                        v-model="formData.parent_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        :disabled="loading"
                    >
                        <option value="">Nenhuma (categoria raiz)</option>
                        <option
                            v-for="category in availableParentCategories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Deixe em branco para criar uma categoria principal.
                    </p>
                </div>

                <!-- Imagem da categoria -->
                <div>
                    <label
                        for="image"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Imagem da Categoria
                    </label>
                    <input
                        id="image"
                        v-model="formData.image"
                        type="url"
                        placeholder="URL da imagem da categoria"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        :disabled="loading"
                    />
                    <p class="text-xs text-gray-500 mt-1">
                        URL da imagem que representa a categoria.
                    </p>
                </div>

                <!-- Ordem de exibição -->
                <div>
                    <label
                        for="order"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Ordem de Exibição
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
                        Ordem de exibição no menu. Números menores aparecem
                        primeiro.
                    </p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
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
                                >Categoria ativa</span
                            >
                        </label>
                        <label class="flex items-center">
                            <input
                                v-model="formData.show_in_menu"
                                type="checkbox"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                :disabled="loading"
                            />
                            <span class="ml-2 text-sm text-gray-700"
                                >Mostrar no menu principal</span
                            >
                        </label>
                    </div>
                </div>

                <!-- SEO -->
                <div class="border-t pt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">
                        Otimização SEO
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                for="meta_title"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Meta Título
                            </label>
                            <input
                                id="meta_title"
                                v-model="formData.meta_title"
                                type="text"
                                placeholder="Título para SEO"
                                maxlength="60"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :disabled="loading"
                            />
                            <p class="text-xs text-gray-500 mt-1">
                                {{ formData.meta_title.length }}/60 caracteres
                            </p>
                        </div>

                        <div>
                            <label
                                for="meta_description"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Meta Descrição
                            </label>
                            <textarea
                                id="meta_description"
                                v-model="formData.meta_description"
                                rows="2"
                                placeholder="Descrição para SEO"
                                maxlength="160"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                :disabled="loading"
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ formData.meta_description.length }}/160
                                caracteres
                            </p>
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
                                  ? "Atualizar Categoria"
                                  : "Criar Categoria"
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
    category: {
        type: Object,
        default: null,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["create-category", "update-category", "close-form"]);

const showForm = ref(false);
const isEditing = ref(false);

const formData = ref({
    name: "",
    slug: "",
    description: "",
    parent_id: "",
    image: "",
    order: 0,
    is_active: true,
    show_in_menu: true,
    meta_title: "",
    meta_description: "",
});

// Computed properties
const canSubmit = computed(() => {
    return (
        formData.value.name.trim() !== "" &&
        formData.value.slug.trim() !== "" &&
        !props.loading
    );
});

const availableParentCategories = computed(() => {
    // Filtrar categorias que não sejam a própria categoria sendo editada
    return props.categories.filter((cat) => {
        if (isEditing.value && props.category) {
            return cat.id !== props.category.id;
        }
        return true;
    });
});

// Methods
const openCreateForm = () => {
    resetForm();
    isEditing.value = false;
    showForm.value = true;
};

const openEditForm = (category) => {
    formData.value = {
        name: category.name || "",
        slug: category.slug || "",
        description: category.description || "",
        parent_id: category.parent_id || "",
        image: category.image || "",
        order: category.order || 0,
        is_active: category.is_active !== false,
        show_in_menu: category.show_in_menu !== false,
        meta_title: category.meta_title || "",
        meta_description: category.meta_description || "",
    };
    isEditing.value = true;
    showForm.value = true;
};

const closeForm = () => {
    showForm.value = false;
    emit("close-form");
};

const resetForm = () => {
    formData.value = {
        name: "",
        slug: "",
        description: "",
        parent_id: "",
        image: "",
        order: 0,
        is_active: true,
        show_in_menu: true,
        meta_title: "",
        meta_description: "",
    };
};

// Auto-gerar slug baseado no nome
watch(
    () => formData.value.name,
    (newName) => {
        if (newName && !isEditing.value) {
            formData.value.slug = newName
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .replace(/[^a-z0-9\s-]/g, "")
                .trim()
                .replace(/\s+/g, "-")
                .replace(/-+/g, "-");
        }
    },
);

const handleSubmit = () => {
    if (!canSubmit.value) return;

    if (isEditing.value && props.category) {
        emit("update-category", { id: props.category.id, ...formData.value });
    } else {
        emit("create-category", formData.value);
    }
};

// Watch for prop changes
watch(
    () => props.category,
    (newCategory) => {
        if (newCategory && !showForm.value) {
            openEditForm(newCategory);
        }
    },
    { immediate: true },
);
</script>
