<template>
    <div class="order-filters bg-white p-6 rounded-lg shadow-sm border">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Filtrar Pedidos</h3>
            <button
                @click="showFilters = !showFilters"
                class="text-gray-500 hover:text-gray-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 p-1 rounded"
            >
                <i
                    :class="
                        showFilters
                            ? 'fas fa-chevron-up'
                            : 'fas fa-chevron-down'
                    "
                ></i>
            </button>
        </div>

        <div v-show="showFilters" class="space-y-4">
            <form @submit.prevent="handleSubmit" class="space-y-4">
                <!-- Busca por ID ou número do pedido -->
                <div>
                    <label
                        for="search"
                        class="block text-sm font-medium text-gray-700 mb-1"
                    >
                        Buscar pedido
                    </label>
                    <input
                        id="search"
                        v-model="localFilters.search"
                        type="text"
                        placeholder="ID, número ou produto..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        @input="debouncedSearch"
                    />
                </div>

                <!-- Filtro por status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status do Pedido
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label
                            v-for="status in statusOptions"
                            :key="status.value"
                            class="flex items-center"
                        >
                            <input
                                v-model="localFilters.status"
                                :value="status.value"
                                type="checkbox"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                @change="handleSubmit"
                            />
                            <span class="ml-2 text-sm text-gray-700">{{
                                status.label
                            }}</span>
                        </label>
                    </div>
                </div>

                <!-- Filtro por período -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Período
                    </label>
                    <select
                        v-model="localFilters.period"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        @change="handleSubmit"
                    >
                        <option value="">Todos os períodos</option>
                        <option value="today">Hoje</option>
                        <option value="week">Última semana</option>
                        <option value="month">Último mês</option>
                        <option value="3months">Últimos 3 meses</option>
                        <option value="year">Último ano</option>
                        <option value="custom">Período personalizado</option>
                    </select>
                </div>

                <!-- Datas personalizadas -->
                <div
                    v-if="localFilters.period === 'custom'"
                    class="grid grid-cols-2 gap-2"
                >
                    <div>
                        <label
                            for="start_date"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Data inicial
                        </label>
                        <input
                            id="start_date"
                            v-model="localFilters.start_date"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @change="handleSubmit"
                        />
                    </div>
                    <div>
                        <label
                            for="end_date"
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Data final
                        </label>
                        <input
                            id="end_date"
                            v-model="localFilters.end_date"
                            type="date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @change="handleSubmit"
                        />
                    </div>
                </div>

                <!-- Filtro por valor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Faixa de Valor
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <input
                            v-model.number="localFilters.min_amount"
                            type="number"
                            placeholder="Mín. R$"
                            min="0"
                            step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @input="debouncedSearch"
                        />
                        <input
                            v-model.number="localFilters.max_amount"
                            type="number"
                            placeholder="Máx. R$"
                            min="0"
                            step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            @input="debouncedSearch"
                        />
                    </div>
                </div>

                <!-- Ordenação -->
                <div>
                    <label
                        for="sort"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Ordenar por
                    </label>
                    <select
                        id="sort"
                        v-model="localFilters.sort_by"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        @change="handleSubmit"
                    >
                        <option value="created_at">Data do pedido</option>
                        <option value="total">Valor total</option>
                        <option value="status">Status</option>
                    </select>
                </div>

                <!-- Botões de ação -->
                <div class="flex gap-2 pt-4">
                    <button
                        type="submit"
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        :disabled="loading"
                    >
                        <i
                            v-if="loading"
                            class="fas fa-spinner fa-spin mr-2"
                        ></i>
                        <i v-else class="fas fa-search mr-2"></i>
                        Aplicar Filtros
                    </button>
                    <button
                        type="button"
                        @click="clearFilters"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                        :disabled="loading"
                    >
                        <i class="fas fa-times mr-2"></i>
                        Limpar
                    </button>
                </div>
            </form>

            <!-- Resultados -->
            <div
                v-if="totalResults !== null"
                class="mt-4 p-3 bg-gray-50 rounded-md"
            >
                <p class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ totalResults }} pedido{{
                        totalResults !== 1 ? "s" : ""
                    }}
                    encontrado{{ totalResults !== 1 ? "s" : "" }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useDebounce } from "@/composables/useDebounce";

const props = defineProps({
    initialFilters: {
        type: Object,
        default: () => ({}),
    },
    loading: {
        type: Boolean,
        default: false,
    },
    totalResults: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(["apply-filters", "clear-filters"]);

const showFilters = ref(false);

const statusOptions = [
    { value: "pending", label: "Pendente" },
    { value: "processing", label: "Processando" },
    { value: "shipped", label: "Enviado" },
    { value: "delivered", label: "Entregue" },
    { value: "cancelled", label: "Cancelado" },
];

const localFilters = ref({
    search: "",
    status: [],
    period: "",
    start_date: "",
    end_date: "",
    min_amount: null,
    max_amount: null,
    sort_by: "created_at",
    sort_order: "desc",
    ...props.initialFilters,
});

// Debounced search
const { debouncedFunction: debouncedSearch } = useDebounce(() => {
    handleSubmit();
}, 500);

const handleSubmit = () => {
    // Limpar valores vazios
    const cleanFilters = { ...localFilters.value };
    Object.keys(cleanFilters).forEach((key) => {
        if (cleanFilters[key] === "" || cleanFilters[key] === null) {
            delete cleanFilters[key];
        }
    });

    emit("apply-filters", cleanFilters);
};

const clearFilters = () => {
    localFilters.value = {
        search: "",
        status: [],
        period: "",
        start_date: "",
        end_date: "",
        min_amount: null,
        max_amount: null,
        sort_by: "created_at",
        sort_order: "desc",
    };
    emit("clear-filters");
};

// Atualizar filtros locais quando props mudam
watch(
    () => props.initialFilters,
    (newFilters) => {
        if (newFilters) {
            localFilters.value = { ...localFilters.value, ...newFilters };
        }
    },
    { deep: true },
);
</script>
