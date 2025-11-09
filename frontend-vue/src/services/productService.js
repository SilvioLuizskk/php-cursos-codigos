import { apiClient } from "@/services/api";

const productService = {
    /**
     * Obter lista de produtos
     */
    async getProducts(params = {}) {
        const response = await apiClient.get("/products", { params });
        return response.data;
    },

    /**
     * Obter produto por ID
     */
    async getProduct(id) {
        const response = await apiClient.get(`/products/${id}`);
        return response.data;
    },

    /**
     * Criar novo produto (admin)
     */
    async createProduct(productData) {
        const response = await apiClient.post("/products", productData);
        return response.data;
    },

    /**
     * Atualizar produto (admin)
     */
    async updateProduct(id, productData) {
        const response = await apiClient.put(`/products/${id}`, productData);
        return response.data;
    },

    /**
     * Deletar produto (admin)
     */
    async deleteProduct(id) {
        const response = await apiClient.delete(`/products/${id}`);
        return response.data;
    },

    /**
     * Obter produtos em destaque
     */
    async getFeaturedProducts() {
        const response = await apiClient.get("/products/featured");
        return response.data;
    },

    /**
     * Buscar produtos
     */
    async searchProducts(query, params = {}) {
        const response = await apiClient.get("/products/search", {
            params: { q: query, ...params },
        });
        return response.data;
    },

    /**
     * Obter produtos por categoria
     */
    async getProductsByCategory(categoryId, params = {}) {
        const response = await apiClient.get(
            `/categories/${categoryId}/products`,
            { params },
        );
        return response.data;
    },

    /**
     * Upload de imagem (admin)
     */
    async uploadImage(file) {
        const formData = new FormData();
        formData.append("image", file);
        // Opcional: formData.append('folder', 'products');
        const response = await apiClient.post("/upload/image", formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        return response.data;
    },
};

export default productService;
