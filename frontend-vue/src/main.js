import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";
import App from "./App.vue";

// Importar páginas
import Home from "./pages/Home.vue";
import Login from "./pages/Login.vue";
import ProductList from "./pages/ProductList.vue";
import Cart from "./pages/Cart.vue";
import NotFound from "./pages/NotFound.vue";

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
app.mount("#app");
