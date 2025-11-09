<template>
    <div id="app" class="min-h-screen bg-gray-50">
        <!-- Header/Navbar -->
        <header class="bg-white shadow-sm border-b">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <router-link to="/" class="flex items-center space-x-2">
                            <i
                                class="fas fa-flip-flops text-2xl text-blue-600"
                            ></i>
                            <span class="text-xl font-bold text-gray-900"
                                >EstampariaPro</span
                            >
                        </router-link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <router-link
                            to="/"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors"
                            active-class="text-blue-600 font-semibold"
                        >
                            Início
                        </router-link>
                        <router-link
                            to="/produtos"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors"
                            active-class="text-blue-600 font-semibold"
                        >
                            Produtos
                        </router-link>
                        <router-link
                            to="/carrinho"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors relative"
                            active-class="text-blue-600 font-semibold"
                        >
                            <i class="fas fa-shopping-cart mr-1"></i>
                            Carrinho
                            <span
                                v-if="cartCount > 0"
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                            >
                                {{ cartCount }}
                            </span>
                        </router-link>
                        <router-link
                            to="/login"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors"
                        >
                            Login
                        </router-link>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="text-gray-700 hover:text-blue-600"
                        >
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div v-if="mobileMenuOpen" class="md:hidden py-4 border-t">
                    <div class="flex flex-col space-y-2">
                        <router-link
                            to="/"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium"
                        >
                            Início
                        </router-link>
                        <router-link
                            to="/produtos"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium"
                        >
                            Produtos
                        </router-link>
                        <router-link
                            to="/carrinho"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium"
                        >
                            Carrinho
                        </router-link>
                        <router-link
                            to="/login"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium"
                        >
                            Login
                        </router-link>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="min-h-screen">
            <router-view />
        </main>

        <!-- Carrinho Flutuante -->
        <FloatingCart />

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <div
                        class="flex items-center justify-center space-x-2 mb-4"
                    >
                        <i class="fas fa-flip-flops text-2xl text-blue-400"></i>
                        <span class="text-xl font-bold">EstampariaPro</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Chinelos personalizados com qualidade e estilo
                    </p>
                    <div
                        class="flex justify-center space-x-6 text-sm text-gray-400"
                    >
                        <a href="#" class="hover:text-white transition-colors"
                            >Política de Privacidade</a
                        >
                        <a href="#" class="hover:text-white transition-colors"
                            >Termos de Uso</a
                        >
                        <a href="#" class="hover:text-white transition-colors"
                            >Contato</a
                        >
                    </div>
                    <p class="text-gray-500 text-sm mt-4">
                        © 2025 EstampariaPro. Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </footer>

        <!-- Notification Component -->
        <div v-if="notification" class="fixed bottom-4 right-4 z-50">
            <div
                :class="[
                    'px-6 py-4 rounded-lg shadow-lg text-white max-w-sm',
                    notification.type === 'success'
                        ? 'bg-green-500'
                        : notification.type === 'error'
                          ? 'bg-red-500'
                          : notification.type === 'warning'
                            ? 'bg-yellow-500'
                            : 'bg-blue-500',
                ]"
            >
                <div class="flex items-center">
                    <i
                        :class="[
                            'mr-3',
                            notification.type === 'success'
                                ? 'fas fa-check-circle'
                                : notification.type === 'error'
                                  ? 'fas fa-exclamation-circle'
                                  : notification.type === 'warning'
                                    ? 'fas fa-exclamation-triangle'
                                    : 'fas fa-info-circle',
                        ]"
                    ></i>
                    <span>{{ notification.message }}</span>
                    <button
                        @click="notification = null"
                        class="ml-4 text-white hover:text-gray-200"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import FloatingCart from "@/components/cart/FloatingCart.vue";

export default {
    name: "App",
    components: { FloatingCart },
    setup() {
        const mobileMenuOpen = ref(false);
        const cartCount = ref(0);
        const notification = ref(null);

        // Simular contagem do carrinho
        const updateCartCount = () => {
            // Aqui você integraria com o composable useCart
            cartCount.value = 0;
        };

        // Função para mostrar notificações
        const showNotification = (message, type = "info") => {
            notification.value = { message, type };
            setTimeout(() => {
                notification.value = null;
            }, 5000);
        };

        onMounted(() => {
            updateCartCount();

            // Inject Hotjar if configured
            try {
                const hotjarId = import.meta.env.VITE_HOTJAR_ID;
                if (hotjarId) {
                    (function (h, o, t, j, a, r) {
                        h.hj =
                            h.hj ||
                            function () {
                                (h.hj.q = h.hj.q || []).push(arguments);
                            };
                        h._hjSettings = { hjid: hotjarId, hjsv: 6 };
                        a = o.getElementsByTagName("head")[0];
                        r = o.createElement("script");
                        r.async = 1;
                        r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
                        a.appendChild(r);
                    })(
                        window,
                        document,
                        "https://static.hotjar.com/c/hotjar-",
                        ".js?sv=",
                    );
                    console.info("Hotjar snippet injected");
                }
            } catch (e) {
                console.warn("Hotjar injection failed", e);
            }

            // Inject Microsoft Clarity if configured
            try {
                const clarityId = import.meta.env.VITE_CLARITY_ID;
                if (clarityId) {
                    (function (c, l, a, r, i, t, y) {
                        c[a] =
                            c[a] ||
                            function () {
                                (c[a].q = c[a].q || []).push(arguments);
                            };
                        t = l.createElement(r);
                        t.async = 1;
                        t.src = "https://www.clarity.ms/tag/" + i;
                        y = l.getElementsByTagName("script")[0];
                        y.parentNode.insertBefore(t, y);
                    })(window, document, "clarity", "script", clarityId);
                    console.info("Clarity snippet injected");
                }
            } catch (e) {
                console.warn("Clarity injection failed", e);
            }
        });

        return {
            mobileMenuOpen,
            cartCount,
            notification,
            showNotification,
        };
    },
};
</script>

<style scoped>
/* Router link active states */
.router-link-active {
    color: #2563eb;
    font-weight: 600;
}

/* Custom transitions */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
