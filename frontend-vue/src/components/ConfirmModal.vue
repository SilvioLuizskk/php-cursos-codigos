<template>
    <div
        v-if="visible"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold mb-4">{{ title }}</h3>
            <p class="text-sm text-gray-700 mb-4">{{ message }}</p>
            <div class="flex justify-end space-x-3">
                <button
                    @click="cancel"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800"
                >
                    Cancelar
                </button>
                <button
                    @click="confirm"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                >
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    visible: { type: Boolean, default: false },
    title: { type: String, default: "Confirmação" },
    message: { type: String, default: "Tem certeza?" },
});

const emits = defineEmits(["confirm", "cancel"]);

const confirm = () => {
    console.log("[ConfirmModal] confirm clicked");
    emits("confirm");
};

const cancel = () => {
    console.log("[ConfirmModal] cancel clicked");
    emits("cancel");
};
</script>

<style scoped></style>
