<template>
    <div class="recommended-section my-10">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-thumbs-up text-blue-500 mr-2"></i> Recomendados
            para você
        </h2>
        <div class="overflow-x-auto hide-scrollbar">
            <div class="flex gap-4 min-w-[320px]">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="min-w-[220px] max-w-xs bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow flex-shrink-0"
                >
                    <img
                        :src="product.image"
                        class="w-full h-40 object-cover rounded-t-lg"
                        :alt="product.name"
                    />
                    <div class="p-3">
                        <h3
                            class="font-semibold text-gray-900 mb-1 text-base line-clamp-1"
                        >
                            {{ product.name }}
                        </h3>
                        <div class="flex items-center mb-1">
                            <div class="flex text-yellow-400 text-xs">
                                <i
                                    v-for="n in 5"
                                    :key="n"
                                    :class="
                                        n <= product.rating
                                            ? 'fas fa-star'
                                            : 'far fa-star'
                                    "
                                ></i>
                            </div>
                            <span class="text-xs text-gray-500 ml-1"
                                >({{ product.rating || 0 }})</span
                            >
                        </div>
                        <div class="text-blue-600 font-bold text-lg">
                            R$ {{ product.price }}
                        </div>
                        <button
                            class="btn btn-primary w-full mt-2"
                            @click="$emit('add-to-cart', product)"
                        >
                            <i class="fas fa-cart-plus mr-1"></i> Adicionar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    products: { type: Array, default: () => [] },
});
</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 600;
    transition:
        background 0.2s,
        color 0.2s;
}
.btn-primary {
    background: #2563eb;
    color: #fff;
}
.btn-primary:hover {
    background: #1d4ed8;
}
</style>
