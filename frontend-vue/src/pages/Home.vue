<template>
    <div class="home">
        <!-- Hero Section (A/B variant-enabled) -->
        <section
            class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 text-white py-20 relative overflow-hidden"
        >
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute inset-0"
                    style="
                        background-image: radial-gradient(
                            circle at 25% 25%,
                            white 2px,
                            transparent 2px
                        );
                        background-size: 50px 50px;
                    "
                ></div>
            </div>

            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10"
            >
                <Transition name="hero" appear>
                    <div>
                        <h1
                            class="text-4xl md:text-6xl font-bold mb-6 animate-pulse-slow"
                        >
                            <span v-if="variant === 'B'"
                                >Estampas que inspiram você</span
                            >
                            <span v-else>EstampariaPro</span>
                        </h1>
                        <p
                            class="text-xl md:text-2xl mb-8 opacity-90 max-w-4xl mx-auto"
                        >
                            <span v-if="variant === 'B'"
                                >Personalize com rapidez e entregue estilo ao
                                seu dia a dia.</span
                            >
                            <span v-else
                                >Chinelos personalizados com qualidade e estilo
                                único</span
                            >
                        </p>
                        <div
                            class="flex flex-col sm:flex-row gap-4 justify-center items-center"
                        >
                            <router-link
                                to="/produtos"
                                class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 hover:scale-105 transition-all duration-300 inline-block shadow-lg hover:shadow-xl"
                            >
                                <i class="fas fa-eye mr-2"></i>Ver Produtos
                            </router-link>
                            <button
                                @click="onStartClick"
                                class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 hover:scale-105 transition-all duration-300 inline-block shadow-lg hover:shadow-xl"
                            >
                                <i class="fas fa-rocket mr-2"></i>
                                <span v-if="variant === 'B'"
                                    >Comece sua Estampa Já</span
                                >
                                <span v-else>Começar Agora</span>
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <Transition name="fade-up" appear>
                    <div class="text-center mb-16">
                        <h2
                            class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"
                        >
                            Por que escolher a EstampariaPro?
                        </h2>
                        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                            Oferecemos chinelos de alta qualidade com
                            personalização exclusiva, entrega rápida e
                            atendimento excepcional.
                        </p>
                    </div>
                </Transition>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <Transition name="fade-up" appear :delay="100">
                        <div
                            class="text-center p-8 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-2 group"
                        >
                            <div
                                class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300"
                            >
                                <i
                                    class="fas fa-palette text-3xl text-white"
                                ></i>
                            </div>
                            <h3
                                class="text-xl font-semibold text-gray-900 mb-3"
                            >
                                Personalização Única
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Crie seu design exclusivo com nossa ferramenta
                                de personalização intuitiva e moderna.
                            </p>
                        </div>
                    </Transition>

                    <Transition name="fade-up" appear :delay="200">
                        <div
                            class="text-center p-8 bg-gradient-to-br from-green-50 to-green-100 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-2 group"
                        >
                            <div
                                class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300"
                            >
                                <i class="fas fa-award text-3xl text-white"></i>
                            </div>
                            <h3
                                class="text-xl font-semibold text-gray-900 mb-3"
                            >
                                Qualidade Premium
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Materiais de primeira linha garantem
                                durabilidade e conforto superior em todos os
                                nossos produtos.
                            </p>
                        </div>
                    </Transition>

                    <Transition name="fade-up" appear :delay="300">
                        <div
                            class="text-center p-8 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-2 group"
                        >
                            <div
                                class="w-20 h-20 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300"
                            >
                                <i
                                    class="fas fa-shipping-fast text-3xl text-white"
                                ></i>
                            </div>
                            <h3
                                class="text-xl font-semibold text-gray-900 mb-3"
                            >
                                Entrega Rápida
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Receba seus chinelos personalizados em casa
                                rapidamente e com total segurança.
                            </p>
                        </div>
                    </Transition>
                </div>
            </div>
        </section>

        <!-- Products Preview -->
        <section class="py-20 bg-gradient-to-br from-gray-50 to-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <Transition name="fade-up" appear>
                    <div class="text-center mb-16">
                        <h2
                            class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"
                        >
                            Produtos em Destaque
                        </h2>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            Confira alguns dos nossos chinelos mais populares e
                            comece sua personalização
                        </p>
                    </div>
                </Transition>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8"
                >
                    <TransitionGroup name="product" tag="div" class="contents">
                        <div
                            v-for="(product, index) in featuredProducts"
                            :key="product.id"
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group"
                            :style="{ transitionDelay: `${index * 100}ms` }"
                        >
                            <div class="relative overflow-hidden">
                                <div
                                    class="aspect-square bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-500"
                                >
                                    <i
                                        class="fas fa-flip-flops text-5xl text-blue-400 group-hover:text-blue-600 transition-colors duration-300"
                                    ></i>
                                </div>
                                <!-- Badge de destaque -->
                                <div
                                    class="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold"
                                >
                                    Destaque
                                </div>
                            </div>
                            <div class="p-6">
                                <h3
                                    class="font-bold text-gray-900 mb-2 text-lg group-hover:text-blue-600 transition-colors"
                                >
                                    {{ product.name }}
                                </h3>
                                <p
                                    class="text-gray-600 text-sm mb-4 line-clamp-2"
                                >
                                    {{ product.description }}
                                </p>
                                <div
                                    class="flex items-center justify-between mb-4"
                                >
                                    <span
                                        class="text-2xl font-bold text-blue-600"
                                        >R$ {{ product.price }}</span
                                    >
                                    <div class="flex items-center">
                                        <i
                                            class="fas fa-star text-yellow-400 mr-1"
                                        ></i>
                                        <span class="text-sm text-gray-600"
                                            >4.8</span
                                        >
                                    </div>
                                </div>
                                <button
                                    @click="viewProduct(product)"
                                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                >
                                    <i class="fas fa-eye mr-2"></i>Ver Detalhes
                                </button>
                            </div>
                        </div>
                    </TransitionGroup>
                </div>

                <!-- CTA Section -->
                <Transition name="fade-up" appear :delay="400">
                    <div class="text-center mt-16">
                        <router-link
                            to="/produtos"
                            class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                        >
                            <i class="fas fa-th-large mr-2"></i>
                            Ver Todos os Produtos
                            <i class="fas fa-arrow-right ml-2"></i>
                        </router-link>
                    </div>
                </Transition>
            </div>
        </section>

        <!-- Carrossel de Recomendados -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <Transition name="fade-up" appear>
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">
                            Recomendados para Você
                        </h2>
                        <p class="text-lg text-gray-600">
                            Baseado nas suas preferências e tendências atuais
                        </p>
                    </div>
                </Transition>
                <div class="text-center">
                    <p class="text-gray-500">
                        Carrossel será implementado aqui
                    </p>
                </div>
            </div>
        </section>

        <!-- Final CTA Section -->
        <section
            class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white relative overflow-hidden"
        >
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute inset-0"
                    style="
                        background-image: radial-gradient(
                            circle at 75% 25%,
                            white 2px,
                            transparent 2px
                        );
                        background-size: 50px 50px;
                    "
                ></div>
            </div>
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10"
            >
                <Transition name="fade-up" appear>
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold mb-6">
                            Pronto para criar seu chinelo personalizado?
                        </h2>
                        <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto">
                            Junte-se a milhares de clientes satisfeitos e comece
                            sua personalização agora mesmo!
                        </p>
                        <div
                            class="flex flex-col sm:flex-row gap-4 justify-center"
                        >
                            <router-link
                                to="/produtos"
                                class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 hover:scale-105 transition-all duration-300 inline-block shadow-lg hover:shadow-xl"
                            >
                                <i class="fas fa-palette mr-2"></i>Começar
                                Personalização
                            </router-link>
                            <router-link
                                to="/registrar"
                                class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 hover:scale-105 transition-all duration-300 inline-block"
                            >
                                <i class="fas fa-user-plus mr-2"></i>Criar Conta
                            </router-link>
                        </div>
                    </div>
                </Transition>
            </div>
        </section>

        <!-- Demo Link Section -->
        <section class="py-8 bg-gray-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-sm opacity-75 mb-2">👨‍💻 Para Desenvolvedores</p>
                <router-link
                    to="/comunicacao-vertical"
                    class="text-blue-400 hover:text-blue-300 underline text-sm"
                >
                    Ver Demonstração: Comunicação Vertical Descendente em Vue.js
                </router-link>
            </div>
        </section>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import RecommendedCarousel from "@/components/product/RecommendedCarousel.vue";
import { useABTest } from "@/composables/useABTest";
import { useHome } from "@/composables/useHome";

export default {
    name: "Home",
    components: { RecommendedCarousel },
    setup() {
        const router = useRouter();
        const { getVariant, recordConversion } = useABTest();
        const variant = ref("A");

        const {
            featuredProducts,
            categories,
            stats,
            banners,
            loading,
            error,
            fetchHomeData,
        } = useHome();

        const recommendedProducts = ref([
            {
                id: 5,
                name: "Chinelo Praia",
                price: "34.99",
                rating: 4.7,
                image: "https://via.placeholder.com/220x160?text=Praia",
            },
            {
                id: 6,
                name: "Chinelo Esportivo",
                price: "49.99",
                rating: 4.9,
                image: "https://via.placeholder.com/220x160?text=Esportivo",
            },
            {
                id: 7,
                name: "Chinelo Casual",
                price: "27.99",
                rating: 4.3,
                image: "https://via.placeholder.com/220x160?text=Casual",
            },
            {
                id: 8,
                name: "Chinelo Kids",
                price: "24.99",
                rating: 4.8,
                image: "https://via.placeholder.com/220x160?text=Kids",
            },
            {
                id: 9,
                name: "Chinelo Premium",
                price: "59.99",
                rating: 5.0,
                image: "https://via.placeholder.com/220x160?text=Premium",
            },
        ]);

        const handleAddToCart = (product) => {
            // Aqui você pode integrar com o carrinho global
            alert(`Produto adicionado: ${product.name}`);
        };

        onMounted(async () => {
            try {
                const v = await getVariant("homepage_hero");
                variant.value = v || "A";
            } catch (e) {
                console.warn("AB variant fetch failed", e);
            }
            console.log("Home page loaded, variant=", variant.value);

            // Buscar dados da home do backend
            try {
                await fetchHomeData();
            } catch (e) {
                console.warn("Home data fetch failed", e);
            }
        });

        function onStartClick() {
            // Record conversion (best-effort)
            recordConversion("homepage_hero", variant.value);
            router.push("/produtos");
        }

        return {
            featuredProducts,
            recommendedProducts,
            categories,
            stats,
            banners,
            loading,
            error,
            handleAddToCart,
            variant,
            onStartClick,
        };
    },
};
</script>

<style scoped>
.home {
    min-height: 100vh;
}

.aspect-square {
    aspect-ratio: 1 / 1;
}

/* Animações de entrada */
.hero-enter-active {
    transition:
        opacity 1s ease,
        transform 1s ease;
}

.hero-enter-from {
    opacity: 0;
    transform: translateY(30px);
}

.fade-up-enter-active {
    transition:
        opacity 0.8s ease,
        transform 0.8s ease;
}

.fade-up-enter-from {
    opacity: 0;
    transform: translateY(40px);
}

.fade-up-enter-active[data-delay] {
    transition-delay: attr(data-delay);
}

/* Animação do produto */
.product-enter-active {
    transition:
        opacity 0.6s ease,
        transform 0.6s ease;
}

.product-enter-from {
    opacity: 0;
    transform: translateY(30px) scale(0.9);
}

.product-leave-active {
    transition:
        opacity 0.3s ease,
        transform 0.3s ease;
}

.product-leave-to {
    opacity: 0;
    transform: scale(0.8);
}

.product-move {
    transition: transform 0.5s ease;
}

/* Animação lenta do título */
@keyframes pulse-slow {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.animate-pulse-slow {
    animation: pulse-slow 3s ease-in-out infinite;
}

/* Utilitários */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-clamp: 2;
    overflow: hidden;
}
</style>
