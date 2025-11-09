<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div>
                <div class="mx-auto h-12 w-auto text-center">
                    <i class="fas fa-flip-flops text-4xl text-blue-600"></i>
                    <h1 class="text-2xl font-bold text-gray-900 mt-2">
                        EstampariaPro
                    </h1>
                </div>
                <h2
                    class="mt-6 text-center text-3xl font-extrabold text-gray-900"
                >
                    Esqueceu sua senha?
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Digite seu email e enviaremos um link para redefinir sua
                    senha.
                </p>
            </div>

            <!-- Formulário -->
            <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
                <div class="rounded-md shadow-sm">
                    <!-- Email -->
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            :class="{
                                'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500':
                                    errors.email,
                            }"
                            placeholder="Endereço de email"
                        />
                        <Transition name="error" mode="out-in">
                            <div
                                v-if="errors.email"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.email[0] }}
                            </div>
                        </Transition>
                    </div>
                </div>

                <!-- Erro geral -->
                <Transition name="error" mode="out-in">
                    <div v-if="errors.general" class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-5 w-5 text-red-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    {{ errors.general }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </Transition>

                <!-- Mensagem de sucesso -->
                <Transition name="success" mode="out-in">
                    <div v-if="success" class="rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-5 w-5 text-green-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">
                                    {{ success }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </Transition>

                <!-- Botão de envio -->
                <div>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span
                            class="absolute left-0 inset-y-0 flex items-center pl-3"
                        >
                            <svg
                                v-if="!loading"
                                class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"
                                />
                                <path
                                    d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"
                                />
                            </svg>
                            <svg
                                v-else
                                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
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
                        {{
                            loading
                                ? "Enviando..."
                                : "Enviar link de redefinição"
                        }}
                    </button>
                </div>

                <!-- Voltar ao login -->
                <div class="text-center">
                    <router-link
                        to="/login"
                        class="font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        Voltar ao login
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { validateEmail } from "@/utils/validators";

// Estado do formulário
const form = reactive({
    email: "",
});

// Loading
const loading = ref(false);

// Erros de validação
const errors = ref({});

// Mensagem de sucesso
const success = ref("");

// Validar formulário
const validateForm = () => {
    errors.value = {};
    success.value = "";

    // Validar email
    const emailError = validateEmail(form.email);
    if (emailError !== true) {
        errors.value.email = [emailError];
    }

    return Object.keys(errors.value).length === 0;
};

// Enviar formulário
const handleSubmit = async () => {
    // Limpar erros anteriores
    errors.value = {};

    // Validar formulário
    if (!validateForm()) {
        return;
    }

    loading.value = true;

    try {
        // Aqui você implementaria a chamada para enviar o email de redefinição
        // Por exemplo: await forgotPassword(form.email);

        // Simulação de sucesso
        console.log("Email de redefinição enviado para", form.email);
        success.value = "Um link de redefinição foi enviado para seu email.";
    } catch (err) {
        // Tratar erros
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors || {};
        } else {
            errors.value.general =
                err.response?.data?.message || "Erro ao enviar email";
        }
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
/* Transições para erros */
.error-enter-active,
.error-leave-active {
    transition: opacity 0.3s ease;
}

.error-enter-from,
.error-leave-to {
    opacity: 0;
}

/* Transições para sucesso */
.success-enter-active,
.success-leave-active {
    transition: opacity 0.3s ease;
}

.success-enter-from,
.success-leave-to {
    opacity: 0;
}
</style>
