<template>
    <div class="contact-page min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Entre em Contato
                </h1>
                <p class="text-xl text-gray-600">
                    Estamos aqui para ajudar! Envie sua mensagem e responderemos
                    o mais breve possível.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Formulário de Contato -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                        Envie sua Mensagem
                    </h2>

                    <form @submit.prevent="submitContactForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Nome Completo *
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-2"
                                >
                                    Email *
                                </label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                />
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Telefone
                            </label>
                            <input
                                v-model="form.phone"
                                type="tel"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Assunto *
                            </label>
                            <select
                                v-model="form.subject"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            >
                                <option value="">Selecione um assunto</option>
                                <option value="pedido">
                                    Informações sobre pedido
                                </option>
                                <option value="produto">
                                    Dúvidas sobre produto
                                </option>
                                <option value="devolucao">
                                    Devolução/Troca
                                </option>
                                <option value="pagamento">
                                    Problemas de pagamento
                                </option>
                                <option value="entrega">
                                    Informações de entrega
                                </option>
                                <option value="reclamacao">Reclamação</option>
                                <option value="elogio">Elogio/Sugestão</option>
                                <option value="outros">Outros</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Número do Pedido (se aplicável)
                            </label>
                            <input
                                v-model="form.orderNumber"
                                type="text"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                placeholder="Ex: #12345"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Mensagem *
                            </label>
                            <textarea
                                v-model="form.message"
                                required
                                rows="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-vertical"
                                placeholder="Descreva sua dúvida ou solicitação em detalhes..."
                            ></textarea>
                        </div>

                        <div class="flex items-center">
                            <input
                                v-model="form.acceptTerms"
                                type="checkbox"
                                required
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label class="ml-2 block text-sm text-gray-700">
                                Concordo com a
                                <router-link
                                    to="/privacidade"
                                    class="text-blue-600 hover:text-blue-500"
                                >
                                    Política de Privacidade
                                </router-link>
                                e
                                <router-link
                                    to="/termos"
                                    class="text-blue-600 hover:text-blue-500"
                                >
                                    Termos de Uso
                                </router-link>
                            </label>
                        </div>

                        <button
                            type="submit"
                            :disabled="submitting"
                            class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200"
                        >
                            {{ submitting ? "Enviando..." : "Enviar Mensagem" }}
                        </button>
                    </form>
                </div>

                <!-- Informações de Contato -->
                <div class="space-y-8">
                    <!-- Informações da Empresa -->
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                            Informações de Contato
                        </h2>

                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                                >
                                    <i
                                        class="fas fa-envelope text-blue-600"
                                    ></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">
                                        Email
                                    </h3>
                                    <p class="text-gray-600">
                                        contato@chineloskaribe.com
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Respondemos em até 24 horas
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center"
                                >
                                    <i class="fas fa-phone text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">
                                        Telefone
                                    </h3>
                                    <p class="text-gray-600">(11) 9999-9999</p>
                                    <p class="text-sm text-gray-500">
                                        Seg a Sex, 9h às 18h
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center"
                                >
                                    <i class="fas fa-clock text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">
                                        Horário de Atendimento
                                    </h3>
                                    <p class="text-gray-600">
                                        Segunda a Sexta: 9h às 18h
                                    </p>
                                    <p class="text-gray-600">
                                        Sábado: 9h às 12h
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Domingo: Fechado
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Rápido -->
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                            Perguntas Frequentes
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">
                                    Quanto tempo leva para entregar?
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Em média 7-15 dias úteis após a produção.
                                </p>
                            </div>

                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">
                                    Posso cancelar meu pedido?
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Sim, se ainda não foi produzido. Entre em
                                    contato.
                                </p>
                            </div>

                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">
                                    Como faço para trocar um produto?
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    Aceitamos trocas em até 7 dias após o
                                    recebimento.
                                </p>
                            </div>

                            <div class="pt-4">
                                <router-link
                                    to="/faq"
                                    class="text-blue-600 hover:text-blue-500 font-medium"
                                >
                                    Ver todas as perguntas frequentes →
                                </router-link>
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociais -->
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">
                            Redes Sociais
                        </h2>

                        <div class="flex space-x-4">
                            <a
                                href="#"
                                class="w-12 h-12 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center text-white transition-colors"
                            >
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a
                                href="#"
                                class="w-12 h-12 bg-pink-600 hover:bg-pink-700 rounded-lg flex items-center justify-center text-white transition-colors"
                            >
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a
                                href="#"
                                class="w-12 h-12 bg-blue-400 hover:bg-blue-500 rounded-lg flex items-center justify-center text-white transition-colors"
                            >
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a
                                href="#"
                                class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center text-white transition-colors"
                            >
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useNotification } from "@/composables/useNotification";

const { showNotification } = useNotification();

// Estado do formulário
const form = ref({
    name: "",
    email: "",
    phone: "",
    subject: "",
    orderNumber: "",
    message: "",
    acceptTerms: false,
});

const submitting = ref(false);

// Método para enviar formulário
const submitContactForm = async () => {
    submitting.value = true;

    try {
        // Aqui seria a chamada para a API
        console.log("Enviando formulário de contato:", form.value);

        // Simular envio
        await new Promise((resolve) => setTimeout(resolve, 2000));

        // Limpar formulário
        form.value = {
            name: "",
            email: "",
            phone: "",
            subject: "",
            orderNumber: "",
            message: "",
            acceptTerms: false,
        };

        showNotification(
            "Mensagem enviada com sucesso! Responderemos em breve.",
            "success",
        );
    } catch (error) {
        console.error("Erro ao enviar formulário:", error);
        showNotification("Erro ao enviar mensagem. Tente novamente.", "error");
    } finally {
        submitting.value = false;
    }
};
</script>

<style scoped>
/* Estilos adicionais se necessário */
</style>
