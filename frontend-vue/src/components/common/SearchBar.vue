<template>
    <div class="relative w-full max-w-md mx-auto">
        <div class="relative">
            <input
                v-model="searchQuery"
                @input="onInput"
                @keydown="handleKeydown"
                type="text"
                placeholder="Buscar produtos..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
            <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
            >
                <i class="fas fa-search text-gray-400"></i>
            </div>
        </div>

        <!-- Dropdown de sugestões -->
        <div
            v-if="showSuggestions && suggestions.length > 0"
            class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto"
        >
            <div
                v-for="(suggestion, index) in suggestions"
                :key="suggestion.id"
                @click="selectSuggestion(suggestion)"
                @mouseenter="highlightedIndex = index"
                class="px-4 py-2 cursor-pointer hover:bg-gray-50 flex items-center justify-between"
                :class="{ 'bg-blue-50': highlightedIndex === index }"
            >
                <div class="flex items-center">
                    <i class="fas fa-box text-gray-400 mr-2"></i>
                    <span class="text-gray-900">{{ suggestion.name }}</span>
                </div>
                <span class="text-sm text-gray-500">{{
                    suggestion.category
                }}</span>
            </div>
        </div>

        <!-- Filtros de categoria -->
        <div v-if="categories.length > 0" class="mt-2 flex flex-wrap gap-2">
            <button
                v-for="category in categories"
                :key="category.id"
                @click="filterByCategory(category.id)"
                class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-full transition-colors"
                :class="{
                    'bg-blue-100 text-blue-700':
                        selectedCategory === category.id,
                }"
            >
                {{ category.name }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { useDebounce } from "@/composables/useDebounce";

const router = useRouter();

// Dados reativos
const searchQuery = ref("");
const suggestions = ref([]);
const categories = ref([]);
const showSuggestions = ref(false);
const highlightedIndex = ref(-1);
const selectedCategory = ref(null);

// Debounced search
const debouncedSearch = useDebounce(async (query) => {
    if (query.length < 2) {
        suggestions.value = [];
        showSuggestions.value = false;
        return;
    }

    try {
        const response = await axios.get("/api/products/search", {
            params: {
                q: query,
                category: selectedCategory.value,
                limit: 5,
            },
        });

        suggestions.value = response.data.data || [];
        showSuggestions.value = suggestions.value.length > 0;
        highlightedIndex.value = -1;
    } catch (error) {
        console.error("Erro na busca:", error);
        suggestions.value = [];
        showSuggestions.value = false;
    }
}, 300);

// Computed
const cartCount = computed(() => {
    // Implementar lógica do carrinho
    return 0;
});

// Métodos
const onInput = () => {
    debouncedSearch(searchQuery.value);
};

const handleKeydown = (event) => {
    if (!showSuggestions.value || suggestions.value.length === 0) return;

    switch (event.key) {
        case "ArrowDown":
            event.preventDefault();
            highlightedIndex.value = Math.min(
                highlightedIndex.value + 1,
                suggestions.value.length - 1,
            );
            break;
        case "ArrowUp":
            event.preventDefault();
            highlightedIndex.value = Math.max(highlightedIndex.value - 1, -1);
            break;
        case "Enter":
            event.preventDefault();
            if (highlightedIndex.value >= 0) {
                selectSuggestion(suggestions.value[highlightedIndex.value]);
            } else {
                performSearch();
            }
            break;
        case "Escape":
            showSuggestions.value = false;
            highlightedIndex.value = -1;
            break;
    }
};

const selectSuggestion = (suggestion) => {
    searchQuery.value = suggestion.name;
    showSuggestions.value = false;
    router.push(`/produto/${suggestion.id}`);
};

const performSearch = () => {
    if (searchQuery.value.trim()) {
        router.push({
            path: "/produtos",
            query: {
                q: searchQuery.value.trim(),
                category: selectedCategory.value,
            },
        });
        showSuggestions.value = false;
    }
};

const filterByCategory = (categoryId) => {
    selectedCategory.value =
        selectedCategory.value === categoryId ? null : categoryId;
    if (searchQuery.value) {
        debouncedSearch(searchQuery.value);
    }
};

const loadCategories = async () => {
    try {
        const response = await axios.get("/api/categories");
        categories.value = response.data.data || [];
    } catch (error) {
        console.error("Erro ao carregar categorias:", error);
    }
};

// Lifecycle
onMounted(() => {
    loadCategories();
});

// Expor métodos para uso externo
defineExpose({
    performSearch,
});
</script>

<style scoped>
/* Estilos adicionais se necessário */
</style>
