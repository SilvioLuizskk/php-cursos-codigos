<template>
    <div class="flex items-center justify-center">
        <div class="relative">
            <!-- Spinner principal -->
            <div
                class="w-12 h-12 border-4 border-gray-200 border-t-blue-600 rounded-full animate-spin"
            ></div>

            <!-- Spinner interno opcional -->
            <div
                v-if="double"
                class="absolute inset-2 border-2 border-transparent border-t-purple-400 rounded-full animate-spin"
                style="animation-direction: reverse; animation-duration: 0.6s"
            ></div>
        </div>

        <!-- Texto opcional -->
        <span v-if="text" class="ml-3 text-gray-600 font-medium">
            {{ text }}
        </span>
    </div>
</template>

<script setup>
defineProps({
    text: {
        type: String,
        default: null,
    },
    double: {
        type: Boolean,
        default: false,
    },
});
</script>
