<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Finalizar Pedido</h1>

    <div v-if="loading" class="flex justify-center py-12">
      <LoadingSpinner />
    </div>

    <div v-else-if="cartStore.isEmpty" class="text-center py-12">
      <p class="text-gray-600 mb-4">Seu carrinho está vazio</p>
      <router-link
        to="/produtos"
        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700"
      >
        Continuar Comprando
      </router-link>
    </div>

    <form
      v-else
      @submit.prevent="handleSubmit"
      class="grid grid-cols-1 lg:grid-cols-3 gap-8"
    >
      <!-- Formulário -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Endereço de Entrega -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-xl font-semibold mb-4">Endereço de Entrega</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <Input
              v-model="form.shipping_address.street"
              label="Rua *"
              placeholder="Nome da rua"
              :error="errors['shipping_address.street']"
              required
            />

            <Input
              v-model="form.shipping_address.number"
              label="Número *"
              placeholder="123"
              :error="errors['shipping_address.number']"
              required
            />

            <Input
              v-model="form.shipping_address.complement"
              label="Complemento"
              placeholder="Apto, bloco, etc."
            />

            <Input
              v-model="form.shipping_address.city"
              label="Cidade *"
              placeholder="Nome da cidade"
              :error="errors['shipping_address.city']"
              required
            />

            <Input
              v-model="form.shipping_address.state"
              label="Estado *"
              placeholder="UF"
              :error="errors['shipping_address.state']"
              required
            />

            <Input
              v-model="form.shipping_address.zip_code"
              label="CEP *"
              placeholder="00000-000"
              :error="errors['shipping_address.zip_code']"
              required
            />
          </div>
        </div>

        <!-- Método de Pagamento -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-xl font-semibold mb-4">Método de Pagamento</h2>

          <div class="space-y-3">
            <label class="flex items-center">
              <input
                v-model="form.payment_method"
                type="radio"
                value="credit_card"
                class="mr-3"
              />
              <span>Cartão de Crédito</span>
            </label>

            <label class="flex items-center">
              <input
                v-model="form.payment_method"
                type="radio"
                value="debit_card"
                class="mr-3"
              />
              <span>Cartão de Débito</span>
            </label>

            <label class="flex items-center">
              <input
                v-model="form.payment_method"
                type="radio"
                value="pix"
                class="mr-3"
              />
              <span>PIX</span>
            </label>

            <label class="flex items-center">
              <input
                v-model="form.payment_method"
                type="radio"
                value="bank_transfer"
                class="mr-3"
              />
              <span>Transferência Bancária</span>
            </label>
          </div>

          <p v-if="!form.payment_method" class="text-red-500 text-sm mt-2">
            Selecione um método de pagamento
          </p>
        </div>

        <!-- Cupom de Desconto -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-xl font-semibold mb-4">Cupom de Desconto</h2>

          <div class="flex gap-2">
            <Input
              v-model="couponCode"
              placeholder="Digite o código do cupom"
              class="flex-1"
            />
            <Button
              type="button"
              @click="applyCoupon"
              variant="outline"
              :loading="applyingCoupon"
            >
              Aplicar
            </Button>
          </div>

          <p v-if="cartStore.discountAmount > 0" class="text-green-600 mt-2">
            Cupom aplicado: -R$ {{ cartStore.discountAmount.toFixed(2) }}
          </p>
        </div>

        <!-- Observações -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-xl font-semibold mb-4">Observações</h2>

          <Textarea
            v-model="form.notes"
            placeholder="Observações sobre o pedido (opcional)"
            rows="3"
          />
        </div>
      </div>

      <!-- Resumo do Pedido -->
      <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow sticky top-8">
          <h2 class="text-xl font-semibold mb-4">Resumo do Pedido</h2>

          <!-- Itens -->
          <div class="space-y-3 mb-4">
            <div
              v-for="item in cartStore.items"
              :key="item.id"
              class="flex justify-between items-center"
            >
              <div class="flex-1">
                <p class="font-medium">{{ item.product.name }}</p>
                <p class="text-sm text-gray-600">
                  Quantidade: {{ item.quantity }}
                </p>
              </div>
              <span class="font-medium">
                R$ {{ (item.product.price * item.quantity).toFixed(2) }}
              </span>
            </div>
          </div>

          <hr class="my-4" />

          <!-- Totais -->
          <div class="space-y-2">
            <div class="flex justify-between">
              <span>Subtotal</span>
              <span>R$ {{ cartStore.subtotal.toFixed(2) }}</span>
            </div>

            <div class="flex justify-between">
              <span>Frete</span>
              <span>R$ {{ cartStore.shippingCost.toFixed(2) }}</span>
            </div>

            <div
              v-if="cartStore.discountAmount > 0"
              class="flex justify-between text-green-600"
            >
              <span>Desconto</span>
              <span>-R$ {{ cartStore.discountAmount.toFixed(2) }}</span>
            </div>

            <hr class="my-2" />

            <div class="flex justify-between text-lg font-semibold">
              <span>Total</span>
              <span>R$ {{ cartStore.total.toFixed(2) }}</span>
            </div>
          </div>

          <!-- Botão Finalizar -->
          <Button
            type="submit"
            variant="primary"
            size="lg"
            class="w-full mt-6"
            :loading="submitting"
            :disabled="!isFormValid"
          >
            Finalizar Pedido
          </Button>

          <!-- Erro -->
          <p v-if="error" class="text-red-500 text-sm mt-2 text-center">
            {{ error }}
          </p>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useCart } from "@/composables/useCart";
import { useOrders } from "@/composables/useOrders";
import { useAuth } from "@/composables/useAuth";
import Input from "@/components/base/Input.vue";
import Button from "@/components/base/Button.vue";
import Textarea from "@/components/base/Textarea.vue";
import LoadingSpinner from "@/components/base/LoadingSpinner.vue";

const router = useRouter();
const cartStore = useCart();
const { createOrder } = useOrders();
const { isAuthenticated } = useAuth();

// Estado
const loading = ref(false);
const submitting = ref(false);
const applyingCoupon = ref(false);
const error = ref("");
const couponCode = ref("");

// Formulário
const form = reactive({
  shipping_address: {
    street: "",
    number: "",
    complement: "",
    city: "",
    state: "",
    zip_code: "",
  },
  payment_method: "",
  notes: "",
});

// Erros de validação
const errors = reactive({});

// Validação do formulário
const isFormValid = computed(() => {
  return (
    form.shipping_address.street &&
    form.shipping_address.number &&
    form.shipping_address.city &&
    form.shipping_address.state &&
    form.shipping_address.zip_code &&
    form.payment_method
  );
});

// Aplicar cupom
async function applyCoupon() {
  if (!couponCode.value.trim()) return;

  applyingCoupon.value = true;
  try {
    await cartStore.applyCoupon(couponCode.value.trim());
    couponCode.value = "";
  } catch (err) {
    // Erro já tratado no store
  } finally {
    applyingCoupon.value = false;
  }
}

// Validar formulário
function validateForm() {
  errors["shipping_address.street"] = !form.shipping_address.street
    ? "Rua é obrigatória"
    : "";
  errors["shipping_address.number"] = !form.shipping_address.number
    ? "Número é obrigatório"
    : "";
  errors["shipping_address.city"] = !form.shipping_address.city
    ? "Cidade é obrigatória"
    : "";
  errors["shipping_address.state"] = !form.shipping_address.state
    ? "Estado é obrigatório"
    : "";
  errors["shipping_address.zip_code"] = !form.shipping_address.zip_code
    ? "CEP é obrigatório"
    : "";

  return Object.values(errors).every((error) => !error);
}

// Enviar pedido
async function handleSubmit() {
  if (!isAuthenticated.value) {
    router.push({ name: "Login", query: { redirect: "/checkout" } });
    return;
  }

  if (!validateForm()) {
    error.value = "Preencha todos os campos obrigatórios";
    return;
  }

  submitting.value = true;
  error.value = "";

  try {
    const orderData = {
      shipping_address: form.shipping_address,
      billing_address: form.shipping_address, // Mesmo endereço por padrão
      payment_method: form.payment_method,
      coupon_code: cartStore.coupon?.code || null,
      notes: form.notes,
    };

    const response = await createOrder(orderData);

    // Redirecionar para página de sucesso
    router.push({
      name: "CheckoutSuccess",
      params: { orderId: response.order.id },
    });
  } catch (err) {
    error.value = err.response?.data?.message || "Erro ao processar pedido";
  } finally {
    submitting.value = false;
  }
}

// Carregar carrinho ao montar
onMounted(async () => {
  if (!isAuthenticated.value) {
    router.push({ name: "Login", query: { redirect: "/checkout" } });
    return;
  }

  loading.value = true;
  try {
    await cartStore.fetchCart();
  } catch (err) {
    error.value = "Erro ao carregar carrinho";
  } finally {
    loading.value = false;
  }
});
</script>
