<template>
    <div class="search-filters bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            Buscar Produtos
        </h3>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <!-- Campo de busca por texto -->
            <div>
                <label
                    for="search"
                    class="block text-sm font-medium text-gray-700 mb-1"
                >
                    Buscar por nome ou descrição
                </label>
                <div class="relative">
                    <input
                        id="search"
                        v-model="localFilters.search"
                        type="text"
                        placeholder="Digite o nome do produto..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        @input="debouncedSearch"
                    />
                    <i
                        class="fas fa-search absolute left-3 top-3 text-gray-400"
                    ></i>
                </div>
            </div>

            <!-- Filtros de preço -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Faixa de Preço
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <input
                            v-model.number="localFilters.min_price"
                            type="number"
                            placeholder="Mín."
                            min="0"
                            step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @input="debouncedSearch"
                        />
                    </div>
                    <div>
                        <input
                            v-model.number="localFilters.max_price"
                            type="number"
                            placeholder="Máx."
                            min="0"
                            step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @input="debouncedSearch"
                        />
                    </div>
                </div>
            </div>

            <!-- Filtro por categoria -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Categoria
                </label>
                <select
                    v-model="localFilters.category"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    @change="handleSubmit"
                >
                    <option value="">Todas as categorias</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!-- Filtro por avaliação -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Avaliação Mínima
                </label>
                <select
                    v-model="localFilters.min_rating"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    @change="handleSubmit"
                >
                    <option value="">Qualquer avaliação</option>
                    <option value="4">4+ estrelas</option>
                    <option value="3">3+ estrelas</option>
                    <option value="2">2+ estrelas</option>
                    <option value="1">1+ estrelas</option>
                </select>
            </div>

            <!-- Filtro por disponibilidade -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Disponibilidade
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input
                            v-model="localFilters.in_stock"
                            type="checkbox"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            @change="handleSubmit"
                        />
                        <span class="ml-2 text-sm text-gray-700"
                            >Apenas produtos em estoque</span
                        >
                    </label>
                    <label class="flex items-center">
                        <input
                            v-model="localFilters.on_sale"
                            type="checkbox"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            @change="handleSubmit"
                        />
                        <span class="ml-2 text-sm text-gray-700"
                            >Apenas produtos em promoção</span
                        >
                    </label>
                </div>
            </div>

            <!-- Botões de ação -->
            <div class="flex gap-2 pt-4">
                <button
                    type="submit"
                    class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                    :disabled="loading"
                >
                    <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
                    <i v-else class="fas fa-search mr-2"></i>
                    Buscar
                </button>
                <button
                    type="button"
                    @click="clearFilters"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                    :disabled="loading"
                >
                    <i class="fas fa-times mr-2"></i>
                    Limpar
                </button>
            </div>
        </form>

        <!-- Resultados da busca -->
        <div
            v-if="showResults && totalResults !== null"
            class="mt-4 p-3 bg-gray-50 rounded-md"
        >
            <p class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                {{ totalResults }} produto{{
                    totalResults !== 1 ? "s" : ""
                }}
                encontrado{{ totalResults !== 1 ? "s" : "" }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import { useDebounce } from "@/composables/useDebounce";

const props = defineProps({
    initialFilters: {
        type: Object,
        default: () => ({}),
    },
    categories: {
        type: Array,
        default: () => [],
    },
    loading: {
        type: Boolean,
        default: false,
    },
    totalResults: {
        type: Number,
        default: null,
    },
    showResults: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["apply-filters", "clear-filters"]);

const localFilters = ref({
    search: "",
    min_price: null,
    max_price: null,
    category: "",
    min_rating: null,
    in_stock: false,
    on_sale: false,
    ...props.initialFilters,
});

// Debounced search
const { debouncedFunction: debouncedSearch } = useDebounce(() => {
    handleSubmit();
}, 500);

const handleSubmit = () => {
    // Limpar valores vazios
    const cleanFilters = { ...localFilters.value };
    Object.keys(cleanFilters).forEach((key) => {
        if (cleanFilters[key] === "" || cleanFilters[key] === null) {
            delete cleanFilters[key];
        }
    });

    emit("apply-filters", cleanFilters);
};

const clearFilters = () => {
    localFilters.value = {
        search: "",
        min_price: null,
        max_price: null,
        category: "",
        min_rating: null,
        in_stock: false,
        on_sale: false,
    };
    emit("clear-filters");
};

// Atualizar filtros locais quando props mudam
watch(
    () => props.initialFilters,
    (newFilters) => {
        if (newFilters) {
            localFilters.value = { ...localFilters.value, ...newFilters };
        }
    },
    { deep: true },
);

onMounted(() => {
    // Aplicar filtros iniciais se houver
    if (Object.keys(props.initialFilters).length > 0) {
        handleSubmit();
    }
});
</script>
