<template>
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full">
            <!-- Card principal -->
            <div
                class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden"
            >
                <!-- Header com gradiente -->
                <div
                    class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 px-8 py-12 text-center"
                >
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-full mb-6"
                    >
                        <i class="fas fa-flip-flops text-4xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        Bem-vindo de volta!
                    </h1>
                    <p class="text-blue-100 text-lg">
                        Entre na sua conta EstampariaPro
                    </p>
                </div>

                <!-- Formulário -->
                <div class="px-8 py-8">
                    <Transition name="form" appear>
                        <form class="space-y-6" @submit.prevent="handleSubmit">
                            <!-- Campo Email -->
                            <div>
                                <label
                                    for="email"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <i
                                        class="fas fa-envelope mr-2 text-blue-600"
                                    ></i>
                                    Email
                                </label>
                                <div class="relative">
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        name="email"
                                        type="email"
                                        autocomplete="email"
                                        required
                                        class="w-full px-4 py-3 pl-12 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder-gray-400"
                                        :class="{
                                            'border-red-300 focus:ring-red-100 focus:border-red-500':
                                                errors.email,
                                        }"
                                        placeholder="seu@email.com"
                                    />
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                                    >
                                        <i
                                            class="fas fa-envelope text-gray-400"
                                        ></i>
                                    </div>
                                </div>
                                <Transition name="error" mode="out-in">
                                    <div
                                        v-if="errors.email"
                                        class="mt-2 text-sm text-red-600 flex items-center"
                                    >
                                        <i
                                            class="fas fa-exclamation-circle mr-1"
                                        ></i>
                                        {{ errors.email[0] }}
                                    </div>
                                </Transition>
                            </div>

                            <!-- Campo Senha -->
                            <div>
                                <label
                                    for="password"
                                    class="block text-sm font-semibold text-gray-700 mb-2"
                                >
                                    <i
                                        class="fas fa-lock mr-2 text-blue-600"
                                    ></i>
                                    Senha
                                </label>
                                <div class="relative">
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        name="password"
                                        :type="
                                            showPassword ? 'text' : 'password'
                                        "
                                        autocomplete="current-password"
                                        required
                                        class="w-full px-4 py-3 pl-12 pr-12 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder-gray-400"
                                        :class="{
                                            'border-red-300 focus:ring-red-100 focus:border-red-500':
                                                errors.password,
                                        }"
                                        placeholder="••••••••"
                                    />
                                    <div
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
                                    >
                                        <i
                                            class="fas fa-lock text-gray-400"
                                        ></i>
                                    </div>
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center hover:text-blue-600 transition-colors"
                                    >
                                        <i
                                            :class="
                                                showPassword
                                                    ? 'fas fa-eye-slash'
                                                    : 'fas fa-eye'
                                            "
                                        ></i>
                                    </button>
                                </div>
                                <Transition name="error" mode="out-in">
                                    <div
                                        v-if="errors.password"
                                        class="mt-2 text-sm text-red-600 flex items-center"
                                    >
                                        <i
                                            class="fas fa-exclamation-circle mr-1"
                                        ></i>
                                        {{ errors.password[0] }}
                                    </div>
                                </Transition>
                            </div>

                            <!-- Lembrar-me e Esqueci a senha -->
                            <div class="flex items-center justify-between">
                                <label class="flex items-center">
                                    <input
                                        v-model="form.remember"
                                        type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                    />
                                    <span
                                        class="ml-2 text-sm text-gray-600 font-medium"
                                    >
                                        Lembrar-me
                                    </span>
                                </label>

                                <router-link
                                    to="/esqueci-senha"
                                    class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors"
                                >
                                    Esqueci a senha?
                                </router-link>
                            </div>

                            <!-- Erro geral -->
                            <Transition name="error" mode="out-in">
                                <div
                                    v-if="errors.general"
                                    class="bg-red-50 border border-red-200 rounded-xl p-4"
                                >
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <i
                                                class="fas fa-exclamation-triangle text-red-400"
                                            ></i>
                                        </div>
                                        <div class="ml-3">
                                            <p
                                                class="text-sm text-red-800 font-medium"
                                            >
                                                {{ errors.general }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </Transition>

                            <!-- Botão de envio -->
                            <button
                                type="submit"
                                :disabled="loading"
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-blue-100 transform hover:scale-[1.02] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg hover:shadow-xl"
                            >
                                <span
                                    v-if="!loading"
                                    class="flex items-center justify-center"
                                >
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Entrar
                                </span>
                                <span
                                    v-else
                                    class="flex items-center justify-center"
                                >
                                    <svg
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
                                    Entrando...
                                </span>
                            </button>

                            <!-- Separador -->
                            <div class="relative my-8">
                                <div class="absolute inset-0 flex items-center">
                                    <div
                                        class="w-full border-t border-gray-200"
                                    ></div>
                                </div>
                                <div
                                    class="relative flex justify-center text-sm"
                                >
                                    <span
                                        class="px-4 bg-white text-gray-500 font-medium"
                                        >Ou continue com</span
                                    >
                                </div>
                            </div>

                            <!-- Login social -->
                            <div class="grid grid-cols-2 gap-4">
                                <button
                                    type="button"
                                    @click="loginWithGoogle"
                                    class="flex items-center justify-center px-4 py-3 border-2 border-gray-200 rounded-xl hover:border-gray-300 hover:bg-gray-50 transition-all duration-200 group"
                                >
                                    <svg
                                        class="w-5 h-5 mr-3"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            fill="currentColor"
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                        />
                                        <path
                                            fill="currentColor"
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                        />
                                        <path
                                            fill="currentColor"
                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                        />
                                        <path
                                            fill="currentColor"
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                        />
                                    </svg>
                                    <span
                                        class="text-sm font-medium text-gray-700 group-hover:text-gray-900"
                                        >Google</span
                                    >
                                </button>

                                <button
                                    type="button"
                                    @click="loginWithFacebook"
                                    class="flex items-center justify-center px-4 py-3 border-2 border-gray-200 rounded-xl hover:border-gray-300 hover:bg-gray-50 transition-all duration-200 group"
                                >
                                    <svg
                                        class="w-5 h-5 mr-3 text-blue-600"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span
                                        class="text-sm font-medium text-gray-700 group-hover:text-gray-900"
                                        >Facebook</span
                                    >
                                </button>
                            </div>
                        </form>
                    </Transition>

                    <!-- Link para cadastro -->
                    <div class="mt-8 text-center">
                        <p class="text-gray-600">
                            Não tem uma conta?
                            <router-link
                                to="/registrar"
                                class="font-semibold text-blue-600 hover:text-blue-800 transition-colors"
                            >
                                Criar conta gratuita
                            </router-link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useAuth } from "@/composables/useAuth";
import { validateEmail, validateRequired } from "@/utils/validators";

// Composables
const { login, loading, error } = useAuth();

// Estado do formulário
const form = reactive({
    email: "",
    password: "",
    remember: false,
});

// Mostrar/ocultar senha
const showPassword = ref(false);

// Erros de validação
const errors = ref({});

// Validar formulário
const validateForm = () => {
    errors.value = {};

    // Validar email
    const emailError = validateEmail(form.email);
    if (emailError !== true) {
        errors.value.email = [emailError];
    }

    // Validar senha
    const passwordError = validateRequired(form.password, "senha");
    if (passwordError !== true) {
        errors.value.password = [passwordError];
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

    try {
        await login(form.email, form.password, form.remember);
    } catch (err) {
        // Tratar erros específicos
        if (err.response?.status === 422) {
            // Erros de validação do servidor
            errors.value = err.response.data.errors || {};
        } else if (err.response?.status === 401) {
            // Credenciais inválidas
            errors.value.general = "Email ou senha incorretos";
        } else {
            // Outros erros
            errors.value.general =
                err.response?.data?.message || "Erro ao fazer login";
        }
    }
};

// Login com Google (implementar conforme necessário)
const loginWithGoogle = () => {
    console.log("Login com Google - implementar");
    // Implementar OAuth com Google
};

// Login com Facebook (implementar conforme necessário)
const loginWithFacebook = () => {
    console.log("Login com Facebook - implementar");
    // Implementar OAuth com Facebook
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

/* Transição para o formulário */
.form-enter-active {
    transition:
        opacity 0.5s ease,
        transform 0.5s ease;
}

.form-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

/* Estilos customizados se necessário */
</style>
