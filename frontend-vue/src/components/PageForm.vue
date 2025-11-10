<template>
    <div class="page-form">
        <!-- Botão para criar nova página -->
        <div v-if="!showForm" class="mb-6">
            <button
                @click="openCreateForm()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
            >
                <i class="fas fa-plus mr-2"></i>
                Nova Página
            </button>
        </div>

        <!-- Formulário -->
        <div v-else class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ isEditing ? "Editar Página" : "Nova Página" }}
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
                            Título da Página *
                        </label>
                        <input
                            id="title"
                            v-model="formData.title"
                            type="text"
                            required
                            placeholder="Título da página"
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
                            placeholder="url-da-pagina"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            Usado na URL. Apenas letras minúsculas, números e
                            hífens.
                        </p>
                    </div>
                </div>

                <!-- Tipo de página -->
                <div>
                    <label
                        for="type"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Tipo de Página *
                    </label>
                    <select
                        id="type"
                        v-model="formData.type"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        :disabled="loading"
                    >
                        <option value="">Selecione o tipo</option>
                        <option value="page">Página Normal</option>
                        <option value="blog">Post do Blog</option>
                        <option value="landing">Landing Page</option>
                        <option value="legal">Página Legal</option>
                    </select>
                </div>

                <!-- Conteúdo -->
                <div>
                    <label
                        for="content"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Conteúdo *
                    </label>
                    <textarea
                        id="content"
                        v-model="formData.content"
                        rows="10"
                        required
                        placeholder="Conteúdo da página em HTML ou Markdown..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none font-mono text-sm"
                        :disabled="loading"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        Você pode usar HTML ou Markdown. Para páginas complexas,
                        considere usar um editor WYSIWYG.
                    </p>
                </div>

                <!-- Excerpt -->
                <div>
                    <label
                        for="excerpt"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Resumo (Excerpt)
                    </label>
                    <textarea
                        id="excerpt"
                        v-model="formData.excerpt"
                        rows="3"
                        placeholder="Breve resumo da página..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        :disabled="loading"
                        maxlength="300"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ formData.excerpt.length }}/300 caracteres. Usado em
                        previews e meta descriptions.
                    </p>
                </div>

                <!-- Imagem destacada -->
                <div>
                    <label
                        for="featured_image"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Imagem Destacada
                    </label>
                    <input
                        id="featured_image"
                        v-model="formData.featured_image"
                        type="url"
                        placeholder="URL da imagem destacada"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        :disabled="loading"
                    />
                </div>

                <!-- Configurações -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            for="template"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Template
                        </label>
                        <select
                            id="template"
                            v-model="formData.template"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        >
                            <option value="default">Padrão</option>
                            <option value="full-width">Largura Total</option>
                            <option value="sidebar">Com Sidebar</option>
                            <option value="landing">Landing Page</option>
                        </select>
                    </div>

                    <div>
                        <label
                            for="order"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Ordem no Menu
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
                    </div>
                </div>

                <!-- Status e visibilidade -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Configurações de Visibilidade
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
                                >Página publicada</span
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
                                >Mostrar no menu de navegação</span
                            >
                        </label>
                        <label class="flex items-center">
                            <input
                                v-model="formData.show_in_footer"
                                type="checkbox"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                :disabled="loading"
                            />
                            <span class="ml-2 text-sm text-gray-700"
                                >Mostrar no rodapé</span
                            >
                        </label>
                        <label class="flex items-center">
                            <input
                                v-model="formData.require_auth"
                                type="checkbox"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                :disabled="loading"
                            />
                            <span class="ml-2 text-sm text-gray-700"
                                >Requer autenticação</span
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
                                  ? "Atualizar Página"
                                  : "Criar Página"
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
    page: {
        type: Object,
        default: null,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["create-page", "update-page", "close-form"]);

const showForm = ref(false);
const isEditing = ref(false);

const formData = ref({
    title: "",
    slug: "",
    type: "page",
    content: "",
    excerpt: "",
    featured_image: "",
    template: "default",
    order: 0,
    is_active: true,
    show_in_menu: false,
    show_in_footer: false,
    require_auth: false,
    meta_title: "",
    meta_description: "",
});

// Computed properties
const canSubmit = computed(() => {
    return (
        formData.value.title.trim() !== "" &&
        formData.value.slug.trim() !== "" &&
        formData.value.content.trim() !== "" &&
        formData.value.type !== "" &&
        !props.loading
    );
});

// Methods
const openCreateForm = () => {
    resetForm();
    isEditing.value = false;
    showForm.value = true;
};

const openEditForm = (page) => {
    formData.value = {
        title: page.title || "",
        slug: page.slug || "",
        type: page.type || "page",
        content: page.content || "",
        excerpt: page.excerpt || "",
        featured_image: page.featured_image || "",
        template: page.template || "default",
        order: page.order || 0,
        is_active: page.is_active !== false,
        show_in_menu: page.show_in_menu || false,
        show_in_footer: page.show_in_footer || false,
        require_auth: page.require_auth || false,
        meta_title: page.meta_title || "",
        meta_description: page.meta_description || "",
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
        title: "",
        slug: "",
        type: "page",
        content: "",
        excerpt: "",
        featured_image: "",
        template: "default",
        order: 0,
        is_active: true,
        show_in_menu: false,
        show_in_footer: false,
        require_auth: false,
        meta_title: "",
        meta_description: "",
    };
};

// Auto-gerar slug baseado no título
watch(
    () => formData.value.title,
    (newTitle) => {
        if (newTitle && !isEditing.value) {
            formData.value.slug = newTitle
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

    if (isEditing.value && props.page) {
        emit("update-page", { id: props.page.id, ...formData.value });
    } else {
        emit("create-page", formData.value);
    }
};

// Watch for prop changes
watch(
    () => props.page,
    (newPage) => {
        if (newPage && !showForm.value) {
            openEditForm(newPage);
        }
    },
    { immediate: true },
);
</script>
