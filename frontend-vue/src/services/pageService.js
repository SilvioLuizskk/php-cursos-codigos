import { apiClient } from "@/services/api";

const pageService = {
    /**
     * Obter lista de páginas
     */
    async getPages(params = {}) {
        const response = await apiClient.get("/pages", { params });
        return response.data;
    },

    /**
     * Obter página por ID
     */
    async getPage(id) {
        const response = await apiClient.get(`/pages/${id}`);
        return response.data;
    },

    /**
     * Criar nova página (admin)
     */
    async createPage(pageData) {
        const response = await apiClient.post("/pages", pageData);
        return response.data;
    },

    /**
     * Atualizar página (admin)
     */
    async updatePage(id, pageData) {
        const response = await apiClient.put(`/pages/${id}`, pageData);
        return response.data;
    },

    /**
     * Deletar página (admin)
     */
    async deletePage(id) {
        const response = await apiClient.delete(`/pages/${id}`);
        return response.data;
    },

    /**
     * Obter página por slug
     */
    async getPageBySlug(slug) {
        const response = await apiClient.get(`/pages/slug/${slug}`);
        return response.data;
    },
};

export default pageService;
