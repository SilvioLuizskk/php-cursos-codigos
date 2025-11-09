import { computed } from 'vue'
import { useProductStore } from '@/stores/products'

export function useProducts() {
  const productStore = useProductStore()

  const products = computed(() => productStore.products)
  const featured = computed(() => productStore.featured)
  const currentProduct = computed(() => productStore.currentProduct)
  const loading = computed(() => productStore.loading)
  const error = computed(() => productStore.error)
  const filters = computed(() => productStore.filters)
  const pagination = computed(() => productStore.pagination)

  async function fetchProducts(newFilters = {}) {
    return await productStore.fetchProducts(newFilters)
  }

  async function fetchFeatured() {
    return await productStore.fetchFeatured()
  }

  async function fetchProduct(id) {
    return await productStore.fetchProduct(id)
  }

  async function searchProducts(term) {
    return await productStore.searchProducts(term)
  }

  function setFilters(newFilters) {
    productStore.setFilters(newFilters)
  }

  return {
    products,
    featured,
    currentProduct,
    loading,
    error,
    filters,
    pagination,
    fetchProducts,
    fetchFeatured,
    fetchProduct,
    searchProducts,
    setFilters,
  }
}