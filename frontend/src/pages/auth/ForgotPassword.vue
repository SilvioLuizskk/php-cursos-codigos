<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8 p-8">
      <div class="text-center">
        <h2 class="text-3xl font-extrabold text-gray-900">
          Esqueceu sua senha?
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Digite seu email para receber instruções de recuperação
        </p>
      </div>

      <form @submit.prevent="handleForgotPassword" class="space-y-6">
        <Input
          v-model="email"
          label="Email"
          type="email"
          placeholder="Seu email"
          required
        />

        <Button
          type="submit"
          variant="primary"
          size="lg"
          class="w-full"
          :loading="loading"
        >
          Enviar Link de Recuperação
        </Button>

        <div class="text-center">
          <router-link to="/login" class="text-blue-600 hover:text-blue-500">
            Voltar ao login
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { authService } from "@/api/authService";
import Input from "@/components/base/Input.vue";
import Button from "@/components/base/Button.vue";

const email = ref("");
const loading = ref(false);

async function handleForgotPassword() {
  loading.value = true;
  try {
    await authService.forgotPassword(email.value);
    alert("Link de recuperação enviado para seu email!");
  } catch (err) {
    console.error("Erro:", err);
    alert("Erro ao enviar email de recuperação");
  } finally {
    loading.value = false;
  }
}
</script>
