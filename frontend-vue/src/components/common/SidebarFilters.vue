<template>
    <aside class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-3"></i>
                Filtrar Produtos
            </h2>
            <button
                @click="$emit('apply-filters', {})"
                class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors"
            >
                Limpar Tudo
            </button>
        </div>

        <!-- Categoria -->
        <div class="mb-8">
            <label
                class="flex items-center text-sm font-semibold text-gray-800 mb-4"
            >
                <i class="fas fa-tags text-blue-600 mr-2"></i>
                Categoria
            </label>
            <div class="space-y-2">
                <button
                    @click="setCategory('')"
                    :class="[
                        'w-full text-left px-4 py-3 rounded-xl transition-all duration-200 flex items-center justify-between group',
                        !filters.category
                            ? 'bg-blue-50 text-blue-700 border-2 border-blue-200'
                            : 'bg-gray-50 text-gray-700 border-2 border-transparent hover:bg-blue-50 hover:border-blue-100',
                    ]"
                >
                    <span class="font-medium">Todas as Categorias</span>
                    <i
                        v-if="!filters.category"
                        class="fas fa-check text-blue-600"
                    ></i>
                </button>
                <button
                    v-for="category in categories"
                    :key="category.id"
                    @click="setCategory(category.id)"
                    :class="[
                        'w-full text-left px-4 py-3 rounded-xl transition-all duration-200 flex items-center justify-between group',
                        filters.category == category.id
                            ? 'bg-blue-50 text-blue-700 border-2 border-blue-200'
                            : 'bg-gray-50 text-gray-700 border-2 border-transparent hover:bg-blue-50 hover:border-blue-100',
                    ]"
                >
                    <span class="font-medium">{{ category.name }}</span>
                    <i
                        v-if="filters.category == category.id"
                        class="fas fa-check text-blue-600"
                    ></i>
                </button>
            </div>
        </div>

        <!-- Preço -->
        <div class="mb-8">
            <label
                class="flex items-center text-sm font-semibold text-gray-800 mb-4"
            >
                <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                Faixa de Preço
            </label>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-600 mb-1"
                        >Preço Mínimo</label
                    >
                    <input
                        v-model.number="filters.min_price"
                        type="number"
                        placeholder="R$ 0"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    />
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1"
                        >Preço Máximo</label
                    >
                    <input
                        v-model.number="filters.max_price"
                        type="number"
                        placeholder="R$ 1000"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    />
                </div>
            </div>
        </div>

        <!-- Avaliação -->
        <div class="mb-8">
            <label
                class="flex items-center text-sm font-semibold text-gray-800 mb-4"
            >
                <i class="fas fa-star text-yellow-500 mr-2"></i>
                Avaliação Mínima
            </label>
            <div class="space-y-2">
                <button
                    v-for="n in 5"
                    :key="n"
                    @click="setRating(n)"
                    :class="[
                        'w-full px-4 py-3 rounded-xl transition-all duration-200 flex items-center justify-between group',
                        filters.rating === n
                            ? 'bg-yellow-50 text-yellow-700 border-2 border-yellow-200'
                            : 'bg-gray-50 text-gray-700 border-2 border-transparent hover:bg-yellow-50 hover:border-yellow-100',
                    ]"
                >
                    <div class="flex items-center">
                        <div class="flex text-yellow-400 mr-3">
                            <i
                                v-for="star in 5"
                                :key="star"
                                :class="
                                    star <= n ? 'fas fa-star' : 'far fa-star'
                                "
                            ></i>
                        </div>
                        <span class="font-medium"
                            >{{ n }} estrela{{ n > 1 ? "s" : "" }}</span
                        >
                    </div>
                    <i
                        v-if="filters.rating === n"
                        class="fas fa-check text-yellow-600"
                    ></i>
                </button>
                <button
                    v-if="filters.rating"
                    @click="setRating(0)"
                    class="w-full px-4 py-2 text-sm text-gray-500 hover:text-gray-700 transition-colors underline"
                >
                    Remover filtro
                </button>
            </div>
        </div>

        <!-- Destaque -->
        <div class="mb-8">
            <label
                class="flex items-center text-sm font-semibold text-gray-800 mb-4"
            >
                <i class="fas fa-crown text-purple-600 mr-2"></i>
                Características
            </label>
            <div class="space-y-3">
                <label
                    class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-purple-50 transition-colors cursor-pointer"
                >
                    <input
                        v-model="filters.featured"
                        type="checkbox"
                        class="w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2"
                    />
                    <span class="ml-3 text-sm font-medium text-gray-700">
                        <i class="fas fa-star text-purple-600 mr-2"></i>
                        Apenas produtos em destaque
                    </span>
                </label>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="space-y-3">
            <button
                @click="$emit('apply-filters', filters)"
                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center"
            >
                <i class="fas fa-search mr-2"></i>
                Aplicar Filtros
            </button>
            <button
                @click="clearFilters"
                class="w-full bg-white border-2 border-gray-200 text-gray-700 py-3 px-6 rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 flex items-center justify-center"
            >
                <i class="fas fa-undo mr-2"></i>
                Limpar Filtros
            </button>
        </div>

        <!-- Estatísticas -->
        <div
            class="mt-8 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-100"
        >
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600 mb-1">
                    {{ categories.length }}
                </div>
                <div class="text-sm text-gray-600">Categorias Disponíveis</div>
            </div>
        </div>
    </aside>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from "vue";

const props = defineProps({
    categories: { type: Array, default: () => [] },
    initialFilters: { type: Object, default: () => ({}) },
});
const emit = defineEmits(["apply-filters"]);

const filters = ref({
    category: "",
    min_price: null,
    max_price: null,
    rating: 0,
    featured: false,
    ...props.initialFilters,
});

watch(
    () => props.initialFilters,
    (val) => {
        Object.assign(filters.value, val);
    },
);

const setRating = (n) => {
    filters.value.rating = n;
};

const clearFilters = () => {
    filters.value = {
        category: "",
        min_price: null,
        max_price: null,
        rating: 0,
        featured: false,
    };
    emit("apply-filters", filters.value);
};

const setCategory = (categoryId) => {
    filters.value.category = categoryId;
};
</script>

<style scoped>
/* Estilos globais para o componente */
.bg-white {
    background-color: #ffffff;
}

.rounded-2xl {
    border-radius: 1rem;
}

.shadow-xl {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.p-8 {
    padding: 2rem;
}

.border {
    border-width: 1px;
}

.border-gray-100 {
    border-color: #f3f4f6;
}

.mb-6 {
    margin-bottom: 1.5rem;
}

.text-xl {
    font-size: 1.25rem;
}

.font-bold {
    font-weight: 700;
}

.text-gray-900 {
    color: #111827;
}

.flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.justify-between {
    justify-content: space-between;
}

.hover\:text-blue-800:hover {
    color: #1e40af;
}

.transition-colors {
    transition-property: color;
}

.mb-8 {
    margin-bottom: 2rem;
}

.text-sm {
    font-size: 0.875rem;
}

.font-semibold {
    font-weight: 600;
}

.text-gray-800 {
    color: #374151;
}

.mr-2 {
    margin-right: 0.5rem;
}

.space-y-2 > * {
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

.w-full {
    width: 100%;
}

.text-left {
    text-align: left;
}

.px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}

.py-3 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}

.rounded-xl {
    border-radius: 0.75rem;
}

.transition-all {
    transition-property: all;
}

.duration-200 {
    transition-duration: 200ms;
}

.group:hover .bg-blue-50 {
    background-color: #eff6ff;
}

.group:hover .text-blue-700 {
    color: #1d4ed8;
}

.group:hover .border-blue-200 {
    border-color: #bfdbfe;
}

.bg-blue-50 {
    background-color: #eff6ff;
}

.text-blue-700 {
    color: #1d4ed8;
}

.border-2 {
    border-width: 2px;
}

.border-transparent {
    border-color: transparent;
}

.hover\:bg-blue-50:hover {
    background-color: #eff6ff;
}

.hover\:border-blue-100:hover {
    border-color: #bfdbfe;
}

.text-gray-700 {
    color: #374151;
}

.bg-gray-50 {
    background-color: #f9fafb;
}

.text-yellow-400 {
    color: #f59e42;
}

.mr-3 {
    margin-right: 0.75rem;
}

.text-yellow-700 {
    color: #b45309;
}

.bg-yellow-50 {
    background-color: #fefce8;
}

.border-yellow-200 {
    border-color: #fef9c3;
}

.hover\:bg-yellow-50:hover {
    background-color: #fefce8;
}

.hover\:border-yellow-100:hover {
    border-color: #fef9c3;
}

.text-purple-600 {
    color: #6d28d9;
}

.bg-purple-50 {
    background-color: #f5f3ff;
}

.hover\:bg-purple-50:hover {
    background-color: #e0d7ff;
}

.text-gray-600 {
    color: #4b5563;
}

.font-medium {
    font-weight: 500;
}

.cursor-pointer {
    cursor: pointer;
}

.w-5 {
    width: 1.25rem;
}

.h-5 {
    height: 1.25rem;
}

.bg-gray-100 {
    background-color: #f3f4f6;
}

.border-gray-300 {
    border-color: #d1d5db;
}

.focus\:ring-purple-500:focus {
    --tw-ring-color: #a855f7;
}

.focus\:ring-2:focus {
    --tw-ring-width: 2px;
}

.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-blue-600 {
    --tw-gradient-from: #2563eb;
    --tw-gradient-to: #4f46e5;
}

.to-purple-600 {
    --tw-gradient-from: #6d28d9;
    --tw-gradient-to: #9333ea;
}

.hover\:from-blue-700:hover {
    --tw-gradient-from: #1d4ed8;
    --tw-gradient-to: #4338ca;
}

.hover\:to-purple-700:hover {
    --tw-gradient-from: #7c3aed;
    --tw-gradient-to: #9333ea;
}

.py-4 {
    padding-top: 1rem;
    padding-bottom: 1rem;
}

.px-6 {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

.rounded-xl {
    border-radius: 0.75rem;
}

.hover\:scale-105:hover {
    transform: scale(1.05);
}

.shadow-lg {
    box-shadow:
        0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.hover\:shadow-xl:hover {
    box-shadow:
        0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-blue-50 {
    --tw-gradient-from: #eff6ff;
    --tw-gradient-to: #ffffff;
}

.to-purple-50 {
    --tw-gradient-from: #f5f3ff;
    --tw-gradient-to: #ffffff;
}

.border-blue-100 {
    border-color: #bfdbfe;
}

.text-2xl {
    font-size: 1.5rem;
}

.font-bold {
    font-weight: 700;
}

.text-blue-600 {
    color: #2563eb;
}

.mb-1 {
    margin-bottom: 0.25rem;
}

.text-sm {
    font-size: 0.875rem;
}

.text-gray-600 {
    color: #4b5563;
}

.rounded-xl {
    border-radius: 0.75rem;
}

.border {
    border-width: 1px;
}

.border-blue-100 {
    border-color: #bfdbfe;
}

.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-blue-50 {
    --tw-gradient-from: #eff6ff;
    --tw-gradient-to: #ffffff;
}

.to-purple-50 {
    --tw-gradient-from: #f5f3ff;
    --tw-gradient-to: #ffffff;
}
</style>
