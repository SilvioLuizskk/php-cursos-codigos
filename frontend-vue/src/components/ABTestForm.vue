<template>
    <div class="ab-test-form">
        <!-- Botão para criar novo teste -->
        <div v-if="!showForm" class="mb-6">
            <button
                @click="openCreateForm()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
            >
                <i class="fas fa-plus mr-2"></i>
                Novo Teste A/B
            </button>
        </div>

        <!-- Formulário -->
        <div v-else class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ isEditing ? "Editar Teste A/B" : "Novo Teste A/B" }}
                </h3>
                <button
                    @click="closeForm"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Informações básicas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Nome do Teste *
                        </label>
                        <input
                            id="name"
                            v-model="formData.name"
                            type="text"
                            required
                            placeholder="Ex: Botão CTA Verde vs Azul"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>

                    <div>
                        <label
                            for="status"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Status
                        </label>
                        <select
                            id="status"
                            v-model="formData.status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        >
                            <option value="draft">Rascunho</option>
                            <option value="running">Executando</option>
                            <option value="paused">Pausado</option>
                            <option value="finished">Finalizado</option>
                        </select>
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label
                        for="description"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Descrição
                    </label>
                    <textarea
                        id="description"
                        v-model="formData.description"
                        rows="3"
                        placeholder="Descreva o objetivo do teste A/B..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        :disabled="loading"
                        maxlength="500"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ formData.description.length }}/500 caracteres
                    </p>
                </div>

                <!-- Variantes -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-md font-medium text-gray-900">
                            Variantes do Teste
                        </h4>
                        <button
                            type="button"
                            @click="addVariant"
                            class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-md hover:bg-green-700"
                            :disabled="loading"
                        >
                            <i class="fas fa-plus mr-1"></i>
                            Adicionar Variante
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="(variant, index) in formData.variants"
                            :key="index"
                            class="border border-gray-200 rounded-md p-4"
                        >
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-medium text-gray-900">
                                    Variante {{ index + 1 }}
                                </h5>
                                <button
                                    v-if="formData.variants.length > 2"
                                    type="button"
                                    @click="removeVariant(index)"
                                    class="text-red-600 hover:text-red-700"
                                    :disabled="loading"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        :for="'variant-name-' + index"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        Nome da Variante *
                                    </label>
                                    <input
                                        :id="'variant-name-' + index"
                                        v-model="variant.name"
                                        type="text"
                                        required
                                        placeholder="Ex: Controle, Variante A"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :disabled="loading"
                                    />
                                </div>

                                <div>
                                    <label
                                        :for="'variant-traffic-' + index"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >
                                        % de Tráfego
                                    </label>
                                    <input
                                        :id="'variant-traffic-' + index"
                                        v-model.number="
                                            variant.traffic_percentage
                                        "
                                        type="number"
                                        min="0"
                                        max="100"
                                        step="1"
                                        placeholder="33"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        :disabled="loading"
                                    />
                                </div>
                            </div>

                            <div class="mt-3">
                                <label
                                    :for="'variant-content-' + index"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >
                                    Conteúdo/Alterações
                                </label>
                                <textarea
                                    :id="'variant-content-' + index"
                                    v-model="variant.content"
                                    rows="2"
                                    placeholder="Descreva as alterações desta variante..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                    :disabled="loading"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Aviso sobre distribuição de tráfego -->
                    <div class="mt-3 p-3 bg-blue-50 rounded-md">
                        <p class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            A soma dos percentuais de tráfego deve ser 100%.
                            Variantes sem percentual definido terão distribuição
                            automática.
                        </p>
                    </div>
                </div>

                <!-- Configurações de data -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label
                            for="start_date"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Data de Início
                        </label>
                        <input
                            id="start_date"
                            v-model="formData.start_date"
                            type="datetime-local"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>

                    <div>
                        <label
                            for="end_date"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Data de Fim
                        </label>
                        <input
                            id="end_date"
                            v-model="formData.end_date"
                            type="datetime-local"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :disabled="loading"
                        />
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="flex gap-3 pt-6 border-t">
                    <button
                        type="button"
                        @click="closeForm"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                        :disabled="loading"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        :disabled="!canSubmit || loading"
                    >
                        <i
                            v-if="loading"
                            class="fas fa-spinner fa-spin mr-2"
                        ></i>
                        <i v-else class="fas fa-save mr-2"></i>
                        {{
                            loading
                                ? "Salvando..."
                                : isEditing
                                  ? "Atualizar Teste"
                                  : "Criar Teste"
                        }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    test: {
        type: Object,
        default: null,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["create-test", "update-test", "close-form"]);

const showForm = ref(false);
const isEditing = ref(false);

const formData = ref({
    name: "",
    description: "",
    status: "draft",
    variants: [
        { name: "Controle", traffic_percentage: 50, content: "" },
        { name: "Variante A", traffic_percentage: 50, content: "" },
    ],
    start_date: "",
    end_date: "",
});

// Computed properties
const canSubmit = computed(() => {
    return (
        formData.value.name.trim() !== "" &&
        formData.value.variants.length >= 2 &&
        formData.value.variants.every((v) => v.name.trim() !== "") &&
        !props.loading
    );
});

// Methods
const openCreateForm = () => {
    resetForm();
    isEditing.value = false;
    showForm.value = true;
};

const openEditForm = (test) => {
    formData.value = {
        name: test.name || "",
        description: test.description || "",
        status: test.status || "draft",
        variants: test.variants
            ? [...test.variants]
            : [
                  { name: "Controle", traffic_percentage: 50, content: "" },
                  { name: "Variante A", traffic_percentage: 50, content: "" },
              ],
        start_date: test.start_date
            ? new Date(test.start_date).toISOString().slice(0, 16)
            : "",
        end_date: test.end_date
            ? new Date(test.end_date).toISOString().slice(0, 16)
            : "",
    };
    isEditing.value = true;
    showForm.value = true;
};

const closeForm = () => {
    showForm.value = false;
    emit("close-form");
};

const resetForm = () => {
    formData.value = {
        name: "",
        description: "",
        status: "draft",
        variants: [
            { name: "Controle", traffic_percentage: 50, content: "" },
            { name: "Variante A", traffic_percentage: 50, content: "" },
        ],
        start_date: "",
        end_date: "",
    };
};

const addVariant = () => {
    formData.value.variants.push({
        name: `Variante ${String.fromCharCode(65 + formData.value.variants.length - 1)}`,
        traffic_percentage: 0,
        content: "",
    });
};

const removeVariant = (index) => {
    if (formData.value.variants.length > 2) {
        formData.value.variants.splice(index, 1);
    }
};

const handleSubmit = () => {
    if (!canSubmit.value) return;

    const testData = {
        ...formData.value,
        // Converter datas para formato ISO
        start_date: formData.value.start_date || null,
        end_date: formData.value.end_date || null,
    };

    if (isEditing.value && props.test) {
        emit("update-test", { id: props.test.id, ...testData });
    } else {
        emit("create-test", testData);
    }
};

// Watch for prop changes
watch(
    () => props.test,
    (newTest) => {
        if (newTest && !showForm.value) {
            openEditForm(newTest);
        }
    },
    { immediate: true },
);
</script>
