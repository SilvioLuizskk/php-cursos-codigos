import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { orderService } from "@/api/orderService";

export const useOrderStore = defineStore("orders", () => {
  // Estado
  const orders = ref([]);
  const currentOrder = ref(null);
  const loading = ref(false);
  const error = ref(null);
  const pagination = ref({
    total: 0,
    per_page: 10,
    current_page: 1,
    last_page: 1,
  });

  // Computados
  const hasOrders = computed(() => orders.value.length > 0);

  // Ações
  async function fetchOrders(page = 1, perPage = 10) {
    loading.value = true;
    error.value = null;

    try {
      const response = await orderService.getOrders(page, perPage);
      orders.value = response.data || response;
      pagination.value = response.meta || {};
      return response;
    } catch (err) {
      error.value = err.response?.data?.message || "Erro ao carregar pedidos";
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function fetchOrder(id) {
    loading.value = true;
    error.value = null;

    try {
      const response = await orderService.getOrder(id);
      currentOrder.value = response.data || response;
      return currentOrder.value;
    } catch (err) {
      error.value = err.response?.data?.message || "Pedido não encontrado";
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function createOrder(data) {
    loading.value = true;
    error.value = null;

    try {
      const response = await orderService.createOrder(data);
      currentOrder.value = response.order || response;
      return response;
    } catch (err) {
      error.value = err.response?.data?.message || "Erro ao criar pedido";
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function cancelOrder(id, reason = null) {
    try {
      const response = await orderService.cancelOrder(id, reason);
      await fetchOrder(id);
      return response;
    } catch (err) {
      error.value = err.response?.data?.message || "Erro ao cancelar pedido";
      throw err;
    }
  }

  return {
    orders,
    currentOrder,
    loading,
    error,
    pagination,
    hasOrders,
    fetchOrders,
    fetchOrder,
    createOrder,
    cancelOrder,
  };
});
