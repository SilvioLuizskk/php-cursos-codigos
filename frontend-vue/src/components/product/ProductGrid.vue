<template>
    <div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Produtos</h2>
            <div>
                <button
                    @click="setView('grid')"
                    :class="view === 'grid' ? 'text-blue-600' : ''"
                >
                    Grid
                </button>
                <button
                    @click="setView('list')"
                    :class="view === 'list' ? 'text-blue-600 ml-2' : 'ml-2'"
                >
                    Lista
                </button>
            </div>
        </div>
        <div
            v-if="view === 'grid'"
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6"
        >
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
            />
        </div>
        <div v-else class="space-y-4">
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
                list
            />
        </div>
        <div v-if="loading" class="text-center py-8">
            <span>Carregando...</span>
        </div>
        <div
            v-if="!loading && !products.length"
            class="text-center py-8 text-gray-400"
        >
            Nenhum produto encontrado.
        </div>
        <div v-if="hasMore" class="flex justify-center mt-6">
            <button
                @click="loadMore"
                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                Carregar mais
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import ProductCard from "./ProductCard.vue";
import axios from "axios";
import { useRoute } from "vue-router";

const products = ref([]);
const page = ref(1);
const perPage = 12;
const hasMore = ref(true);
const loading = ref(false);
const view = ref("grid");
const route = useRoute();

const fetchProducts = async (reset = false) => {
    loading.value = true;
    if (reset) {
        products.value = [];
        page.value = 1;
        hasMore.value = true;
    }
    try {
        const { data } = await axios.get("/api/products", {
            params: {
                ...route.query,
                page: page.value,
                per_page: perPage,
            },
        });
        if (reset) {
            products.value = data.data || [];
        } else {
            products.value = [...products.value, ...(data.data || [])];
        }
        hasMore.value = (data.data?.length || 0) === perPage;
        page.value++;
    } catch (e) {
        hasMore.value = false;
    } finally {
        loading.value = false;
    }
};

const loadMore = () => fetchProducts();
const setView = (v) => (view.value = v);

onMounted(() => fetchProducts(true));
watch(
    () => route.query,
    () => fetchProducts(true),
);
</script>
