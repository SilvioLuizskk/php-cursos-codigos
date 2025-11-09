import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { cartService } from "@/api/cartService";

export const useCartStore = defineStore("cart", () => {
  // Estado
  const items = ref([]);
  const loading = ref(false);
  const error = ref(null);
  const coupon = ref(null);

  // Computados
  const itemCount = computed(() =>
    items.value.reduce((total, item) => total + item.quantity, 0)
  );

  const subtotal = computed(() =>
    items.value.reduce((total, item) => {
      const price = item.product.discount_price || item.product.price;
      return total + price * item.quantity;
    }, 0)
  );

  const shippingCost = computed(() => 15.0); // Exemplo

  const discountAmount = computed(() => {
    if (!coupon.value) return 0;

    if (coupon.value.discount_type === "percentage") {
      return subtotal.value * (coupon.value.discount_value / 100);
    }
    return coupon.value.discount_value;
  });

  const total = computed(
    () => subtotal.value + shippingCost.value - discountAmount.value
  );

  const isEmpty = computed(() => items.value.length === 0);

  // Ações
  async function fetchCart() {
    loading.value = true;
    error.value = null;

    try {
      const response = await cartService.getCart();
      items.value = response.items || [];
      return response;
    } catch (err) {
      error.value = err.response?.data?.message || "Erro ao carregar carrinho";
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function addToCart(productId, quantity = 1, customization = null) {
    loading.value = true;
    error.value = null;

    try {
      const response = await cartService.addToCart(
        productId,
        quantity,
        customization
      );
      await fetchCart();
      return response;
    } catch (err) {
      error.value =
        err.response?.data?.message || "Erro ao adicionar ao carrinho";
      throw err;
    } finally {
      loading.value = false;
    }
  }

  async function updateQuantity(cartItemId, quantity) {
    try {
      await cartService.updateCartItem(cartItemId, quantity);
      await fetchCart();
    } catch (err) {
      error.value =
        err.response?.data?.message || "Erro ao atualizar quantidade";
      throw err;
    }
  }

  async function removeFromCart(cartItemId) {
    try {
      await cartService.removeFromCart(cartItemId);
      await fetchCart();
    } catch (err) {
      error.value =
        err.response?.data?.message || "Erro ao remover do carrinho";
      throw err;
    }
  }

  async function clearCart() {
    try {
      await cartService.clearCart();
      items.value = [];
      coupon.value = null;
    } catch (err) {
      error.value = err.response?.data?.message || "Erro ao limpar carrinho";
      throw err;
    }
  }

  async function applyCoupon(couponCode) {
    try {
      const response = await cartService.applyCoupon(couponCode);
      coupon.value = response.coupon;
      await fetchCart();
      return response;
    } catch (err) {
      error.value = err.response?.data?.message || "Cupom inválido";
      throw err;
    }
  }

  return {
    items,
    loading,
    error,
    coupon,
    itemCount,
    subtotal,
    shippingCost,
    discountAmount,
    total,
    isEmpty,
    fetchCart,
    addToCart,
    updateQuantity,
    removeFromCart,
    clearCart,
    applyCoupon,
  };
});
