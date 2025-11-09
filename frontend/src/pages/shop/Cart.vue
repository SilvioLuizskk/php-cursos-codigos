<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Carrinho de Compras</h1>

    <div v-if="isEmpty" class="text-center py-12">
      <p class="text-gray-500 text-lg mb-4">Seu carrinho está vazio</p>
      <router-link to="/produtos" class="text-blue-600 hover:underline">
        Continuar comprando
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Itens do Carrinho -->
      <div class="lg:col-span-2 space-y-4">
        <div v-for="item in items" :key="item.id" class="bg-white p-4 rounded-lg shadow flex items-center gap-4">
          <img 
            :src="item.product.image || '/placeholder-image.jpg'" 
            :alt="item.product.name"
            class="w-16 h-16 object-cover rounded"
          />
          <div class="flex-1">
            <h3 class="font-semibold">{{ item.product.name }}</h3>
            <p class="text-gray-600">R$ {{ formatPrice(item.product.price) }}</p>
          </div>
          <div class="flex items-center gap-2">
            <button 
              class="px-2 py-1 bg-gray-200 rounded"
              @click="updateQuantity(item.id, item.quantity - 1)"
            >-</button>
            <span class="px-3">{{ item.quantity }}</span>
            <button 
              class="px-2 py-1 bg-gray-200 rounded"
              @click="updateQuantity(item.id, item.quantity + 1)"
            >+</button>
          </div>
          <button 
            class="text-red-600 hover:text-red-800"
            @click="removeFromCart(item.id)"
          >
            Remover
          </button>
        </div>
      </div>

      <!-- Resumo -->
      <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-xl font-semibold mb-4">Resumo</h2>
          <div class="space-y-2 mb-4">
            <div class="flex justify-between">
              <span>Subtotal</span>
              <span>R$ {{ formatPrice(subtotal) }}</span>
            </div>
            <div class="flex justify-between">
              <span>Frete</span>
              <span>R$ {{ formatPrice(shippingCost) }}</span>
            </div>
          </div>
          <div class="border-t pt-4">
            <div class="flex justify-between font-semibold text-lg">
              <span>Total</span>
              <span>R$ {{ formatPrice(total) }}</span>
            </div>
          </div>
          <router-link 
            to="/checkout" 
            class="w-full mt-4 bg-blue-600 text-white py-3 rounded-lg block text-center hover:bg-blue-700"
          >
            Finalizar Compra
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useCart } from '@/composables/useCart'
import { formatPrice } from '@/utils/formatters'

const {
  items,
  isEmpty,
  subtotal,
  shippingCost,
  total,
  fetchCart,
  updateQuantity,
  removeFromCart,
} = useCart()

onMounted(async () => {
  await fetchCart()
})
</script>