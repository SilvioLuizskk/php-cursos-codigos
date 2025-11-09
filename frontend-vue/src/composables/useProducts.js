import { ref, computed } from "vue";
import productService from "@/services/productService";
import { useNotification } from "@/composables/useNotification";

export function useProducts() {
    const { showNotification } = useNotification();

    const products = ref([]);
    const featuredProducts = ref([]);
    const currentProduct = ref(null);
    const loading = ref(false);
    const error = ref(null);

    // Buscar produtos
    async function fetchProducts(params = {}) {
        loading.value = true;
        error.value = null;

        try {
            const response = await productService.getProducts(params);
            products.value = response.data || response;
            return products.value;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao buscar produtos";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Buscar produto por ID/slug
    async function fetchProduct(idOrSlug) {
        loading.value = true;
        error.value = null;

        try {
            const response = await productService.getProduct(idOrSlug);
            currentProduct.value = response.data || response;
            return currentProduct.value;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao buscar produto";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Buscar produtos em destaque
    async function fetchFeaturedProducts() {
        loading.value = true;
        error.value = null;

        try {
            const response = await productService.getFeaturedProducts();
            featuredProducts.value = response.data || response;
            return featuredProducts.value;
        } catch (err) {
            error.value =
                err.response?.data?.message ||
                "Erro ao buscar produtos em destaque";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Buscar produtos
    async function searchProducts(query, params = {}) {
        loading.value = true;
        error.value = null;

        try {
            const response = await productService.searchProducts(query, params);
            products.value = response.data || response;
            return products.value;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao buscar produtos";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Criar produto (admin)
    async function createProduct(productData) {
        loading.value = true;
        error.value = null;

        try {
            const response = await productService.createProduct(productData);
            showNotification("Produto criado com sucesso!", "success");
            return response.data || response;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao criar produto";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Atualizar produto (admin)
    async function updateProduct(id, productData) {
        loading.value = true;
        error.value = null;

        try {
            const response = await productService.updateProduct(
                id,
                productData,
            );
            showNotification("Produto atualizado com sucesso!", "success");
            return response.data || response;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao atualizar produto";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Deletar produto (admin)
    async function deleteProduct(id) {
        loading.value = true;
        error.value = null;

        try {
            const response = await productService.deleteProduct(id);
            showNotification("Produto deletado com sucesso!", "success");
            return response.data || response;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao deletar produto";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    return {
        products: computed(() => products.value),
        featuredProducts: computed(() => featuredProducts.value),
        currentProduct: computed(() => currentProduct.value),
        loading: computed(() => loading.value),
        error: computed(() => error.value),
        fetchProducts,
        fetchProduct,
        fetchFeaturedProducts,
        searchProducts,
        createProduct,
        updateProduct,
        deleteProduct,
    };
}
