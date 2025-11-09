import { ref, computed } from "vue";
import { apiClient } from "@/services/api";
import { useNotification } from "@/composables/useNotification";

export function useHome() {
    const { showNotification } = useNotification();

    const homeData = ref(null);
    const loading = ref(false);
    const error = ref(null);

    // Buscar dados da página inicial
    async function fetchHomeData() {
        loading.value = true;
        error.value = null;

        try {
            const response = await apiClient.get("/home");
            homeData.value = response.data;
            return homeData.value;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao carregar dados da home";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    return {
        homeData: computed(() => homeData.value),
        featuredProducts: computed(
            () => homeData.value?.featured_products || [],
        ),
        categories: computed(() => homeData.value?.categories || []),
        stats: computed(() => homeData.value?.stats || null),
        banners: computed(() => homeData.value?.banners || []),
        loading: computed(() => loading.value),
        error: computed(() => error.value),
        fetchHomeData,
    };
}
