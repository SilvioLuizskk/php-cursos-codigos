<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
  >
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div>
        <div class="mx-auto h-12 w-auto text-center">
          <i class="fas fa-flip-flops text-4xl text-blue-600"></i>
          <h1 class="text-2xl font-bold text-gray-900 mt-2">EstampariaPro</h1>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Entre na sua conta
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Ou
          <router-link
            to="/registrar"
            class="font-medium text-indigo-600 hover:text-indigo-500"
          >
            crie uma nova conta
          </router-link>
        </p>
      </div>

      <!-- Formulário -->
      <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <input type="hidden" name="remember" :value="form.remember" />

        <div class="rounded-md shadow-sm -space-y-px">
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
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              :class="{
                'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500':
                  errors.email,
              }"
              placeholder="Endereço de email"
            />
            <div v-if="errors.email" class="mt-1 text-sm text-red-600">
              {{ errors.email[0] }}
            </div>
          </div>

          <!-- Senha -->
          <div>
            <label for="password" class="sr-only">Senha</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="current-password"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              :class="{
                'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500':
                  errors.password,
              }"
              placeholder="Senha"
            />
            <div v-if="errors.password" class="mt-1 text-sm text-red-600">
              {{ errors.password[0] }}
            </div>
          </div>
        </div>

        <!-- Lembrar-me e Esqueci a senha -->
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember-me"
              v-model="form.remember"
              name="remember-me"
              type="checkbox"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
            />
            <label for="remember-me" class="ml-2 block text-sm text-gray-900">
              Lembrar-me
            </label>
          </div>

          <div class="text-sm">
            <router-link
              to="/esqueci-senha"
              class="font-medium text-indigo-600 hover:text-indigo-500"
            >
              Esqueci minha senha
            </router-link>
          </div>
        </div>

        <!-- Erro geral -->
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

        <!-- Botão de envio -->
        <div>
          <button
            type="submit"
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg
                v-if="!loading"
                class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
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
            {{ loading ? "Entrando..." : "Entrar" }}
          </button>
        </div>

        <!-- Login social -->
        <div class="mt-6">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300" />
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-gray-50 text-gray-500">Ou continue com</span>
            </div>
          </div>

          <div class="mt-6 grid grid-cols-2 gap-3">
            <!-- Google -->
            <button
              type="button"
              class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
              @click="loginWithGoogle"
            >
              <span class="sr-only">Entrar com Google</span>
              <svg class="w-5 h-5" viewBox="0 0 24 24">
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
            </button>

            <!-- Facebook -->
            <button
              type="button"
              class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
              @click="loginWithFacebook"
            >
              <span class="sr-only">Entrar com Facebook</span>
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path
                  fill-rule="evenodd"
                  d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z"
                  clip-rule="evenodd"
                />
              </svg>
            </button>
          </div>
        </div>
      </form>
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
/* Estilos customizados se necessário */
</style>
