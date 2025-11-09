import { computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

export function useAuth() {
  const authStore = useAuthStore();
  const router = useRouter();

  const user = computed(() => authStore.user);
  const isAuthenticated = computed(() => authStore.isAuthenticated);
  const isAdmin = computed(() => authStore.isAdmin);
  const loading = computed(() => authStore.loading);
  const error = computed(() => authStore.error);

  async function login(email, password) {
    try {
      await authStore.login(email, password);
      router.push({ name: "Home" });
    } catch (err) {
      throw err;
    }
  }

  async function register(data) {
    try {
      await authStore.register(data);
      router.push({ name: "Home" });
    } catch (err) {
      throw err;
    }
  }

  async function logout() {
    await authStore.logout();
    router.push({ name: "Login" });
  }

  async function fetchMe() {
    try {
      await authStore.fetchMe();
    } catch (err) {
      console.error("Erro ao carregar usuário:", err);
    }
  }

  return {
    user,
    isAuthenticated,
    isAdmin,
    loading,
    error,
    login,
    register,
    logout,
    fetchMe,
  };
}
