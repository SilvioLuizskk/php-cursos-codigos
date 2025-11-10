import { apiClient } from "@/services/api";

export const abTestService = {
    /**
     * Obter dados do dashboard A/B
     */
    async getDashboardData() {
        const response = await apiClient.get("/ab/dashboard");
        return response.data;
    },

    /**
     * Obter lista de testes A/B
     */
    async getTests(params = {}) {
        const response = await apiClient.get("/ab/tests", { params });
        return response.data;
    },

    /**
     * Criar novo teste A/B
     */
    async createTest(testData) {
        const response = await apiClient.post("/ab/tests", testData);
        return response.data;
    },

    /**
     * Obter detalhes de um teste A/B
     */
    async getTest(id) {
        const response = await apiClient.get(`/ab/tests/${id}`);
        return response.data;
    },

    /**
     * Atualizar teste A/B
     */
    async updateTest(id, testData) {
        const response = await apiClient.put(`/ab/tests/${id}`, testData);
        return response.data;
    },

    /**
     * Excluir teste A/B
     */
    async deleteTest(id) {
        const response = await apiClient.delete(`/ab/tests/${id}`);
        return response.data;
    },

    /**
     * Iniciar teste A/B
     */
    async startTest(id) {
        const response = await apiClient.put(`/ab/tests/${id}/start`);
        return response.data;
    },

    /**
     * Pausar teste A/B
     */
    async pauseTest(id) {
        const response = await apiClient.put(`/ab/tests/${id}/pause`);
        return response.data;
    },

    /**
     * Finalizar teste A/B
     */
    async finishTest(id) {
        const response = await apiClient.put(`/ab/tests/${id}/finish`);
        return response.data;
    },

    /**
     * Obter resultados de um teste A/B
     */
    async getTestResults(id, params = {}) {
        const response = await apiClient.get(`/ab/tests/${id}/results`, {
            params,
        });
        return response.data;
    },

    /**
     * Obter estatísticas de conversão
     */
    async getConversionStats(testId, variantId) {
        const response = await apiClient.get(
            `/ab/tests/${testId}/variants/${variantId}/stats`,
        );
        return response.data;
    },

    /**
     * Obter relatório de performance
     */
    async getPerformanceReport(params = {}) {
        const response = await apiClient.get("/ab/performance", { params });
        return response.data;
    },

    /**
     * Exportar dados do teste
     */
    async exportTestData(id, format = "csv") {
        const response = await apiClient.get(`/ab/tests/${id}/export`, {
            params: { format },
            responseType: "blob",
        });
        return response.data;
    },

    /**
     * Obter configurações do A/B testing
     */
    async getSettings() {
        const response = await apiClient.get("/ab/settings");
        return response.data;
    },

    /**
     * Atualizar configurações do A/B testing
     */
    async updateSettings(settingsData) {
        const response = await apiClient.put("/ab/settings", settingsData);
        return response.data;
    },
};
