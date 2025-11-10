<template>
    <div
        class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-8 text-white"
    >
        <div class="max-w-md mx-auto text-center">
            <h3 class="text-2xl font-bold mb-4">Receba nossas novidades</h3>
            <p class="text-blue-100 mb-6">
                Inscreva-se em nossa newsletter e seja o primeiro a saber sobre
                novos produtos, ofertas especiais e dicas exclusivas.
            </p>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="Seu melhor e-mail"
                        class="flex-1 px-4 py-3 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50"
                        required
                    />
                    <button
                        type="submit"
                        :disabled="loading"
                        class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        <span v-if="loading">Inscrevendo...</span>
                        <span v-else>Inscrever-se</span>
                    </button>
                </div>

                <div
                    class="flex items-center justify-center space-x-4 text-sm text-blue-100"
                >
                    <label class="flex items-center">
                        <input
                            v-model="form.acceptTerms"
                            type="checkbox"
                            class="mr-2 text-blue-600 focus:ring-white focus:ring-opacity-50"
                            required
                        />
                        Concordo com os
                        <router-link
                            to="/terms"
                            class="underline hover:text-white ml-1"
                        >
                            termos de uso
                        </router-link>
                    </label>
                </div>

                <div
                    v-if="message"
                    :class="
                        messageType === 'success'
                            ? 'text-green-300'
                            : 'text-red-300'
                    "
                    class="text-sm"
                >
                    {{ message }}
                </div>
            </form>

            <div class="mt-6 text-sm text-blue-100">
                <p>
                    Não se preocupe, você pode cancelar a inscrição a qualquer
                    momento.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useNotification } from "@/composables/useNotification";
import pageService from "@/services/pageService";

const { showNotification } = useNotification();

const loading = ref(false);
const message = ref("");
const messageType = ref("");

const form = ref({
    email: "",
    acceptTerms: false,
});

const handleSubmit = async () => {
    if (!form.value.email || !form.value.acceptTerms) {
        message.value = "Por favor, preencha todos os campos obrigatórios.";
        messageType.value = "error";
        return;
    }

    try {
        loading.value = true;
        message.value = "";
        messageType.value = "";

        await pageService.subscribeNewsletter(form.value);

        message.value =
            "Inscrição realizada com sucesso! Verifique seu e-mail para confirmar.";
        messageType.value = "success";
        showNotification("Inscrição realizada com sucesso!", "success");

        // Limpar formulário
        form.value = {
            email: "",
            acceptTerms: false,
        };
    } catch (error) {
        console.error("Erro ao inscrever na newsletter:", error);

        if (error.response?.status === 422) {
            message.value = "Este e-mail já está inscrito em nossa newsletter.";
        } else {
            message.value = "Erro ao realizar inscrição. Tente novamente.";
        }
        messageType.value = "error";
        showNotification("Erro ao inscrever na newsletter", "error");
    } finally {
        loading.value = false;
    }
};
</script>
