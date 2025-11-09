<template>
    <div class="space-y-2">
        <label v-if="label" class="block text-sm font-medium text-gray-700">{{
            label
        }}</label>

        <!-- Preview da imagem atual -->
        <div v-if="modelValue && !uploading" class="relative">
            <img
                :src="modelValue"
                :alt="previewAlt"
                class="w-full h-32 object-cover rounded-lg border"
            />
            <button
                @click="$emit('update:modelValue', '')"
                class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition"
                title="Remover imagem"
            >
                ×
            </button>
        </div>

        <!-- Área de upload -->
        <div
            @dragover.prevent
            @drop.prevent="handleDrop"
            class="border-2 border-dashed rounded-lg p-4 text-center transition-colors"
            :class="
                uploading
                    ? 'border-blue-300 bg-blue-50'
                    : 'border-gray-300 hover:border-gray-400'
            "
        >
            <input
                ref="fileInput"
                type="file"
                accept="image/*"
                @change="handleFileSelect"
                class="hidden"
            />

            <div v-if="uploading" class="text-blue-600">
                <div
                    class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"
                ></div>
                <p>Enviando imagem...</p>
            </div>

            <div v-else-if="!modelValue" class="text-gray-500">
                <svg
                    class="w-12 h-12 mx-auto mb-2 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                    ></path>
                </svg>
                <p class="mb-2">Arraste uma imagem aqui ou</p>
                <button
                    @click="$refs.fileInput.click()"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                >
                    Selecionar arquivo
                </button>
                <p class="text-xs text-gray-400 mt-2">PNG, JPG até 5MB</p>
            </div>

            <div v-else class="text-gray-500">
                <button
                    @click="$refs.fileInput.click()"
                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition"
                >
                    Alterar imagem
                </button>
            </div>
        </div>

        <!-- Campo alternativo para URL -->
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Ou</span>
            <input
                v-model="urlInput"
                type="url"
                placeholder="Cole uma URL de imagem"
                class="flex-1 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                @blur="handleUrlInput"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useImageUpload } from "@/composables/useImageUpload";

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
    label: {
        type: String,
        default: "",
    },
    previewAlt: {
        type: String,
        default: "Preview",
    },
});

const emit = defineEmits(["update:modelValue"]);

const { uploading, uploadImage, validateImageUrl } = useImageUpload();
const fileInput = ref(null);
const urlInput = ref("");

const handleFileSelect = async (event) => {
    const file = event.target.files[0];
    if (file) {
        const result = await uploadImage(file);
        if (result) {
            emit("update:modelValue", result);
        }
    }
    // Reset input
    event.target.value = "";
};

const handleDrop = async (event) => {
    const file = event.dataTransfer.files[0];
    if (file) {
        const result = await uploadImage(file);
        if (result) {
            emit("update:modelValue", result);
        }
    }
};

const handleUrlInput = () => {
    if (urlInput.value && validateImageUrl(urlInput.value)) {
        emit("update:modelValue", urlInput.value);
    } else if (urlInput.value) {
        // Reset se URL inválida
        urlInput.value = "";
    }
};

// Atualizar urlInput quando modelValue muda
watch(
    () => props.modelValue,
    (newValue) => {
        if (newValue && newValue.startsWith("http")) {
            urlInput.value = newValue;
        } else {
            urlInput.value = "";
        }
    },
    { immediate: true },
);
</script>

<style scoped>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
