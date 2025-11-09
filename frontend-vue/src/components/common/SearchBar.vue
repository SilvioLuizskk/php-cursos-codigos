<template>
    <div class="relative w-full max-w-2xl mx-auto">
        <div
            class="flex items-center border rounded-lg bg-white shadow-sm px-2 py-1"
        >
            <input
                v-model="query"
                @input="onInput"
                @keydown.down.prevent="moveSelection(1)"
                @keydown.up.prevent="moveSelection(-1)"
                @keydown.enter.prevent="selectSuggestion"
                type="text"
                class="flex-1 px-3 py-2 outline-none bg-transparent"
                placeholder="Buscar produtos, marcas..."
                autocomplete="off"
            />
            <select
                v-model="selectedCategory"
                class="ml-2 px-2 py-1 border rounded"
            >
                <option value="">Todas categorias</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                </option>
            </select>
            <button
                @click="search"
                class="ml-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                Buscar
            </button>
        </div>
        <ul
            v-if="showSuggestions && suggestions.length"
            class="absolute z-10 w-full bg-white border rounded-b shadow-lg mt-1 max-h-60 overflow-y-auto"
        >
            <li
                v-for="(suggestion, idx) in suggestions"
                :key="suggestion.id"
                :class="[
                    'px-4 py-2 cursor-pointer',
                    idx === selectedIndex ? 'bg-blue-100' : '',
                ]"
                @mousedown.prevent="selectSuggestion(idx)"
            >
                <span v-html="highlight(suggestion.name)"></span>
                <span v-if="suggestion.price" class="text-gray-500 ml-2"
                    >R$ {{ (parseFloat(suggestion.price) || 0).toFixed(2) }}</span
                >
            </li>
            <li v-if="!suggestions.length" class="px-4 py-2 text-gray-400">
                Nenhum resultado
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import axios from "axios";

const query = ref("");
const suggestions = ref([]);
const showSuggestions = ref(false);
const selectedIndex = ref(-1);
const selectedCategory = ref("");
const categories = ref([]);

const fetchCategories = async () => {
    try {
        const { data } = await axios.get("/api/categories");
        categories.value = data.data || data;
    } catch (e) {
        categories.value = [];
    }
};

fetchCategories();

const onInput = async () => {
    showSuggestions.value = !!query.value;
    if (!query.value) {
        suggestions.value = [];
        return;
    }
    try {
        const { data } = await axios.get("/api/products", {
            params: {
                search: query.value,
                category_id: selectedCategory.value || undefined,
                per_page: 5,
            },
        });
        suggestions.value = data.data || [];
        selectedIndex.value = -1;
    } catch (e) {
        suggestions.value = [];
    }
};

const moveSelection = (dir) => {
    if (!suggestions.value.length) return;
    let next = selectedIndex.value + dir;
    if (next < 0) next = suggestions.value.length - 1;
    if (next >= suggestions.value.length) next = 0;
    selectedIndex.value = next;
};

const selectSuggestion = (idx = selectedIndex.value) => {
    if (suggestions.value.length && idx >= 0) {
        const product = suggestions.value[idx];
        window.location.href = `/produto/${product.slug || product.id}`;
        showSuggestions.value = false;
    } else {
        search();
    }
};

const search = () => {
    const params = new URLSearchParams();
    if (query.value) params.append("search", query.value);
    if (selectedCategory.value)
        params.append("category_id", selectedCategory.value);
    window.location.href = `/produtos?${params.toString()}`;
};

const highlight = (text) => {
    if (!query.value) return text;
    const re = new RegExp(`(${query.value})`, "gi");
    return text.replace(re, "<mark>$1</mark>");
};
</script>

<style scoped>
mark {
    background: #ffe066;
    color: inherit;
    padding: 0;
}
</style>
