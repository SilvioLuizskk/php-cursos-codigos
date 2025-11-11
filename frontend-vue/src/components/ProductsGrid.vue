<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
    <TransitionGroup name="product" tag="div" class="contents">
      <div
        v-for="(product, index) in products"
        :key="product.id || index"
        class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group product-card"
        :style="{ transitionDelay: `${index * 100}ms` }"
      >
        <div class="relative overflow-hidden">
          <div class="aspect-square bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
            <img v-if="product.image" :src="product.image" :alt="product.name" class="w-full h-full object-cover" />
            <i v-else class="fas fa-flip-flops text-5xl text-blue-400 group-hover:text-blue-600 transition-colors duration-300"></i>
          </div>
          <div v-if="product.is_featured" class="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">Destaque</div>
        </div>
        <div class="p-6">
          <h3 class="font-bold text-gray-900 mb-2 text-lg group-hover:text-blue-600 transition-colors">{{ product.name }}</h3>
          <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ product.description }}</p>
          <div class="flex items-center justify-between mb-4">
            <span class="text-2xl font-bold text-blue-600">R$ {{ product.price }}</span>
            <div class="flex items-center">
              <i class="fas fa-star text-yellow-400 mr-1"></i>
              <span class="text-sm text-gray-600">4.8</span>
            </div>
          </div>
          <button @click="emitView(product)" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-full font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
            <i class="fas fa-eye mr-2"></i>Ver Detalhes
          </button>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  products: { type: Array, default: () => [] }
})

const emit = defineEmits(['view'])

const emitView = (product) => {
  emit('view', product)
}
</script>

<style scoped>
.product-card img {
  width: 100%;
  height: 160px;
  object-fit: cover;
}
@media (max-width: 768px) {
  .product-card img { height: 120px }
}
</style>
