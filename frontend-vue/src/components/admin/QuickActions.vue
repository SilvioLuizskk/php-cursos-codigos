<template>
    <AdminCard
        title="Ações Rápidas"
        :show-edit-button="false"
        :show-delete-button="false"
    >
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <button
                v-for="action in actions"
                :key="action.id"
                @click="$emit('action-clicked', action.id)"
                class="p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors text-left"
            >
                <div class="flex items-center">
                    <div class="text-2xl mr-3">{{ action.icon }}</div>
                    <div>
                        <div class="font-medium text-gray-900">
                            {{ action.title }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ action.description }}
                        </div>
                    </div>
                </div>
            </button>
        </div>
    </AdminCard>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";
import AdminCard from "./AdminCard.vue";

// Props recebidas do componente pai
const props = defineProps({
    actions: {
        type: Array,
        default: () => [],
    },
});

// Eventos emitidos para o componente pai
const emit = defineEmits(["action-clicked"]);
</script>
