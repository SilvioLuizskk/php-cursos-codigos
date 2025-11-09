import { ref, computed } from "vue";
import { orderService } from "@/services/orderService";
import { useNotification } from "@/composables/useNotification";

export function useOrders() {
    const { showNotification } = useNotification();

    const orders = ref([]);
    const currentOrder = ref(null);
    const orderStats = ref(null);
    const loading = ref(false);
    const error = ref(null);

    // Buscar pedidos do usuário
    async function fetchOrders(params = {}) {
        loading.value = true;
        error.value = null;

        try {
            const response = await orderService.getOrders(params);
            orders.value = response.data || response;
            return orders.value;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao buscar pedidos";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Buscar estatísticas dos pedidos
    async function fetchOrderStats() {
        loading.value = true;
        error.value = null;

        try {
            const response = await orderService.getOrderStats();
            orderStats.value = response.data || response;
            return orderStats.value;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao buscar estatísticas";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Criar novo pedido
    async function createOrder(orderData) {
        loading.value = true;
        error.value = null;

        try {
            const response = await orderService.createOrder(orderData);
            showNotification("Pedido criado com sucesso!", "success");
            return response.data || response;
        } catch (err) {
            error.value = err.response?.data?.message || "Erro ao criar pedido";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Buscar detalhes do pedido
    async function fetchOrder(orderId) {
        loading.value = true;
        error.value = null;

        try {
            const response = await orderService.getOrder(orderId);
            currentOrder.value = response.data || response;
            return currentOrder.value;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao buscar pedido";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Cancelar pedido
    async function cancelOrder(orderId) {
        loading.value = true;
        error.value = null;

        try {
            const response = await orderService.cancelOrder(orderId);
            showNotification("Pedido cancelado com sucesso!", "success");
            return response.data || response;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao cancelar pedido";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    // Buscar rastreamento do pedido
    async function fetchOrderTracking(orderId) {
        loading.value = true;
        error.value = null;

        try {
            const response = await orderService.getOrderTracking(orderId);
            return response.data || response;
        } catch (err) {
            error.value =
                err.response?.data?.message || "Erro ao buscar rastreamento";
            showNotification(error.value, "error");
            throw err;
        } finally {
            loading.value = false;
        }
    }

    return {
        orders: computed(() => orders.value),
        currentOrder: computed(() => currentOrder.value),
        orderStats: computed(() => orderStats.value),
        loading: computed(() => loading.value),
        error: computed(() => error.value),
        fetchOrders,
        fetchOrderStats,
        createOrder,
        fetchOrder,
        cancelOrder,
        fetchOrderTracking,
    };
}
