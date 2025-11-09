<template>
    <div
        class="min-h-screen bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    >
        <!-- Elementos decorativos de fundo -->
        <div class="absolute inset-0 overflow-hidden">
            <div
                class="absolute top-20 left-20 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"
            ></div>
            <div
                class="absolute top-40 right-20 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"
            ></div>
            <div
                class="absolute bottom-20 left-40 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"
            ></div>
        </div>

        <div class="relative max-w-md w-full">
            <!-- Card principal -->
            <div
                class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-white/20"
            >
                <div class="text-center mb-8">
                    <div class="text-6xl mb-4">🎨</div>
                    <h2 class="text-3xl font-black text-gray-900 mb-2">
                        Bem-vindo de volta!
                    </h2>
                    <p class="text-gray-600">
                        Entre na sua conta para continuar criando
                    </p>
                </div>
                <form class="space-y-6" @submit.prevent="handleLogin">
                    <div class="space-y-5">
                        <Input
                            v-model="formData.email"
                            label="Email"
                            type="email"
                            placeholder="seu@email.com"
                            required
                            :error="
                                errors.email && errors.email !== true
                                    ? errors.email
                                    : null
                            "
                            :class="
                                errors.email && errors.email !== true
                                    ? 'ring-2 ring-red-400'
                                    : ''
                            "
                        />
                        <Input
                            v-model="formData.password"
                            label="Senha"
                            type="password"
                            placeholder="••••••••"
                            required
                            :error="
                                errors.password && errors.password !== true
                                    ? errors.password
                                    : null
                            "
                            :class="
                                errors.password && errors.password !== true
                                    ? 'ring-2 ring-red-400'
                                    : ''
                            "
                        />
                    </div>

                    <div
                        v-if="errors.general"
                        class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm text-center"
                    >
                        {{ errors.general }}
                    </div>

                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 px-6 rounded-xl font-bold text-lg hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span
                            v-if="loading"
                            class="flex items-center justify-center"
                        >
                            <svg
                                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
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
                        <span v-else class="flex items-center justify-center">
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                ></path>
                            </svg>
                            Entrar na Conta
                        </span>
                    </button>

                    <div class="text-center space-y-3">
                        <router-link
                            to="/esqueci-senha"
                            class="text-purple-600 hover:text-purple-800 text-sm font-medium"
                        >
                            Esqueceu sua senha?
                        </router-link>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    <p class="text-gray-600 mb-4">Ainda não tem uma conta?</p>
                    <router-link
                        to="/registrar"
                        class="inline-flex items-center px-6 py-3 border-2 border-purple-600 text-purple-600 font-bold rounded-xl hover:bg-purple-600 hover:text-white transition-all duration-300"
                    >
                        <svg
                            class="w-5 h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                            ></path>
                        </svg>
                        Criar Conta Grátis
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, watch } from "vue";
import { useAuth } from "@/composables/useAuth";
import { validators } from "@/utils/validators";
import Input from "@/components/base/Input.vue";
import Button from "@/components/base/Button.vue";

const { login, loading } = useAuth();

const formData = reactive({
    email: "",
    password: "",
});

const errors = reactive({
    email: null,
    password: null,
    general: null,
});

// Limpar erro do campo ao digitar
Object.keys(formData).forEach((key) => {
    watch(
        () => formData[key],
        () => {
            errors[key] = null;
            errors.general = null;
            // Validação em tempo real
            if (formData[key]) {
                if (key === "email") {
                    errors.email = validators.email(formData.email);
                } else if (key === "password") {
                    errors.password = validators.required(formData.password);
                }
            }
        }
    );
});
function validate() {
    errors.email = validators.email(formData.email);
    errors.password = validators.required(formData.password);
    return Object.values(errors).every((e) => e === true || e === null);
}

async function handleLogin() {
    errors.general = null;
    if (!validate()) return;
    try {
        await login(formData.email, formData.password);
    } catch (err) {
        if (err.response?.data?.errors) {
            Object.assign(errors, err.response.data.errors);
        } else {
            errors.general =
                err.response?.data?.message || "Email ou senha inválidos";
        }
    }
}
</script>
