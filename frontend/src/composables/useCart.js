import { computed } from "vue";
import { useCartStore } from "@/stores/cart";

export function useCart() {
  const cartStore = useCartStore();

  const items = computed(() => cartStore.items);
  const itemCount = computed(() => cartStore.itemCount);
  const subtotal = computed(() => cartStore.subtotal);
  const shippingCost = computed(() => cartStore.shippingCost);
  const discountAmount = computed(() => cartStore.discountAmount);
  const total = computed(() => cartStore.total);
  const isEmpty = computed(() => cartStore.isEmpty);
  const loading = computed(() => cartStore.loading);
  const error = computed(() => cartStore.error);

  async function addToCart(productId, quantity = 1, customization = null) {
    return await cartStore.addToCart(productId, quantity, customization);
  }

  async function updateQuantity(cartItemId, quantity) {
    return await cartStore.updateQuantity(cartItemId, quantity);
  }

  async function removeFromCart(cartItemId) {
    return await cartStore.removeFromCart(cartItemId);
  }

  async function clearCart() {
    return await cartStore.clearCart();
  }

  async function applyCoupon(couponCode) {
    return await cartStore.applyCoupon(couponCode);
  }

  async function fetchCart() {
    return await cartStore.fetchCart();
  }

  return {
    items,
    itemCount,
    subtotal,
    shippingCost,
    discountAmount,
    total,
    isEmpty,
    loading,
    error,
    addToCart,
    updateQuantity,
    removeFromCart,
    clearCart,
    applyCoupon,
    fetchCart,
  };
}
