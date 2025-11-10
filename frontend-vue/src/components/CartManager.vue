<template>
    <div class="cart-item-manager">
        <div
            v-for="item in items"
            :key="item.id"
            class="cart-item bg-white p-4 rounded-lg shadow-sm border mb-4"
        >
            <div class="flex items-center gap-4">
                <!-- Imagem do produto -->
                <div
                    class="w-16 h-16 bg-gray-100 rounded-md flex items-center justify-center flex-shrink-0"
                >
                    <i class="fas fa-flip-flops text-2xl text-gray-400"></i>
                </div>

                <!-- Informações do produto -->
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-gray-900 truncate">
                        {{ item.product?.name || item.name }}
                    </h4>
                    <p class="text-sm text-gray-500">
                        {{ item.product?.sku || item.sku }}
                    </p>

                    <!-- Variantes (tamanho, cor) -->
                    <div v-if="item.size || item.color" class="flex gap-2 mt-1">
                        <span
                            v-if="item.size"
                            class="text-xs bg-gray-100 px-2 py-1 rounded"
                        >
                            Tamanho: {{ item.size }}
                        </span>
                        <span
                            v-if="item.color"
                            class="text-xs bg-gray-100 px-2 py-1 rounded"
                        >
                            Cor: {{ item.color }}
                        </span>
                    </div>
                </div>

                <!-- Controles de quantidade -->
                <div class="flex items-center gap-2">
                    <button
                        @click="updateQuantity(item.id, item.quantity - 1)"
                        class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50"
                        :disabled="item.quantity <= 1 || loading"
                    >
                        <i class="fas fa-minus text-sm"></i>
                    </button>

                    <input
                        :value="item.quantity"
                        @input="
                            updateQuantity(
                                item.id,
                                parseInt($event.target.value) || 1,
                            )
                        "
                        type="number"
                        min="1"
                        :max="item.product?.stock_quantity || 99"
                        class="w-16 text-center px-2 py-1 border border-gray-300 rounded text-sm"
                        :disabled="loading"
                    />

                    <button
                        @click="updateQuantity(item.id, item.quantity + 1)"
                        class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50"
                        :disabled="
                            item.quantity >=
                                (item.product?.stock_quantity || 99) || loading
                        "
                    >
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                </div>

                <!-- Preço -->
                <div class="text-right">
                    <p class="font-semibold text-gray-900">
                        R$ {{ formatPrice(item.price * item.quantity) }}
                    </p>
                    <p v-if="item.quantity > 1" class="text-sm text-gray-500">
                        R$ {{ formatPrice(item.price) }} cada
                    </p>
                </div>

                <!-- Botão remover -->
                <button
                    @click="removeItem(item.id)"
                    class="w-8 h-8 rounded border border-red-300 flex items-center justify-center hover:bg-red-50 text-red-600 hover:text-red-700 disabled:opacity-50"
                    :disabled="loading"
                    title="Remover item"
                >
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Ações em lote -->
        <div v-if="items.length > 0" class="bg-gray-50 p-4 rounded-lg mt-4">
            <div
                class="flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center"
            >
                <div class="flex items-center gap-2">
                    <input
                        id="select-all"
                        v-model="selectAll"
                        type="checkbox"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        @change="toggleSelectAll"
                    />
                    <label for="select-all" class="text-sm text-gray-700">
                        Selecionar todos ({{ selectedItems.length }}/{{
                            items.length
                        }})
                    </label>
                </div>

                <div class="flex gap-2">
                    <button
                        v-if="selectedItems.length > 0"
                        @click="removeSelected"
                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50"
                        :disabled="loading"
                    >
                        <i class="fas fa-trash mr-1"></i>
                        Remover Selecionados
                    </button>

                    <button
                        @click="clearCart"
                        class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50"
                        :disabled="loading || items.length === 0"
                    >
                        <i class="fas fa-times mr-1"></i>
                        Limpar Carrinho
                    </button>
                </div>
            </div>
        </div>

        <!-- Resumo do carrinho -->
        <div
            v-if="items.length > 0"
            class="bg-white p-4 rounded-lg shadow-sm border mt-4"
        >
            <h4 class="font-medium text-gray-900 mb-3">Resumo do Carrinho</h4>

            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span>Subtotal ({{ totalItems }} itens):</span>
                    <span>R$ {{ formatPrice(subtotal) }}</span>
                </div>

                <div v-if="shipping > 0" class="flex justify-between text-sm">
                    <span>Frete:</span>
                    <span>R$ {{ formatPrice(shipping) }}</span>
                </div>

                <div
                    v-if="discount > 0"
                    class="flex justify-between text-sm text-green-600"
                >
                    <span>Desconto:</span>
                    <span>-R$ {{ formatPrice(discount) }}</span>
                </div>

                <div
                    class="flex justify-between font-semibold text-lg pt-2 border-t"
                >
                    <span>Total:</span>
                    <span class="text-blue-600"
                        >R$ {{ formatPrice(total) }}</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    loading: {
        type: Boolean,
        default: false,
    },
    shipping: {
        type: Number,
        default: 0,
    },
    discount: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits([
    "update-quantity",
    "remove-item",
    "remove-selected",
    "clear-cart",
]);

const selectedItems = ref([]);
const selectAll = ref(false);

// Computed properties
const totalItems = computed(() => {
    return props.items.reduce((total, item) => total + item.quantity, 0);
});

const subtotal = computed(() => {
    return props.items.reduce(
        (total, item) => total + item.price * item.quantity,
        0,
    );
});

const total = computed(() => {
    return subtotal.value + props.shipping - props.discount;
});

// Methods
const updateQuantity = (itemId, newQuantity) => {
    if (newQuantity < 1) return;

    const item = props.items.find((i) => i.id === itemId);
    if (
        item &&
        item.product?.stock_quantity &&
        newQuantity > item.product.stock_quantity
    ) {
        newQuantity = item.product.stock_quantity;
    }

    emit("update-quantity", { itemId, quantity: newQuantity });
};

const removeItem = (itemId) => {
    emit("remove-item", itemId);
};

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedItems.value = props.items.map((item) => item.id);
    } else {
        selectedItems.value = [];
    }
};

const removeSelected = () => {
    emit("remove-selected", selectedItems.value);
    selectedItems.value = [];
    selectAll.value = false;
};

const clearCart = () => {
    emit("clear-cart");
};

const formatPrice = (price) => {
    return parseFloat(price).toFixed(2).replace(".", ",");
};

// Watch for changes in items to update selections
watch(
    () => props.items,
    (newItems) => {
        // Remove selections for items that no longer exist
        selectedItems.value = selectedItems.value.filter((id) =>
            newItems.some((item) => item.id === id),
        );

        // Update select all state
        selectAll.value =
            selectedItems.value.length === newItems.length &&
            newItems.length > 0;
    },
    { deep: true },
);
</script>
