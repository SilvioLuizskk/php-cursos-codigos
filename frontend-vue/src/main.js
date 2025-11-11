import { createApp } from "vue";
import { createPinia } from "pinia";
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

// Importar layout do admin
import AdminLayout from "./components/AdminLayout.vue";

// Importar páginas (apenas as que não são lazy-loaded)
import Home from "./pages/Home.vue";
import ClientHome from "./pages/ClientHome.vue";
import Login from "./pages/Login.vue";
import AdminLogin from "./pages/AdminLogin.vue";
import Register from "./pages/Register.vue";
import ForgotPassword from "./pages/ForgotPassword.vue";
import ProductList from "./pages/ProductList.vue";
import Cart from "./pages/Cart.vue";
import NotFound from "./pages/NotFound.vue";
import ProductDetail from "./pages/ProductDetail.vue";
import Checkout from "./pages/Checkout.vue";
import Contact from "./pages/Contact.vue";
import ABDashboard from "./pages/ABDashboard.vue";
import AdminAB from "./pages/AdminAB.vue";
import HealthCheck from "./pages/HealthCheck.vue";
import Metrics from "./pages/Metrics.vue";
import UserOrders from "./pages/UserOrders.vue";
import OrderDetail from "./pages/OrderDetail.vue";

// Importar componentes admin (removendo lazy loading temporariamente)
import AdminDashboard from "./pages/AdminDashboard.vue";
import AdminProducts from "./pages/AdminProducts.vue";
import AdminCategorias from "./pages/AdminCategorias.vue";
import AdminBanners from "./pages/AdminBanners.vue";
import AdminPaginas from "./pages/AdminPaginas.vue";
import AdminConfiguracoes from "./pages/AdminConfiguracoes.vue";
import AdminPedidos from "./pages/AdminPedidos.vue";
import AdminMetricas from "./pages/AdminMetricas.vue";

// Configurar rotas
const routes = [
    {
        path: "/",
        redirect: "/cliente",
        meta: { title: "Início - EstampariaPro" },
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: { title: "Login - EstampariaPro" },
    },
    {
        path: "/admin-login",
        name: "AdminLogin",
        component: AdminLogin,
        meta: { title: "Login Admin - EstampariaPro" },
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
        path: "/contato",
        name: "Contact",
        component: Contact,
        meta: { title: "Contato - EstampariaPro" },
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
        path: "/comunicacao-vertical",
        name: "ComunicacaoVertical",
        component: () => import("./components/ComunicacaoVerticalDemo.vue"),
        meta: { title: "Comunicação Vertical - EstampariaPro" },
    },
    {
        path: "/cliente",
        name: "ClientHome",
        component: ClientHome,
        meta: { title: "Cliente - EstampariaPro" },
    },
    {
        path: "/ab-dashboard",
        name: "ABDashboard",
        component: ABDashboard,
        meta: { title: "A/B Dashboard - EstampariaPro" },
    },
    {
        path: "/admin",
        component: AdminLayout,
        children: [
            {
                path: "/admin",
                name: "AdminDashboard",
                component: AdminDashboard,
                meta: { title: "Admin Dashboard - EstampariaPro" },
            },
            {
                path: "/admin/produtos",
                name: "AdminProducts",
                component: AdminProducts,
                meta: { title: "Admin Produtos - EstampariaPro" },
            },
            {
                path: "/admin/categorias",
                name: "AdminCategorias",
                component: AdminCategorias,
                meta: { title: "Admin Categorias - EstampariaPro" },
            },
            {
                path: "/admin/banners",
                name: "AdminBanners",
                component: AdminBanners,
                meta: { title: "Admin Banners - EstampariaPro" },
            },
            {
                path: "/admin/paginas",
                name: "AdminPaginas",
                component: AdminPaginas,
                meta: { title: "Admin Páginas - EstampariaPro" },
            },
            {
                path: "/admin/configuracoes",
                name: "AdminConfiguracoes",
                component: AdminConfiguracoes,
                meta: { title: "Admin Configurações - EstampariaPro" },
            },
            {
                path: "/admin/pedidos",
                name: "AdminPedidos",
                component: AdminPedidos,
                meta: { title: "Admin Pedidos - EstampariaPro" },
            },
            {
                path: "/admin/metricas",
                name: "AdminMetricas",
                component: AdminMetricas,
                meta: { title: "Admin Métricas - EstampariaPro" },
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

import { useAuth } from "@/composables/useAuth";
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Atualizar título da página
router.beforeEach(async (to, from, next) => {
    document.title = to.meta.title || "EstampariaPro";

    // Proteger rotas admin - TEMPORARIAMENTE DESABILITADO PARA TESTE
    // if (to.path.startsWith("/admin")) {
    //     const token = localStorage.getItem("auth_token");
    //     const userStr = localStorage.getItem("auth_user");
    //     let user = null;
    //     try {
    //         user = userStr ? JSON.parse(userStr) : null;
    //     } catch (e) {}
    //     if (!token || !user || !user.is_admin) {
    //         return next({
    //             path: "/admin-login",
    //             query: { redirect: to.fullPath },
    //         });
    //     }
    // }
    next();
});

// Debug: log app start to help diagnose mount issues
console.log("[app] starting - main.js");
const app = createApp(App);
// Registrar Pinia (store) globalmente antes de montar a aplicação
const pinia = createPinia();
app.use(pinia);
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

try {
    app.mount("#app");
    console.log("[app] mounted successfully");
} catch (err) {
    // Ensure runtime mount errors are visible in the browser console
    console.error("[app] mount error", err);
    // Re-throw so dev tooling can pick it up
    throw err;
}
