<template>
    <div class="product-form">
        <!-- Botão para criar novo produto -->
        <div v-if="!showForm" class="mb-6">
            <button
                @click="openCreateForm()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
            >
                <i class="fas fa-plus mr-2"></i>
                Novo Produto
            </button>
        </div>

        <!-- Formulário -->
        <div v-else class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ isEditing ? "Editar Produto" : "Novo Produto" }}
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
                            Nome do Produto *
                        </label>
                        <input
                            id="name"
                            v-model="formData.name"
                            type="text"
                            required
                            placeholder="Nome do produto"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>

                    <div>
                        <label
                            for="sku"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            SKU *
                        </label>
                        <input
                            id="sku"
                            v-model="formData.sku"
                            type="text"
                            required
                            placeholder="Código único do produto"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label
                        for="description"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Descrição *
                    </label>
                    <textarea
                        id="description"
                        v-model="formData.description"
                        rows="4"
                        required
                        placeholder="Descrição detalhada do produto..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        :disabled="loading"
                        maxlength="1000"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ formData.description.length }}/1000 caracteres
                    </p>
                </div>

                <!-- Preços e estoque -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label
                            for="price"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Preço (R$) *
                        </label>
                        <input
                            id="price"
                            v-model.number="formData.price"
                            type="number"
                            required
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>

                    <div>
                        <label
                            for="sale_price"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Preço Promocional (R$)
                        </label>
                        <input
                            id="sale_price"
                            v-model.number="formData.sale_price"
                            type="number"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>

                    <div>
                        <label
                            for="stock_quantity"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Estoque *
                        </label>
                        <input
                            id="stock_quantity"
                            v-model.number="formData.stock_quantity"
                            type="number"
                            required
                            min="0"
                            placeholder="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>
                </div>

                <!-- Categoria -->
                <div>
                    <label
                        for="category_id"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Categoria *
                    </label>
                    <select
                        id="category_id"
                        v-model="formData.category_id"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        :disabled="loading"
                    >
                        <option value="">Selecione uma categoria</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <!-- Imagens -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Imagens do Produto
                    </label>
                    <div class="space-y-3">
                        <div
                            v-for="(image, index) in formData.images"
                            :key="index"
                            class="flex items-center gap-3"
                        >
                            <input
                                v-model="image.url"
                                type="url"
                                placeholder="URL da imagem"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :disabled="loading"
                            />
                            <button
                                v-if="formData.images.length > 1"
                                type="button"
                                @click="removeImage(index)"
                                class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                :disabled="loading"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <button
                            type="button"
                            @click="addImage"
                            class="inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700"
                            :disabled="loading"
                        >
                            <i class="fas fa-plus mr-1"></i>
                            Adicionar Imagem
                        </button>
                    </div>
                </div>

                <!-- Status e configurações -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
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
                                    >Produto ativo</span
                                >
                            </label>
                            <label class="flex items-center">
                                <input
                                    v-model="formData.featured"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    :disabled="loading"
                                />
                                <span class="ml-2 text-sm text-gray-700"
                                    >Produto em destaque</span
                                >
                            </label>
                        </div>
                    </div>

                    <div>
                        <label
                            for="weight"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Peso (kg)
                        </label>
                        <input
                            id="weight"
                            v-model.number="formData.weight"
                            type="number"
                            min="0"
                            step="0.001"
                            placeholder="0.000"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
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
                                  ? "Atualizar Produto"
                                  : "Criar Produto"
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
    product: {
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

const emit = defineEmits(["create-product", "update-product", "close-form"]);

const showForm = ref(false);
const isEditing = ref(false);

const formData = ref({
    name: "",
    sku: "",
    description: "",
    price: null,
    sale_price: null,
    stock_quantity: 0,
    category_id: "",
    images: [{ url: "" }],
    is_active: true,
    featured: false,
    weight: null,
    meta_title: "",
    meta_description: "",
});

// Computed properties
const canSubmit = computed(() => {
    return (
        formData.value.name.trim() !== "" &&
        formData.value.sku.trim() !== "" &&
        formData.value.description.trim() !== "" &&
        formData.value.price > 0 &&
        formData.value.stock_quantity >= 0 &&
        formData.value.category_id !== "" &&
        !props.loading
    );
});

// Methods
const openCreateForm = () => {
    resetForm();
    isEditing.value = false;
    showForm.value = true;
};

const openEditForm = (product) => {
    formData.value = {
        name: product.name || "",
        sku: product.sku || "",
        description: product.description || "",
        price: product.price || null,
        sale_price: product.sale_price || null,
        stock_quantity: product.stock_quantity || 0,
        category_id: product.category_id || "",
        images:
            product.images && product.images.length > 0
                ? product.images.map((url) => ({ url }))
                : [{ url: "" }],
        is_active: product.is_active !== false,
        featured: product.featured || false,
        weight: product.weight || null,
        meta_title: product.meta_title || "",
        meta_description: product.meta_description || "",
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
        sku: "",
        description: "",
        price: null,
        sale_price: null,
        stock_quantity: 0,
        category_id: "",
        images: [{ url: "" }],
        is_active: true,
        featured: false,
        weight: null,
        meta_title: "",
        meta_description: "",
    };
};

const addImage = () => {
    formData.value.images.push({ url: "" });
};

const removeImage = (index) => {
    if (formData.value.images.length > 1) {
        formData.value.images.splice(index, 1);
    }
};

const handleSubmit = () => {
    if (!canSubmit.value) return;

    const productData = {
        ...formData.value,
        // Filtrar imagens vazias
        images: formData.value.images
            .map((img) => img.url)
            .filter((url) => url.trim() !== ""),
    };

    if (isEditing.value && props.product) {
        emit("update-product", { id: props.product.id, ...productData });
    } else {
        emit("create-product", productData);
    }
};

// Watch for prop changes
watch(
    () => props.product,
    (newProduct) => {
        if (newProduct && !showForm.value) {
            openEditForm(newProduct);
        }
    },
    { immediate: true },
);
</script>
