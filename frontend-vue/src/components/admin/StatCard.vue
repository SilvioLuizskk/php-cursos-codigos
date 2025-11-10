<template>
    <AdminCard
        :title="title"
        :show-edit-button="false"
        :show-delete-button="false"
    >
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div
                    class="w-12 h-12 rounded-lg flex items-center justify-center text-white text-xl"
                    :class="iconBgClass"
                >
                    {{ icon }}
                </div>
            </div>
            <div class="ml-4 flex-1">
                <div class="text-2xl font-bold text-gray-900">
                    {{ formattedValue }}
                </div>
                <div class="text-sm text-gray-500">{{ subtitle }}</div>
                <div v-if="change" class="text-sm" :class="changeClass">
                    {{ changeText }}
                </div>
            </div>
        </div>

        <template #footer>
            <button
                @click="$emit('view-details')"
                class="text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
                Ver detalhes →
            </button>
        </template>
    </AdminCard>
</template>

<script setup>
import { computed, defineProps, defineEmits } from "vue";
import AdminCard from "./AdminCard.vue";

// Props recebidas do componente pai (AdminDashboard)
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: [Number, String],
        required: true,
    },
    subtitle: {
        type: String,
        default: "",
    },
    icon: {
        type: String,
        default: "📊",
    },
    iconBgClass: {
        type: String,
        default: "bg-blue-500",
    },
    change: {
        type: Number,
        default: null,
    },
    format: {
        type: String,
        default: "number", // 'number', 'currency', 'percentage'
        validator: (value) =>
            ["number", "currency", "percentage"].includes(value),
    },
});

// Eventos emitidos para o componente pai
const emit = defineEmits(["view-details"]);

// Computed para formatar o valor
const formattedValue = computed(() => {
    if (props.format === "currency") {
        return new Intl.NumberFormat("pt-BR", {
            style: "currency",
            currency: "BRL",
        }).format(props.value);
    } else if (props.format === "percentage") {
        return `${props.value}%`;
    } else {
        return props.value.toLocaleString("pt-BR");
    }
});

// Computed para a classe do change
const changeClass = computed(() => {
    if (!props.change) return "";
    return props.change > 0 ? "text-green-600" : "text-red-600";
});

// Computed para o texto do change
const changeText = computed(() => {
    if (!props.change) return "";
    const sign = props.change > 0 ? "+" : "";
    return `${sign}${props.change}% em relação ao mês passado`;
});
</script>
