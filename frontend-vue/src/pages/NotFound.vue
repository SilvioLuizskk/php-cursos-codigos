<template>
    <div
        class="not-found min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50 relative overflow-hidden"
    >
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div
                class="absolute inset-0"
                style="
                    background-image: radial-gradient(
                        circle at 25% 25%,
                        #3b82f6 2px,
                        transparent 2px
                    );
                    background-size: 40px 40px;
                "
            ></div>
        </div>

        <div class="text-center relative z-10 px-4 sm:px-6 lg:px-8">
            <Transition name="bounce-in" appear>
                <div class="mb-12">
                    <div class="relative">
                        <i
                            class="fas fa-search text-9xl text-blue-400 mb-6 animate-pulse"
                        ></i>
                        <div
                            class="absolute -top-4 -right-4 w-16 h-16 bg-red-500 rounded-full flex items-center justify-center animate-bounce"
                        >
                            <span class="text-white font-bold text-xl">?</span>
                        </div>
                    </div>
                    <h1
                        class="text-7xl md:text-8xl font-bold text-gray-900 mb-4 animate-pulse-slow"
                    >
                        404
                    </h1>
                    <h2
                        class="text-3xl md:text-4xl font-semibold text-gray-700 mb-4"
                    >
                        Página não encontrada
                    </h2>
                    <p
                        class="text-xl text-gray-600 mb-8 max-w-lg mx-auto leading-relaxed"
                    >
                        Oops! Parece que você se perdeu no caminho. A página que
                        você está procurando não existe ou foi movida.
                    </p>
                </div>
            </Transition>

            <Transition name="fade-up" appear :delay="300">
                <div class="space-y-6">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <router-link
                            to="/"
                            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 inline-flex items-center justify-center transform hover:scale-105 shadow-lg hover:shadow-xl"
                        >
                            <i class="fas fa-home mr-3"></i>
                            Voltar ao Início
                        </router-link>

                        <router-link
                            to="/produtos"
                            class="border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-xl font-semibold hover:bg-blue-600 hover:text-white transition-all duration-300 inline-flex items-center justify-center transform hover:scale-105 shadow-lg hover:shadow-xl"
                        >
                            <i class="fas fa-shopping-bag mr-3"></i>
                            Ver Produtos
                        </router-link>
                    </div>

                    <button
                        @click="goBack"
                        class="text-gray-600 hover:text-gray-800 font-medium inline-flex items-center transition-colors duration-300 hover:scale-105"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar à página anterior
                    </button>
                </div>
            </Transition>

            <!-- Quick Links -->
            <Transition name="fade-up" appear :delay="600">
                <div class="mt-16">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">
                        Links Rápidos
                    </h3>
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 max-w-2xl mx-auto"
                    >
                        <router-link
                            v-for="link in quickLinks"
                            :key="link.path"
                            :to="link.path"
                            class="group p-4 bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-gray-100"
                        >
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:from-blue-200 group-hover:to-purple-200 transition-colors duration-300"
                                >
                                    <i
                                        :class="link.icon"
                                        class="text-blue-600"
                                    ></i>
                                </div>
                                <div class="text-left">
                                    <div
                                        class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors"
                                    >
                                        {{ link.title }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ link.description }}
                                    </div>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </Transition>

            <!-- Search -->
            <Transition name="fade-up" appear :delay="900">
                <div class="mt-12 max-w-md mx-auto">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h4
                            class="font-semibold text-gray-900 mb-4 flex items-center"
                        >
                            <i class="fas fa-search text-blue-600 mr-2"></i>
                            Procurar algo específico?
                        </h4>
                        <div class="flex gap-2">
                            <input
                                v-model="searchQuery"
                                @keyup.enter="search"
                                type="text"
                                placeholder="Digite sua busca..."
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            />
                            <button
                                @click="search"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200"
                            >
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>

            <!-- Links Populares -->
            <div class="mt-12">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Links populares:
                </h3>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <router-link
                        to="/"
                        class="text-blue-600 hover:text-blue-800 underline"
                    >
                        Página Inicial
                    </router-link>
                    <router-link
                        to="/produtos"
                        class="text-blue-600 hover:text-blue-800 underline"
                    >
                        Produtos
                    </router-link>
                    <router-link
                        to="/carrinho"
                        class="text-blue-600 hover:text-blue-800 underline"
                    >
                        Carrinho
                    </router-link>
                </div>
            </div>

            <!-- Contact Support -->
            <div
                class="mt-8 p-4 bg-white rounded-lg shadow-sm max-w-md mx-auto"
            >
                <h4 class="font-semibold text-gray-900 mb-2">
                    Precisa de ajuda?
                </h4>
                <p class="text-sm text-gray-600 mb-3">
                    Se você acha que isso é um erro, entre em contato conosco.
                </p>
                <a
                    href="mailto:suporte@estampariapro.com"
                    class="text-blue-600 hover:text-blue-800 font-medium text-sm inline-flex items-center"
                >
                    <i class="fas fa-envelope mr-2"></i>
                    suporte@estampariapro.com
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";

export default {
    name: "NotFound",
    setup() {
        const searchQuery = ref("");

        const quickLinks = [
            {
                title: "Produtos",
                description: "Ver nossa coleção",
                path: "/produtos",
                icon: "fas fa-tshirt",
            },
            {
                title: "Sobre",
                description: "Conheça nossa história",
                path: "/sobre",
                icon: "fas fa-info-circle",
            },
            {
                title: "Contato",
                description: "Fale conosco",
                path: "/contato",
                icon: "fas fa-envelope",
            },
        ];

        const goBack = () => {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = "/";
            }
        };

        const search = () => {
            if (searchQuery.value.trim()) {
                window.location.href = `/produtos?search=${encodeURIComponent(searchQuery.value)}`;
            }
        };

        return {
            searchQuery,
            quickLinks,
            goBack,
            search,
        };
    },
};
</script>

<style scoped>
@keyframes pulse-slow {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse-slow {
    animation: pulse-slow 3s ease-in-out infinite;
}

.bounce-in-enter-active,
.bounce-in-leave-active {
    transition: all 0.8s ease;
}

.bounce-in-enter-from {
    opacity: 0;
    transform: scale(0.3);
}

.bounce-in-enter-to {
    opacity: 1;
    transform: scale(1);
}

.fade-up-enter-active,
.fade-up-leave-active {
    transition: all 0.6s ease;
}

.fade-up-enter-from {
    opacity: 0;
    transform: translateY(30px);
}

.fade-up-enter-to {
    opacity: 1;
    transform: translateY(0);
}
</style>
