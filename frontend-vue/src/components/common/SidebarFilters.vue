<template>
    <aside
        class="sidebar-filters bg-white rounded-lg shadow-sm p-6 mb-8 w-full md:w-64 md:sticky md:top-24"
    >
        <h2 class="font-bold text-lg mb-4">Filtrar por</h2>
        <!-- Categoria -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2"
                >Categoria</label
            >
            <select v-model="filters.category" class="input">
                <option value="">Todas</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                </option>
            </select>
        </div>
        <!-- Preço -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2"
                >Preço</label
            >
            <div class="flex gap-2">
                <input
                    v-model.number="filters.min_price"
                    type="number"
                    placeholder="Mín"
                    class="input"
                />
                <input
                    v-model.number="filters.max_price"
                    type="number"
                    placeholder="Máx"
                    class="input"
                />
            </div>
        </div>
        <!-- Avaliação -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2"
                >Avaliação</label
            >
            <div class="flex gap-2">
                <button
                    v-for="n in 5"
                    :key="n"
                    @click="setRating(n)"
                    :class="['star-btn', filters.rating === n ? 'active' : '']"
                >
                    <i
                        :class="
                            n <= filters.rating
                                ? 'fas fa-star text-yellow-400'
                                : 'far fa-star text-gray-300'
                        "
                    ></i>
                </button>
                <button
                    v-if="filters.rating"
                    @click="setRating(0)"
                    class="ml-2 text-xs text-gray-500 underline"
                >
                    Limpar
                </button>
            </div>
        </div>
        <!-- Destaque -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2"
                >Destaque</label
            >
            <input type="checkbox" v-model="filters.featured" class="mr-2" />
            Somente produtos em destaque
        </div>
        <button
            @click="$emit('apply-filters', filters)"
            class="btn btn-primary w-full mt-4"
        >
            Aplicar Filtros
        </button>
        <button @click="clearFilters" class="btn btn-outline w-full mt-2">
            Limpar
        </button>
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
</script>

<style scoped>
.input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    outline: none;
    margin-bottom: 0.25rem;
}
.input:focus {
    box-shadow: 0 0 0 2px #2563eb33;
    border-color: #2563eb;
}
.star-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 0 2px;
}
.star-btn.active i {
    color: #f59e42;
}
.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 600;
    transition:
        background 0.2s,
        color 0.2s;
}
.btn-primary {
    background: #2563eb;
    color: #fff;
}
.btn-primary:hover {
    background: #1d4ed8;
}
.btn-outline {
    border: 1px solid #2563eb;
    color: #2563eb;
    background: #fff;
}
.btn-outline:hover {
    background: #f0f6ff;
}
</style>
