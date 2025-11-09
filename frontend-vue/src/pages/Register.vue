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
                    Criar nova conta
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Já tem uma conta?
                    <router-link
                        to="/login"
                        class="font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        Faça login
                    </router-link>
                </p>
            </div>

            <!-- Formulário -->
            <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
                <div class="rounded-md shadow-sm -space-y-px">
                    <!-- Nome -->
                    <div>
                        <label for="name" class="sr-only">Nome</label>
                        <input
                            id="name"
                            v-model="form.name"
                            name="name"
                            type="text"
                            autocomplete="name"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            :class="{
                                'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500':
                                    errors.name,
                            }"
                            placeholder="Nome completo"
                        />
                        <Transition name="error" mode="out-in">
                            <div
                                v-if="errors.name"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.name[0] }}
                            </div>
                        </Transition>
                    </div>

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
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
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

                    <!-- Senha -->
                    <div class="relative">
                        <label for="password" class="sr-only">Senha</label>
                        <input
                            id="password"
                            v-model="form.password"
                            name="password"
                            :type="showPassword ? 'text' : 'password'"
                            autocomplete="new-password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            :class="{
                                'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500':
                                    errors.password,
                            }"
                            placeholder="Senha"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400 hover:text-gray-600"
                                :class="{ 'text-indigo-500': showPassword }"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    v-if="!showPassword"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                                <path
                                    v-if="!showPassword"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                />
                                <path
                                    v-if="showPassword"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                                />
                            </svg>
                        </button>
                        <Transition name="error" mode="out-in">
                            <div
                                v-if="errors.password"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.password[0] }}
                            </div>
                        </Transition>
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label for="password_confirmation" class="sr-only"
                            >Confirmar Senha</label
                        >
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            name="password_confirmation"
                            :type="showPassword ? 'text' : 'password'"
                            autocomplete="new-password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            :class="{
                                'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500':
                                    errors.password_confirmation,
                            }"
                            placeholder="Confirmar senha"
                        />
                        <Transition name="error" mode="out-in">
                            <div
                                v-if="errors.password_confirmation"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ errors.password_confirmation[0] }}
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
                                    fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd"
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
                        {{ loading ? "Criando conta..." : "Criar conta" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import {
    validateEmail,
    validateRequired,
    validatePassword,
} from "@/utils/validators";

// Composables
const router = useRouter();

// Estado do formulário
const form = reactive({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

// Mostrar/ocultar senha
const showPassword = ref(false);

// Loading
const loading = ref(false);

// Erros de validação
const errors = ref({});

// Validar formulário
const validateForm = () => {
    errors.value = {};

    // Validar nome
    const nameError = validateRequired(form.name, "nome");
    if (nameError !== true) {
        errors.value.name = [nameError];
    }

    // Validar email
    const emailError = validateEmail(form.email);
    if (emailError !== true) {
        errors.value.email = [emailError];
    }

    // Validar senha
    const passwordError = validatePassword(form.password);
    if (passwordError !== true) {
        errors.value.password = [passwordError];
    }

    // Validar confirmação de senha
    if (form.password !== form.password_confirmation) {
        errors.value.password_confirmation = ["As senhas não coincidem"];
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
        // Aqui você implementaria a chamada para registrar o usuário
        // Por exemplo: await register(form.name, form.email, form.password);

        // Simulação de sucesso
        console.log("Registro bem-sucedido", form);

        // Redirecionar para login ou dashboard
        router.push("/login");
    } catch (err) {
        // Tratar erros
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors || {};
        } else {
            errors.value.general =
                err.response?.data?.message || "Erro ao criar conta";
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
</style>
