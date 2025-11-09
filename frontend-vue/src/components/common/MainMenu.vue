<template>
    <nav class="bg-white shadow sticky top-0 z-40">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center h-16"
        >
            <div class="flex-shrink-0 font-bold text-xl text-blue-700">
                <router-link to="/">Minha Loja</router-link>
            </div>
            <div class="ml-8 flex-1">
                <ul class="flex space-x-6">
                    <li
                        v-for="cat in categories"
                        :key="cat.id"
                        class="relative group"
                    >
                        <router-link
                            :to="`/produtos?category_id=${cat.id}`"
                            class="hover:text-blue-600 font-medium"
                        >
                            {{ cat.name }}
                        </router-link>
                        <div
                            v-if="cat.children && cat.children.length"
                            class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity"
                        >
                            <ul>
                                <li v-for="sub in cat.children" :key="sub.id">
                                    <router-link
                                        :to="`/produtos?category_id=${sub.id}`"
                                        class="block px-4 py-2 hover:bg-blue-50"
                                        >{{ sub.name }}</router-link
                                    >
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="ml-auto flex items-center space-x-4">
                <Breadcrumbs :items="breadcrumbs" />
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import Breadcrumbs from "./Breadcrumbs.vue";

const categories = ref([]);

const fetchCategories = async () => {
    try {
        const { data } = await axios.get("/api/categories?tree=1");
        categories.value = data.data || data;
    } catch (e) {
        categories.value = [];
    }
};

onMounted(fetchCategories);

const route = useRoute();

const breadcrumbs = computed(() => {
    // Exemplo simples: Início > Categoria > Produto
    const crumbs = [{ label: "Início", to: "/" }];
    if (route.query.category_id) {
        const cat = findCategoryById(categories.value, route.query.category_id);
        if (cat)
            crumbs.push({
                label: cat.name,
                to: `/produtos?category_id=${cat.id}`,
            });
    }
    if (route.name === "ProductDetail" && route.params.id) {
        crumbs.push({ label: "Produto", to: route.fullPath });
    }
    return crumbs;
});

function findCategoryById(list, id) {
    for (const cat of list) {
        if (String(cat.id) === String(id)) return cat;
        if (cat.children) {
            const found = findCategoryById(cat.children, id);
            if (found) return found;
        }
    }
    return null;
}
</script>

<style scoped>
nav ul li .group-hover\:opacity-100 {
    pointer-events: auto;
}
</style>
