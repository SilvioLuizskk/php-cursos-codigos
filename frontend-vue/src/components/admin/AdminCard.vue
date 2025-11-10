<template>
    <div class="admin-card bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
            <div class="flex space-x-2">
                <button
                    v-if="showEditButton"
                    @click="$emit('edit')"
                    class="text-blue-600 hover:text-blue-800 p-1"
                    title="Editar"
                >
                    ✏️
                </button>
                <button
                    v-if="showDeleteButton"
                    @click="$emit('delete')"
                    class="text-red-600 hover:text-red-800 p-1"
                    title="Excluir"
                >
                    🗑️
                </button>
            </div>
        </div>

        <div class="content">
            <slot></slot>
        </div>

        <div v-if="showFooter" class="mt-4 pt-4 border-t border-gray-200">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

// Props recebidas do componente pai
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    showEditButton: {
        type: Boolean,
        default: true,
    },
    showDeleteButton: {
        type: Boolean,
        default: true,
    },
    showFooter: {
        type: Boolean,
        default: false,
    },
});

// Eventos emitidos para o componente pai
const emit = defineEmits(["edit", "delete"]);
</script>

<style scoped>
.admin-card {
    transition: box-shadow 0.2s ease;
}

.admin-card:hover {
    box-shadow:
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>
