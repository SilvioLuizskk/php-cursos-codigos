<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 text-center">
            Por que comprar conosco?
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Free Shipping -->
            <div class="flex flex-col items-center text-center">
                <div
                    class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-3"
                >
                    <i class="fas fa-truck text-2xl text-blue-600"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Frete Grátis</h4>
                <p class="text-sm text-gray-600">
                    Entrega gratuita para compras acima de R$ 99,00
                </p>
            </div>

            <!-- Secure Payment -->
            <div class="flex flex-col items-center text-center">
                <div
                    class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-3"
                >
                    <i class="fas fa-shield-alt text-2xl text-green-600"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">
                    Pagamento Seguro
                </h4>
                <p class="text-sm text-gray-600">
                    Seus dados estão protegidos com criptografia SSL
                </p>
            </div>

            <!-- Quality Guarantee -->
            <div class="flex flex-col items-center text-center">
                <div
                    class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-3"
                >
                    <i class="fas fa-award text-2xl text-yellow-600"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">
                    Garantia de Qualidade
                </h4>
                <p class="text-sm text-gray-600">
                    Produtos de alta qualidade com garantia estendida
                </p>
            </div>

            <!-- Customer Support -->
            <div class="flex flex-col items-center text-center">
                <div
                    class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-3"
                >
                    <i class="fas fa-headset text-2xl text-purple-600"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Suporte 24/7</h4>
                <p class="text-sm text-gray-600">
                    Atendimento ao cliente disponível a qualquer hora
                </p>
            </div>
        </div>

        <!-- Additional Trust Elements -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div
                class="flex flex-wrap justify-center items-center gap-6 text-sm text-gray-600"
            >
                <div class="flex items-center">
                    <i class="fas fa-lock text-green-500 mr-2"></i>
                    <span>Site Seguro</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-undo text-blue-500 mr-2"></i>
                    <span>Devolução Fácil</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-clock text-orange-500 mr-2"></i>
                    <span>Entrega Rápida</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    <span>Avaliado com 4.8/5</span>
                </div>
            </div>
        </div>

        <!-- Security Badges -->
        <div class="mt-6 flex justify-center">
            <div class="flex items-center space-x-4">
                <div class="flex items-center bg-gray-100 px-3 py-2 rounded">
                    <i class="fab fa-cc-visa text-blue-600 mr-2"></i>
                    <span class="text-xs text-gray-700">Visa</span>
                </div>
                <div class="flex items-center bg-gray-100 px-3 py-2 rounded">
                    <i class="fab fa-cc-mastercard text-red-600 mr-2"></i>
                    <span class="text-xs text-gray-700">Mastercard</span>
                </div>
                <div class="flex items-center bg-gray-100 px-3 py-2 rounded">
                    <i class="fab fa-cc-amex text-blue-800 mr-2"></i>
                    <span class="text-xs text-gray-700">Amex</span>
                </div>
                <div class="flex items-center bg-gray-100 px-3 py-2 rounded">
                    <i class="fab fa-cc-paypal text-blue-500 mr-2"></i>
                    <span class="text-xs text-gray-700">PayPal</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
// Este componente não precisa de lógica específica, apenas exibe elementos de confiança
</script>

<style scoped>
/* Estilos adicionais se necessário */
</style>
