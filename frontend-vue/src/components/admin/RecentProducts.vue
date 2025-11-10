<template>
    <AdminCard
        title="Produtos Recentes"
        :show-edit-button="false"
        :show-delete-button="false"
        :show-footer="true"
    >
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Produto
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Preço
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                        v-for="product in products"
                        :key="product.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img
                                        :src="
                                            product.image ||
                                            '/placeholder-product.jpg'
                                        "
                                        :alt="product.name"
                                        class="h-10 w-10 rounded-lg object-cover"
                                    />
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ product.name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ product.category }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                        >
                            {{ formatCurrency(product.price) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                :class="getStatusClass(product.status)"
                            >
                                {{ getStatusText(product.status) }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                        >
                            <button
                                @click="$emit('edit-product', product.id)"
                                class="text-blue-600 hover:text-blue-900 mr-3"
                            >
                                Editar
                            </button>
                            <button
                                @click="$emit('delete-product', product.id)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Excluir
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <template #footer>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">
                    Mostrando {{ products.length }} de
                    {{ totalProducts }} produtos
                </span>
                <button
                    @click="$emit('view-all-products')"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                >
                    Ver todos →
                </button>
            </div>
        </template>
    </AdminCard>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import AdminCard from "./AdminCard.vue";

// Props recebidas do componente pai
const props = defineProps({
    products: {
        type: Array,
        default: () => [],
    },
    totalProducts: {
        type: Number,
        default: 0,
    },
});

// Eventos emitidos para o componente pai
const emit = defineEmits([
    "edit-product",
    "delete-product",
    "view-all-products",
]);

// Método para formatar moeda
const formatCurrency = (value) => {
    return new Intl.NumberFormat("pt-BR", {
        style: "currency",
        currency: "BRL",
    }).format(value);
};

// Método para obter classe do status
const getStatusClass = (status) => {
    const classes = {
        active: "bg-green-100 text-green-800",
        inactive: "bg-gray-100 text-gray-800",
        draft: "bg-yellow-100 text-yellow-800",
        out_of_stock: "bg-red-100 text-red-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

// Método para obter texto do status
const getStatusText = (status) => {
    const texts = {
        active: "Ativo",
        inactive: "Inativo",
        draft: "Rascunho",
        out_of_stock: "Sem Estoque",
    };
    return texts[status] || status;
};
</script>
