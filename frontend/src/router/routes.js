export default [
  // ========== ROTAS PÚBLICAS ==========

  {
    path: "/",
    name: "Home",
    component: () => import("@/pages/public/Home.vue"),
  },

  {
    path: "/produtos",
    name: "ProductList",
    component: () => import("@/pages/public/ProductList.vue"),
  },

  {
    path: "/produtos/:id",
    name: "ProductDetail",
    component: () => import("@/pages/public/ProductDetail.vue"),
  },

  // ========== ROTAS DE AUTENTICAÇÃO ==========

  {
    path: "/login",
    name: "Login",
    component: () => import("@/pages/auth/Login.vue"),
  },

  {
    path: "/registrar",
    name: "Register",
    component: () => import("@/pages/auth/Register.vue"),
  },

  {
    path: "/esqueci-senha",
    name: "ForgotPassword",
    component: () => import("@/pages/auth/ForgotPassword.vue"),
  },

  // ========== ROTAS DE COMPRA ==========

  {
    path: "/carrinho",
    name: "Cart",
    component: () => import("@/pages/shop/Cart.vue"),
  },

  {
    path: "/checkout",
    name: "Checkout",
    component: () => import("@/pages/shop/Checkout.vue"),
    meta: { requiresAuth: true },
  },

  {
    path: "/checkout/sucesso",
    name: "CheckoutSuccess",
    component: () => import("@/pages/shop/CheckoutSuccess.vue"),
    meta: { requiresAuth: true },
  },

  // ========== ROTAS DO USUÁRIO ==========

  {
    path: "/minha-conta",
    name: "Dashboard",
    component: () => import("@/pages/user/Dashboard.vue"),
    meta: { requiresAuth: true },
  },

  {
    path: "/minha-conta/perfil",
    name: "Profile",
    component: () => import("@/pages/user/Profile.vue"),
    meta: { requiresAuth: true },
  },

  {
    path: "/minha-conta/pedidos",
    name: "Orders",
    component: () => import("@/pages/user/Orders.vue"),
    meta: { requiresAuth: true },
  },

  {
    path: "/minha-conta/pedidos/:id",
    name: "OrderDetail",
    component: () => import("@/pages/user/OrderDetail.vue"),
    meta: { requiresAuth: true },
  },

  {
    path: "/minha-conta/favoritos",
    name: "Wishlist",
    component: () => import("@/pages/user/Wishlist.vue"),
    meta: { requiresAuth: true },
  },

  // ========== ROTAS DE ADMIN ==========

  {
    path: "/admin",
    name: "AdminDashboard",
    component: () => import("@/pages/admin/Dashboard.vue"),
    meta: { requiresAdmin: true },
  },

  {
    path: "/admin/produtos",
    name: "AdminProducts",
    component: () => import("@/pages/admin/Products.vue"),
    meta: { requiresAdmin: true },
  },

  {
    path: "/admin/pedidos",
    name: "AdminOrders",
    component: () => import("@/pages/admin/Orders.vue"),
    meta: { requiresAdmin: true },
  },

  // ========== ROTA 404 ==========

  {
    path: "/:pathMatch(.*)*",
    name: "NotFound",
    component: () => import("@/pages/public/NotFound.vue"),
  },
];
