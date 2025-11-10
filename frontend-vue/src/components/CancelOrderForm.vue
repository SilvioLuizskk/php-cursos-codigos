<template>
    <div class="cancel-order-form">
        <!-- Botão para abrir modal -->
        <button
            v-if="!showForm"
            @click="showForm = true"
            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
            :disabled="loading"
        >
            <i class="fas fa-times mr-2"></i>
            Cancelar Pedido
        </button>

        <!-- Formulário de cancelamento -->
        <div v-else class="bg-white p-6 rounded-lg shadow-sm border">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    Cancelar Pedido
                </h3>
                <button
                    @click="showForm = false"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <!-- Motivo do cancelamento -->
                <div>
                    <label
                        for="reason"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Motivo do Cancelamento *
                    </label>
                    <select
                        id="reason"
                        v-model="formData.reason"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        :disabled="loading"
                    >
                        <option value="">Selecione um motivo</option>
                        <option value="changed_mind">Mudei de ideia</option>
                        <option value="found_better_price">
                            Encontrei preço melhor
                        </option>
                        <option value="shipping_delay">
                            Demora na entrega
                        </option>
                        <option value="wrong_product">Produto errado</option>
                        <option value="damaged_product">
                            Produto danificado
                        </option>
                        <option value="payment_issue">
                            Problema no pagamento
                        </option>
                        <option value="other">Outro motivo</option>
                    </select>
                </div>

                <!-- Detalhes adicionais -->
                <div>
                    <label
                        for="comments"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Detalhes Adicionais (Opcional)
                    </label>
                    <textarea
                        id="comments"
                        v-model="formData.comments"
                        rows="4"
                        placeholder="Descreva o motivo do cancelamento em mais detalhes..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"
                        :disabled="loading"
                        maxlength="500"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ formData.comments.length }}/500 caracteres
                    </p>
                </div>

                <!-- Confirmação -->
                <div class="bg-red-50 p-4 rounded-md">
                    <div class="flex items-start">
                        <i
                            class="fas fa-exclamation-triangle text-red-600 mt-0.5 mr-3"
                        ></i>
                        <div>
                            <h4 class="text-sm font-medium text-red-800 mb-1">
                                Atenção: Esta ação não pode ser desfeita
                            </h4>
                            <p class="text-sm text-red-700">
                                Ao cancelar este pedido, você não poderá mais
                                alterá-lo. Se o pedido já foi processado ou
                                enviado, entre em contato conosco.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Políticas de reembolso -->
                <div
                    v-if="order && orderCanBeRefunded"
                    class="bg-blue-50 p-4 rounded-md"
                >
                    <div class="flex items-start">
                        <i
                            class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"
                        ></i>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800 mb-1">
                                Política de Reembolso
                            </h4>
                            <p class="text-sm text-blue-700">
                                Se o pedido for cancelado antes do envio, você
                                receberá reembolso total. Após o envio, o
                                reembolso será processado conforme nossa
                                política.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="flex gap-3 pt-4">
                    <button
                        type="button"
                        @click="showForm = false"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                        :disabled="loading"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors"
                        :disabled="!canSubmit || loading"
                    >
                        <i
                            v-if="loading"
                            class="fas fa-spinner fa-spin mr-2"
                        ></i>
                        <i v-else class="fas fa-times mr-2"></i>
                        {{
                            loading ? "Cancelando..." : "Confirmar Cancelamento"
                        }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    order: {
        type: Object,
        default: null,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["cancel-order"]);

const showForm = ref(false);

const formData = ref({
    reason: "",
    comments: "",
});

// Computed properties
const canSubmit = computed(() => {
    return formData.value.reason.trim() !== "" && !props.loading;
});

const orderCanBeRefunded = computed(() => {
    if (!props.order) return false;
    // Pedidos podem ser reembolsados se não foram enviados
    return !["shipped", "delivered"].includes(props.order.status);
});

// Methods
const handleSubmit = () => {
    if (!canSubmit.value) return;

    emit("cancel-order", {
        orderId: props.order?.id,
        reason: formData.value.reason,
        comments: formData.value.comments.trim(),
    });

    // Reset form
    formData.value = {
        reason: "",
        comments: "",
    };
    showForm.value = false;
};
</script>
