<template>
    <button
        :class="[
            'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed',
            variantClasses,
            sizeClasses,
            fullWidth ? 'w-full' : '',
        ]"
        :disabled="disabled || loading"
        @click="$emit('click', $event)"
    >
        <!-- Spinner de loading -->
        <span v-if="loading" class="inline-block mr-2">
            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                ></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
            </svg>
        </span>

        <!-- Ícone esquerdo -->
        <span v-if="icon && !loading" class="inline-block mr-2">
            <slot name="icon">
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        :d="icon"
                    />
                </svg>
            </slot>
        </span>

        <!-- Conteúdo -->
        <slot />

        <!-- Ícone direito -->
        <span v-if="iconRight && !loading" class="inline-block ml-2">
            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    :d="iconRight"
                />
            </svg>
        </span>
    </button>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    variant: {
        type: String,
        default: "primary",
        validator: (value) =>
            [
                "primary",
                "secondary",
                "success",
                "danger",
                "warning",
                "ghost",
                "outline",
            ].includes(value),
    },
    size: {
        type: String,
        default: "md",
        validator: (value) => ["xs", "sm", "md", "lg", "xl"].includes(value),
    },
    disabled: Boolean,
    loading: Boolean,
    fullWidth: Boolean,
    icon: String,
    iconRight: String,
});

defineEmits(["click"]);

const variantClasses = computed(() => {
    const variants = {
        primary:
            "bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500 shadow-sm hover:shadow-md",
        secondary:
            "bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500 shadow-sm hover:shadow-md",
        success:
            "bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 shadow-sm hover:shadow-md",
        danger: "bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-sm hover:shadow-md",
        warning:
            "bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-yellow-500 shadow-sm hover:shadow-md",
        ghost: "bg-transparent text-gray-700 hover:bg-gray-100 focus:ring-gray-500",
        outline:
            "border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-blue-500",
    };
    return variants[props.variant];
});

const sizeClasses = computed(() => {
    const sizes = {
        xs: "px-2.5 py-1.5 text-xs",
        sm: "px-3 py-2 text-sm",
        md: "px-4 py-2 text-base",
        lg: "px-6 py-3 text-lg",
        xl: "px-8 py-4 text-xl",
    };
    return sizes[props.size];
});
</script>
