<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2
                    class="mt-6 text-center text-3xl font-extrabold text-gray-900"
                >
                    Login Administrativo
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Acesso restrito à administração
                </p>
            </div>
            <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
                <div class="rounded-md shadow-sm -space-y-px">
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
                            placeholder="Email do administrador"
                        />
                    </div>
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
                            placeholder="Senha"
                        />
                    </div>
                </div>
                <div>
                    <button
                        type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :disabled="loading"
                    >
                        <span v-if="loading">Entrando...</span>
                        <span v-else>Entrar</span>
                    </button>
                </div>
                <div v-if="error" class="text-red-600 text-center text-sm">
                    {{ error }}
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuth } from "@/composables/useAuth";

const router = useRouter();
const { login } = useAuth();

const form = ref({
    email: "",
    password: "",
});
const loading = ref(false);
const error = ref(null);

const handleSubmit = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await login(form.value.email, form.value.password);
        // Checa se é admin
        if (response.user && response.user.role === 'admin') {
            router.push("/admin");
        } else {
            error.value = "Acesso restrito: não é administrador.";
        }
    } catch (e) {
        error.value = "Credenciais inválidas ou erro de autenticação.";
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
/* Reaproveita estilos do login padrão */
</style>
