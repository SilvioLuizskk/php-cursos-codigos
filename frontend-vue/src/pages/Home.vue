<template>
    <div class="home">
        <!-- Hero Section (A/B variant-enabled) -->
        <section
            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    <span v-if="variant === 'B'"
                        >Estampas que inspiram você</span
                    >
                    <span v-else>EstampariaPro</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90">
                    <span v-if="variant === 'B'"
                        >Personalize com rapidez e entregue estilo ao seu dia a
                        dia.</span
                    >
                    <span v-else
                        >Chinelos personalizados com qualidade e estilo
                        único</span
                    >
                </p>
                <div class="space-x-4">
                    <router-link
                        to="/produtos"
                        class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
                    >
                        Ver Produtos
                    </router-link>
                    <button
                        @click="onStartClick"
                        class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors inline-block"
                    >
                        <span v-if="variant === 'B'"
                            >Comece sua Estampa Já</span
                        >
                        <span v-else>Começar Agora</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Por que escolher a EstampariaPro?
                    </h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Oferecemos chinelos de alta qualidade com personalização
                        exclusiva, entrega rápida e atendimento excepcional.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center p-6">
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4"
                        >
                            <i
                                class="fas fa-palette text-2xl text-blue-600"
                            ></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Personalização Única
                        </h3>
                        <p class="text-gray-600">
                            Crie seu design exclusivo com nossa ferramenta de
                            personalização intuitiva.
                        </p>
                    </div>

                    <div class="text-center p-6">
                        <div
                            class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
                        >
                            <i class="fas fa-award text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Qualidade Premium
                        </h3>
                        <p class="text-gray-600">
                            Materiais de primeira linha garantem durabilidade e
                            conforto superior.
                        </p>
                    </div>

                    <div class="text-center p-6">
                        <div
                            class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"
                        >
                            <i
                                class="fas fa-shipping-fast text-2xl text-purple-600"
                            ></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Entrega Rápida
                        </h3>
                        <p class="text-gray-600">
                            Receba seus chinelos personalizados em casa
                            rapidamente e com segurança.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Preview -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        Produtos em Destaque
                    </h2>
                    <p class="text-lg text-gray-600">
                        Confira alguns dos nossos chinelos mais populares
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="product in featuredProducts"
                        :key="product.id"
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
                    >
                        <div
                            class="aspect-square bg-gray-200 flex items-center justify-center"
                        >
                            <i
                                class="fas fa-flip-flops text-4xl text-gray-400"
                            ></i>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">
                                {{ product.name }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-2">
                                {{ product.description }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-blue-600"
                                    >R$ {{ product.price }}</span
                                >
                                <button
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors"
                                >
                                    Ver Mais
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-8">
                    <router-link
                        to="/produtos"
                        class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-block"
                    >
                        Ver Todos os Produtos
                    </router-link>
                </div>
            </div>
        </section>

        <!-- Carrossel de Recomendados -->
        <section class="py-10 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <RecommendedCarousel
                    :products="recommendedProducts"
                    @add-to-cart="handleAddToCart"
                />
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-blue-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold mb-4">
                    Pronto para criar seu chinelo personalizado?
                </h2>
                <p class="text-xl mb-8 opacity-90">
                    Junte-se a milhares de clientes satisfeitos e comece sua
                    personalização agora!
                </p>
                <router-link
                    to="/produtos"
                    class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block"
                >
                    Começar Personalização
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
</style>
