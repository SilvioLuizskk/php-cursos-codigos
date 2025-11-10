import { apiClient } from "@/services/api";

export const adminService = {
    // Dashboard
    async getDashboardStats() {
        const response = await apiClient.get("/admin/dashboard/stats");
        return response.data;
    },

    async getDashboardCharts() {
        const response = await apiClient.get("/admin/dashboard/charts");
        return response.data;
    },

    // Produtos Admin
    async getAdminProducts(params = {}) {
        const response = await apiClient.get("/admin/products", { params });
        return response.data;
    },

    async createProduct(productData) {
        const response = await apiClient.post("/admin/products", productData);
        return response.data;
    },

    async updateProduct(id, productData) {
        const response = await apiClient.put(
            `/admin/products/${id}`,
            productData,
        );
        return response.data;
    },

    async deleteProduct(id) {
        const response = await apiClient.delete(`/admin/products/${id}`);
        return response.data;
    },

    async bulkUpdateProducts(productsData) {
        const response = await apiClient.put(
            "/admin/products/bulk",
            productsData,
        );
        return response.data;
    },

    // Categorias Admin
    async getCategories() {
        const response = await apiClient.get("/admin/categories");
        return response.data;
    },

    async createCategory(categoryData) {
        const response = await apiClient.post(
            "/admin/categories",
            categoryData,
        );
        return response.data;
    },

    async updateCategory(id, categoryData) {
        const response = await apiClient.put(
            `/admin/categories/${id}`,
            categoryData,
        );
        return response.data;
    },

    async deleteCategory(id) {
        const response = await apiClient.delete(`/admin/categories/${id}`);
        return response.data;
    },

    // Banners Admin
    async getBanners() {
        const response = await apiClient.get("/admin/banners");
        return response.data;
    },

    async createBanner(bannerData) {
        const response = await apiClient.post("/admin/banners", bannerData);
        return response.data;
    },

    async updateBanner(id, bannerData) {
        const response = await apiClient.put(
            `/admin/banners/${id}`,
            bannerData,
        );
        return response.data;
    },

    async deleteBanner(id) {
        const response = await apiClient.delete(`/admin/banners/${id}`);
        return response.data;
    },

    // Páginas Admin
    async getPages() {
        const response = await apiClient.get("/admin/pages");
        return response.data;
    },

    async createPage(pageData) {
        const response = await apiClient.post("/admin/pages", pageData);
        return response.data;
    },

    async updatePage(id, pageData) {
        const response = await apiClient.put(`/admin/pages/${id}`, pageData);
        return response.data;
    },

    async deletePage(id) {
        const response = await apiClient.delete(`/admin/pages/${id}`);
        return response.data;
    },

    // Configurações Admin
    async getSettings() {
        const response = await apiClient.get("/admin/settings");
        return response.data;
    },

    async updateSettings(settingsData) {
        const response = await apiClient.put("/admin/settings", settingsData);
        return response.data;
    },

    // Pedidos Admin
    async getAdminOrders(params = {}) {
        const response = await apiClient.get("/admin/orders", { params });
        return response.data;
    },

    async getOrderDetails(id) {
        const response = await apiClient.get(`/admin/orders/${id}`);
        return response.data;
    },

    async updateOrderStatus(id, statusData) {
        const response = await apiClient.put(
            `/admin/orders/${id}/status`,
            statusData,
        );
        return response.data;
    },

    async cancelOrder(id, reason) {
        const response = await apiClient.put(`/admin/orders/${id}/cancel`, {
            reason,
        });
        return response.data;
    },

    // Pedidos Admin (métodos adicionais para compatibilidade com formulários)
    async getOrders(params = {}) {
        return this.getAdminOrders(params);
    },

    async getOrder(id) {
        return this.getOrderDetails(id);
    },

    async updateOrder(id, orderData) {
        const response = await apiClient.put(`/admin/orders/${id}`, orderData);
        return response.data;
    },

    async deleteOrder(id) {
        const response = await apiClient.delete(`/admin/orders/${id}`);
        return response.data;
    },

    // Métricas Admin
    async getMetrics(params = {}) {
        const response = await apiClient.get("/admin/metrics", { params });
        return response.data;
    },

    async getMetricsReport(reportType, dateRange) {
        const response = await apiClient.get("/admin/metrics/report", {
            params: { type: reportType, ...dateRange },
        });
        return response.data;
    },

    // Métricas Admin (método adicional para export)
    async exportReport(params = {}) {
        const response = await apiClient.get("/admin/metrics/export", {
            params,
        });
        return response.data;
    },

    // Usuários Admin
    async getUsers(params = {}) {
        const response = await apiClient.get("/admin/users", { params });
        return response.data;
    },

    async createUser(userData) {
        const response = await apiClient.post("/admin/users", userData);
        return response.data;
    },

    async updateUser(id, userData) {
        const response = await apiClient.put(`/admin/users/${id}`, userData);
        return response.data;
    },

    async deleteUser(id) {
        const response = await apiClient.delete(`/admin/users/${id}`);
        return response.data;
    },

    async toggleUserStatus(id) {
        const response = await apiClient.put(
            `/admin/users/${id}/toggle-status`,
        );
        return response.data;
    },
};
