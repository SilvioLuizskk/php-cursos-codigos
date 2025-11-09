import { computed } from 'vue'
import { useOrderStore } from '@/stores/orders'

export function useOrders() {
  const orderStore = useOrderStore()

  const orders = computed(() => orderStore.orders)
  const currentOrder = computed(() => orderStore.currentOrder)
  const loading = computed(() => orderStore.loading)
  const error = computed(() => orderStore.error)
  const pagination = computed(() => orderStore.pagination)

  async function fetchOrders(page = 1, perPage = 10) {
    return await orderStore.fetchOrders(page, perPage)
  }

  async function fetchOrder(id) {
    return await orderStore.fetchOrder(id)
  }

  async function createOrder(data) {
    return await orderStore.createOrder(data)
  }

  async function cancelOrder(id, reason) {
    return await orderStore.cancelOrder(id, reason)
  }

  return {
    orders,
    currentOrder,
    loading,
    error,
    pagination,
    fetchOrders,
    fetchOrder,
    createOrder,
    cancelOrder,
  }
}