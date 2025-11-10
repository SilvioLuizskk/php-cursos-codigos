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

    /**
     * Obter dados da página inicial
     */
    async getHomeData() {
        const response = await apiClient.get("/home");
        return response.data;
    },

    /**
     * Obter dados da página de contato
     */
    async getContactData() {
        const response = await apiClient.get("/contact");
        return response.data;
    },

    /**
     * Enviar formulário de contato
     */
    async sendContactForm(contactData) {
        const response = await apiClient.post("/contact", contactData);
        return response.data;
    },

    /**
     * Obter dados da página Sobre
     */
    async getAboutData() {
        const response = await apiClient.get("/about");
        return response.data;
    },

    /**
     * Obter dados da página de FAQ
     */
    async getFAQData() {
        const response = await apiClient.get("/faq");
        return response.data;
    },

    /**
     * Obter dados da página de Política de Privacidade
     */
    async getPrivacyPolicyData() {
        const response = await apiClient.get("/privacy-policy");
        return response.data;
    },

    /**
     * Obter dados da página de Termos de Serviço
     */
    async getTermsOfServiceData() {
        const response = await apiClient.get("/terms-of-service");
        return response.data;
    },

    /**
     * Obter dados da página de Política de Devolução
     */
    async getReturnPolicyData() {
        const response = await apiClient.get("/return-policy");
        return response.data;
    },

    /**
     * Obter dados da página de Política de Envio
     */
    async getShippingPolicyData() {
        const response = await apiClient.get("/shipping-policy");
        return response.data;
    },

    /**
     * Obter configurações gerais do site
     */
    async getSiteSettings() {
        const response = await apiClient.get("/settings");
        return response.data;
    },

    /**
     * Obter menu de navegação
     */
    async getNavigationMenu() {
        const response = await apiClient.get("/navigation");
        return response.data;
    },

    /**
     * Obter rodapé do site
     */
    async getFooterData() {
        const response = await apiClient.get("/footer");
        return response.data;
    },

    /**
     * Inscrever na newsletter
     */
    async subscribeNewsletter(subscriptionData) {
        const response = await apiClient.post(
            "/newsletter/subscribe",
            subscriptionData,
        );
        return response.data;
    },
};

export default pageService;
