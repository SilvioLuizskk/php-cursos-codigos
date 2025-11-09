<template>
  <div
    class="min-h-screen bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 flex items-center justify-center p-4"
  >
    <div class="absolute inset-0 bg-black/20"></div>

    <div class="relative w-full max-w-md">
      <div class="text-center mb-8">
        <div
          class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur-sm rounded-full mb-4"
        >
          <svg
            class="w-8 h-8 text-white"
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
        </div>
        <h1 class="text-4xl font-bold text-white mb-2">Criar Conta</h1>
        <p class="text-white/80 text-lg">
          Junte-se à nossa comunidade de clientes
        </p>
      </div>

      <div
        class="bg-white/10 backdrop-blur-md rounded-2xl p-8 shadow-2xl border border-white/20"
      >
        <form class="space-y-6" @submit.prevent="handleRegister">
          <div class="space-y-5">
            <Input
              v-model="formData.name"
              label="Nome completo"
              placeholder="Seu nome completo"
              required
            />

            <Input
              v-model="formData.email"
              label="Email"
              type="email"
              placeholder="seu@email.com"
              required
            />

            <Input
              v-model="formData.password"
              label="Senha"
              type="password"
              placeholder="••••••••"
              required
            />

            <Input
              v-model="formData.password_confirmation"
              label="Confirmar senha"
              type="password"
              placeholder="••••••••"
              required
            />
          </div>

          <div
            v-if="error"
            class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm text-center"
          >
            {{ error }}
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-gradient-to-r from-green-500 to-blue-600 text-white py-4 px-6 rounded-xl font-bold text-lg hover:from-green-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="flex items-center justify-center">
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
              Criando conta...
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
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                ></path>
              </svg>
              Criar Conta Grátis
            </span>
          </button>
        </form>

        <div class="mt-8 pt-6 border-t border-white/20 text-center">
          <p class="text-white/80 mb-4">Já tem uma conta?</p>
          <router-link
            to="/login"
            class="inline-flex items-center px-6 py-3 border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/10 transition-all duration-300"
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
                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"
              ></path>
            </svg>
            Fazer Login
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from "vue";
import { useAuth } from "@/composables/useAuth";
import Input from "@/components/base/Input.vue";
import Button from "@/components/base/Button.vue";

const { register, loading, error } = useAuth();

const formData = reactive({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

async function handleRegister() {
  try {
    await register(formData);
  } catch (err) {
    console.error("Erro ao registrar:", err);
  }
}
</script>
