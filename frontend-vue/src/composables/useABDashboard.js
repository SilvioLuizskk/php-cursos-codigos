import { ref, computed } from "vue";
import { apiClient } from "@/services/api";
import { useNotification } from "@/composables/useNotification";

export function useABDashboard() {
    const { showNotification } = useNotification();

    const dashboardData = ref(null);
    const loading = ref(false);
    const error = ref(null);

    // Buscar dados do dashboard A/B
    async function fetchDashboardData() {
        loading.value = true;
        error.value = null;

        try {
            const response = await apiClient.get("/ab-dashboard");
            dashboardData.value = response.data;
            return dashboardData.value;
        } catch (err) {
            error.value =
                err.response?.data?.message ||
                "Erro ao carregar dados do dashboard A/B";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    return {
        dashboardData: computed(() => dashboardData.value),
        activeTests: computed(() => dashboardData.value?.active_tests || []),
        testStats: computed(() => dashboardData.value?.test_stats || []),
        conversionRates: computed(
            () => dashboardData.value?.conversion_rates || [],
        ),
        summary: computed(() => dashboardData.value?.summary || null),
        loading: computed(() => loading.value),
        error: computed(() => error.value),
        fetchDashboardData,
    };
}
