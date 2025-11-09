import { apiClient } from "@/services/api";

const monitoringService = {
    /**
     * Health check da API
     */
    async getHealth() {
        const response = await apiClient.get("/health");
        return response.data;
    },

    /**
     * Obter métricas do sistema
     */
    async getMetrics() {
        const response = await apiClient.get("/metrics");
        return response.data;
    },
};

const abTestService = {
    /**
     * Obter variante do teste A/B
     */
    async getVariant(testName = null) {
        const response = await apiClient.get("/ab/variant", {
            params: testName ? { test: testName } : {},
        });
        return response.data;
    },

    /**
     * Registrar conversão do teste A/B
     */
    async recordConversion(testName, variant, conversionType = "click") {
        const response = await apiClient.post("/ab/convert", {
            test: testName,
            variant,
            conversion_type: conversionType,
        });
        return response.data;
    },

    /**
     * Obter estatísticas dos testes A/B
     */
    async getStats() {
        const response = await apiClient.get("/ab/stats");
        return response.data;
    },

    /**
     * Obter pesos das variantes (admin)
     */
    async getWeights() {
        const response = await apiClient.get("/ab/weights");
        return response.data;
    },

    /**
     * Definir pesos das variantes (admin)
     */
    async setWeights(weights) {
        const response = await apiClient.post("/ab/weights", { weights });
        return response.data;
    },
};

export { monitoringService, abTestService };
