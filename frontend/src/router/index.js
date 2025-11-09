import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import routes from "./routes";

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0 };
    }
  },
});

// Guards de navegação
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // Se a rota requer autenticação
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: "Login", query: { redirect: to.fullPath } });
    return;
  }

  // Se a rota requer admin
  if (to.meta.requiresAdmin && !authStore.isAdmin) {
    next({ name: "Home" });
    return;
  }

  // Se está autenticado e tenta acessar login/register
  if (
    (to.name === "Login" || to.name === "Register") &&
    authStore.isAuthenticated
  ) {
    next({ name: "Home" });
    return;
  }

  next();
});

export default router;
