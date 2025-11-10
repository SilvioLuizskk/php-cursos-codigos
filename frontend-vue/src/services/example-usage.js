// Exemplo de uso dos services em um componente Vue
import { ref, onMounted } from "vue";
import {
    authService,
    productService,
    cartService,
    userService,
    pageService,
    adminService,
    checkoutService,
    abTestService,
} from "@/services";

export default {
    name: "ExampleComponent",
    setup() {
        const products = ref([]);
        const user = ref(null);
        const cart = ref([]);
        const homeData = ref(null);

        // Carregar dados da página inicial
        const loadHomeData = async () => {
            try {
                homeData.value = await pageService.getHomeData();
            } catch (error) {
                console.error("Erro ao carregar dados da home:", error);
            }
        };

        // Carregar produtos
        const loadProducts = async () => {
            try {
                const response = await productService.getProducts({
                    page: 1,
                    limit: 20,
                    category: "all",
                });
                products.value = response.data;
            } catch (error) {
                console.error("Erro ao carregar produtos:", error);
            }
        };

        // Adicionar produto ao carrinho
        const addToCart = async (productId, quantity = 1) => {
            try {
                await cartService.addItem(productId, quantity);
                // Recarregar carrinho
                await loadCart();
            } catch (error) {
                console.error("Erro ao adicionar ao carrinho:", error);
            }
        };

        // Carregar carrinho
        const loadCart = async () => {
            try {
                const response = await cartService.getCart();
                cart.value = response.items;
            } catch (error) {
                console.error("Erro ao carregar carrinho:", error);
            }
        };

        // Carregar perfil do usuário
        const loadUserProfile = async () => {
            try {
                user.value = await userService.getProfile();
            } catch (error) {
                console.error("Erro ao carregar perfil:", error);
            }
        };

        // Enviar formulário de contato
        const sendContactForm = async (contactData) => {
            try {
                await pageService.sendContactForm(contactData);
                alert("Mensagem enviada com sucesso!");
            } catch (error) {
                console.error("Erro ao enviar contato:", error);
            }
        };

        // Exemplo de uso do checkout
        const initiateCheckout = async () => {
            try {
                const checkoutData = await checkoutService.initiateCheckout(
                    cart.value,
                );
                // Redirecionar para página de checkout
                console.log("Checkout iniciado:", checkoutData);
            } catch (error) {
                console.error("Erro ao iniciar checkout:", error);
            }
        };

        // Exemplo de uso do admin service (apenas para admins)
        const loadAdminDashboard = async () => {
            try {
                const stats = await adminService.getDashboardStats();
                console.log("Estatísticas do dashboard:", stats);
            } catch (error) {
                console.error("Erro ao carregar dashboard admin:", error);
            }
        };

        // Exemplo de uso do A/B testing
        const loadABDashboard = async () => {
            try {
                const abData = await abTestService.getDashboardData();
                console.log("Dados A/B:", abData);
            } catch (error) {
                console.error("Erro ao carregar dashboard A/B:", error);
            }
        };

        onMounted(() => {
            loadHomeData();
            loadProducts();
            loadCart();

            // Se usuário estiver logado
            if (localStorage.getItem("auth_token")) {
                loadUserProfile();
            }
        });

        return {
            products,
            user,
            cart,
            homeData,
            loadProducts,
            addToCart,
            sendContactForm,
            initiateCheckout,
            loadAdminDashboard,
            loadABDashboard,
        };
    },
};
