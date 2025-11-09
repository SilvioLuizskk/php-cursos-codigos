import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import App from "./App.vue";
import "./style.css";

// Limpar qualquer referência ao CDN do Tailwind que possa estar cacheada
if (typeof window !== "undefined") {
    // Remove any dynamically loaded Tailwind CDN scripts
    const tailwindScripts = document.querySelectorAll(
        'script[src*="cdn.tailwindcss.com"]',
    );
    tailwindScripts.forEach((script) => {
        console.warn(
            "Removendo script CDN do Tailwind encontrado:",
            script.src,
        );
        script.remove();
    });

    // Clear any cached Tailwind styles
    const styleSheets = document.styleSheets;
    for (let i = styleSheets.length - 1; i >= 0; i--) {
        const sheet = styleSheets[i];
        if (sheet.href && sheet.href.includes("cdn.tailwindcss.com")) {
            console.warn(
                "Desabilitando stylesheet CDN do Tailwind:",
                sheet.href,
            );
            sheet.disabled = true;
        }
    }

    // Monitor for any new CDN scripts being added
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (
                    node.tagName === "SCRIPT" &&
                    node.src &&
                    node.src.includes("cdn.tailwindcss.com")
                ) {
                    console.error(
                        "Tentativa de carregar CDN do Tailwind detectada e bloqueada:",
                        node.src,
                    );
                    node.remove();
                }
            });
        });
    });

    observer.observe(document.head, { childList: true });
}

// Importar páginas
import Home from "./pages/Home.vue";
import Login from "./pages/Login.vue";
import Register from "./pages/Register.vue";
import ForgotPassword from "./pages/ForgotPassword.vue";
import ProductList from "./pages/ProductList.vue";
import Cart from "./pages/Cart.vue";
import NotFound from "./pages/NotFound.vue";
import ProductDetail from "./pages/ProductDetail.vue";
import Checkout from "./pages/Checkout.vue";
import ABDashboard from "./pages/ABDashboard.vue";
import AdminAB from "./pages/AdminAB.vue";
import AdminProducts from "./pages/AdminProducts.vue";
import HealthCheck from "./pages/HealthCheck.vue";
import Metrics from "./pages/Metrics.vue";
import UserOrders from "./pages/UserOrders.vue";
import OrderDetail from "./pages/OrderDetail.vue";
import AdminDashboard from "./pages/AdminDashboard.vue";

// Configurar rotas
const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
        meta: { title: "Início - EstampariaPro" },
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: { title: "Login - EstampariaPro" },
    },
    {
        path: "/registrar",
        name: "Register",
        component: Register,
        meta: { title: "Registrar - EstampariaPro" },
    },
    {
        path: "/esqueci-senha",
        name: "ForgotPassword",
        component: ForgotPassword,
        meta: { title: "Esqueci a Senha - EstampariaPro" },
    },
    {
        path: "/produtos",
        name: "ProductList",
        component: ProductList,
        meta: { title: "Produtos - EstampariaPro" },
    },
    {
        path: "/carrinho",
        name: "Cart",
        component: Cart,
        meta: { title: "Carrinho - EstampariaPro" },
    },
    {
        path: "/produto/:slug",
        name: "ProductDetail",
        component: ProductDetail,
        meta: { title: "Detalhes do Produto - EstampariaPro" },
    },
    {
        path: "/checkout",
        name: "Checkout",
        component: Checkout,
        meta: { title: "Checkout - EstampariaPro" },
    },
    {
        path: "/pedidos",
        name: "UserOrders",
        component: UserOrders,
        meta: { title: "Meus Pedidos - EstampariaPro" },
    },
    {
        path: "/pedidos/:id",
        name: "OrderDetail",
        component: OrderDetail,
        meta: { title: "Detalhes do Pedido - EstampariaPro" },
    },
    {
        path: "/health",
        name: "HealthCheck",
        component: HealthCheck,
        meta: { title: "Health Check - EstampariaPro" },
    },
    {
        path: "/metrics",
        name: "Metrics",
        component: Metrics,
        meta: { title: "Métricas - EstampariaPro" },
    },
    {
        path: "/ab-dashboard",
        name: "ABDashboard",
        component: ABDashboard,
        meta: { title: "A/B Dashboard - EstampariaPro" },
    },
    {
        path: "/admin",
        name: "AdminDashboard",
        component: AdminDashboard,
        children: [
            {
                path: "home",
                name: "AdminHome",
                component: () => import("./pages/AdminHome.vue"),
            },
            {
                path: "produtos",
                name: "AdminProducts",
                component: () => import("./pages/AdminProducts.vue"),
            },
            {
                path: "categorias",
                name: "AdminCategorias",
                component: () => import("./pages/AdminCategorias.vue"),
            },
            {
                path: "banners",
                name: "AdminBanners",
                component: () => import("./pages/AdminBanners.vue"),
            },
            {
                path: "paginas",
                name: "AdminPaginas",
                component: () => import("./pages/AdminPaginas.vue"),
            },
            {
                path: "configuracoes",
                name: "AdminConfiguracoes",
                component: () => import("./pages/AdminConfiguracoes.vue"),
            },
            {
                path: "pedidos",
                name: "AdminPedidos",
                component: () => import("./pages/AdminPedidos.vue"),
            },
            {
                path: "metricas",
                name: "AdminMetricas",
                component: () => import("./pages/AdminMetricas.vue"),
            },
        ],
    },
    {
        path: "/:pathMatch(.*)*",
        name: "NotFound",
        component: NotFound,
        meta: { title: "Página não encontrada - EstampariaPro" },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Atualizar título da página
router.beforeEach((to) => {
    document.title = to.meta.title || "EstampariaPro";
});

const app = createApp(App);
app.use(router);

// Initialize Sentry if DSN provided (dynamic import so dependency is optional)
const sentryDsn = import.meta.env.VITE_SENTRY_DSN || "";
if (sentryDsn) {
    import("@sentry/vue")
        .then((Sentry) => {
            try {
                Sentry.init({
                    app,
                    dsn: sentryDsn,
                    integrations: [],
                    tracesSampleRate: Number(
                        import.meta.env.VITE_SENTRY_TRACES || 0,
                    ),
                });
                console.info("Sentry initialized");
            } catch (e) {
                console.warn("Sentry init failed", e);
            }
        })
        .catch((e) => {
            console.warn("Sentry package not available", e);
        });
}

app.mount("#app");
