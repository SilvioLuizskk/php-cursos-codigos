import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { productService } from "@/api/productService";

export const useProductStore = defineStore("products", () => {
  // Estado
  const products = ref([]);
  const featured = ref([]);
  const currentProduct = ref(null);
  const loading = ref(false);
  const error = ref(null);
  const filters = ref({
    page: 1,
    per_page: 20,
    category_id: null,
    search: "",
    min_price: null,
    max_price: null,
    rating: null,
    sort_by: "created_at",
    sort_order: "desc",
  });
  const pagination = ref({
    total: 0,
    per_page: 20,
    current_page: 1,
    last_page: 1,
  });

  // Computados
  const hasProducts = computed(() => products.value.length > 0);

  // Ações
  async function fetchProducts(newFilters = {}) {
    loading.value = true;
    error.value = null;

    try {
      Object.assign(filters.value, newFilters);
      const response = await productService.getProducts(filters.value);
      products.value = response.data || response;
      pagination.value = response.meta || {};
      return response;
    } catch (err) {
      error.value = err.response?.data?.message || "Erro ao carregar produtos";
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function fetchFeatured() {
    try {
      const response = await productService.getFeatured();
      featured.value = response.data || response;
      return response;
    } catch (err) {
      console.error("Erro ao carregar produtos em destaque:", err);
    }
  }

  async function fetchProduct(id) {
    loading.value = true;
    error.value = null;

    try {
      const response = await productService.getProduct(id);
      currentProduct.value = response.data || response;
      return currentProduct.value;
    } catch (err) {
      error.value = err.response?.data?.message || "Produto não encontrado";
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function searchProducts(term) {
    try {
      const response = await productService.searchProducts(term);
      products.value = response.data || response;
      return response;
    } catch (err) {
      error.value = err.response?.data?.message || "Erro ao buscar produtos";
      throw err;
    }
  }

  function setFilters(newFilters) {
    Object.assign(filters.value, newFilters);
  }

  return {
    products,
    featured,
    currentProduct,
    loading,
    error,
    filters,
    pagination,
    hasProducts,
    fetchProducts,
    fetchFeatured,
    fetchProduct,
    searchProducts,
    setFilters,
  };
});
