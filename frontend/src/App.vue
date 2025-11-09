<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useAuth } from "@/composables/useAuth";

const { fetchMe, isAuthenticated } = useAuth();

onMounted(async () => {
  // Se tem token, tentar carregar dados do usuário
  if (isAuthenticated.value) {
    try {
      await fetchMe();
    } catch (error) {
      console.error("Erro ao carregar usuário:", error);
    }
  }
});
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap");
@tailwind base;
@tailwind components;
@tailwind utilities;

body {
  font-family: "Inter", sans-serif;
}
</style>
