<template>
    <div class="add-to-cart-form bg-white p-6 rounded-lg shadow-sm border">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            Adicionar ao Carrinho
        </h3>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <!-- Seletor de quantidade -->
            <div>
                <label
                    for="quantity"
                    class="block text-sm font-medium text-gray-700 mb-2"
                >
                    Quantidade
                </label>
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="decreaseQuantity"
                        class="w-10 h-10 rounded-md border border-gray-300 flex items-center justify-center hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="quantity <= 1 || loading"
                    >
                        <i class="fas fa-minus text-gray-600"></i>
                    </button>

                    <input
                        id="quantity"
                        v-model.number="quantity"
                        type="number"
                        min="1"
                        :max="product.stock_quantity || 99"
                        class="w-20 text-center px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        :disabled="loading"
                        @input="validateQuantity"
                    />

                    <button
                        type="button"
                        @click="increaseQuantity"
                        class="w-10 h-10 rounded-md border border-gray-300 flex items-center justify-center hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="
                            quantity >= (product.stock_quantity || 99) ||
                            loading
                        "
                    >
                        <i class="fas fa-plus text-gray-600"></i>
                    </button>
                </div>

                <p
                    v-if="product.stock_quantity"
                    class="text-sm text-gray-500 mt-1"
                >
                    {{ product.stock_quantity }} unidades disponíveis
                </p>
            </div>

            <!-- Seletor de tamanho/cor se disponível -->
            <div v-if="product.variants && product.variants.length > 0">
                <div v-if="hasSizes" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tamanho
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        <button
                            v-for="size in availableSizes"
                            :key="size"
                            type="button"
                            @click="selectedSize = size"
                            :class="[
                                'px-3 py-2 text-sm font-medium rounded-md border focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                                selectedSize === size
                                    ? 'border-blue-500 bg-blue-50 text-blue-700'
                                    : 'border-gray-300 text-gray-700 hover:bg-gray-50',
                            ]"
                            :disabled="loading"
                        >
                            {{ size }}
                        </button>
                    </div>
                </div>

                <div v-if="hasColors" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cor
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        <button
                            v-for="color in availableColors"
                            :key="color"
                            type="button"
                            @click="selectedColor = color"
                            :class="[
                                'px-3 py-2 text-sm font-medium rounded-md border focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                                selectedColor === color
                                    ? 'border-blue-500 bg-blue-50 text-blue-700'
                                    : 'border-gray-300 text-gray-700 hover:bg-gray-50',
                            ]"
                            :disabled="loading"
                        >
                            {{ color }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Preço e total -->
            <div class="bg-gray-50 p-4 rounded-md">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Preço unitário:</span>
                    <span class="font-semibold text-gray-900">
                        R$ {{ formatPrice(product.price) }}
                    </span>
                </div>

                <div
                    v-if="quantity > 1"
                    class="flex justify-between items-center mb-2"
                >
                    <span class="text-sm text-gray-600">Quantidade:</span>
                    <span class="text-sm text-gray-900">{{ quantity }}</span>
                </div>

                <div
                    class="flex justify-between items-center pt-2 border-t border-gray-200"
                >
                    <span class="font-medium text-gray-900">Total:</span>
                    <span class="text-lg font-bold text-blue-600">
                        R$ {{ formatPrice(totalPrice) }}
                    </span>
                </div>
            </div>

            <!-- Avisos -->
            <div
                v-if="
                    product.stock_quantity && quantity > product.stock_quantity
                "
                class="text-sm text-red-600 bg-red-50 p-3 rounded-md"
            >
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Quantidade solicitada maior que o estoque disponível.
            </div>

            <div
                v-if="!isValidSelection"
                class="text-sm text-orange-600 bg-orange-50 p-3 rounded-md"
            >
                <i class="fas fa-info-circle mr-1"></i>
                Selecione todas as opções disponíveis antes de adicionar ao
                carrinho.
            </div>

            <!-- Botões de ação -->
            <div class="space-y-3">
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium"
                    :disabled="!canAddToCart || loading"
                >
                    <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
                    <i v-else class="fas fa-cart-plus mr-2"></i>
                    {{ loading ? "Adicionando..." : "Adicionar ao Carrinho" }}
                </button>

                <button
                    v-if="showBuyNow"
                    type="button"
                    @click="handleBuyNow"
                    class="w-full bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors font-medium"
                    :disabled="!canAddToCart || loading"
                >
                    <i class="fas fa-bolt mr-2"></i>
                    Comprar Agora
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    showBuyNow: {
        type: Boolean,
        default: true,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["add-to-cart", "buy-now"]);

const quantity = ref(1);
const selectedSize = ref("");
const selectedColor = ref("");

// Computed properties
const hasSizes = computed(() => {
    return props.product.variants?.some((v) => v.size);
});

const hasColors = computed(() => {
    return props.product.variants?.some((v) => v.color);
});

const availableSizes = computed(() => {
    if (!hasSizes.value) return [];
    return [...new Set(props.product.variants.map((v) => v.size))].filter(
        Boolean,
    );
});

const availableColors = computed(() => {
    if (!hasColors.value) return [];
    return [...new Set(props.product.variants.map((v) => v.color))].filter(
        Boolean,
    );
});

const totalPrice = computed(() => {
    return (parseFloat(props.product.price) || 0) * quantity.value;
});

const isValidSelection = computed(() => {
    if (hasSizes.value && !selectedSize.value) return false;
    if (hasColors.value && !selectedColor.value) return false;
    return true;
});

const canAddToCart = computed(() => {
    return (
        isValidSelection.value &&
        quantity.value > 0 &&
        (!props.product.stock_quantity ||
            quantity.value <= props.product.stock_quantity)
    );
});

// Methods
const increaseQuantity = () => {
    if (quantity.value < (props.product.stock_quantity || 99)) {
        quantity.value++;
    }
};

const decreaseQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const validateQuantity = () => {
    if (quantity.value < 1) quantity.value = 1;
    if (
        props.product.stock_quantity &&
        quantity.value > props.product.stock_quantity
    ) {
        quantity.value = props.product.stock_quantity;
    }
};

const formatPrice = (price) => {
    return parseFloat(price).toFixed(2).replace(".", ",");
};

const handleSubmit = () => {
    if (!canAddToCart.value) return;

    const cartItem = {
        product_id: props.product.id,
        quantity: quantity.value,
        size: selectedSize.value,
        color: selectedColor.value,
        price: props.product.price,
        total: totalPrice.value,
    };

    emit("add-to-cart", cartItem);
};

const handleBuyNow = () => {
    if (!canAddToCart.value) return;

    const cartItem = {
        product_id: props.product.id,
        quantity: quantity.value,
        size: selectedSize.value,
        color: selectedColor.value,
        price: props.product.price,
        total: totalPrice.value,
    };

    emit("buy-now", cartItem);
};

// Reset selections when product changes
watch(
    () => props.product,
    () => {
        quantity.value = 1;
        selectedSize.value = "";
        selectedColor.value = "";
    },
    { deep: true },
);
</script>
