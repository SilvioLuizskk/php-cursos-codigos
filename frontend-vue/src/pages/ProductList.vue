<template>
    <div class="product-list py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Nossos Produtos
                </h1>
                <p class="text-lg text-gray-600">
                    Descubra nossa coleção de chinelos personalizados
                </p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Buscar</label
                        >
                        <input
                            v-model="filters.search"
                            type="text"
                            placeholder="Nome do produto..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Preço Mínimo</label
                        >
                        <input
                            v-model.number="filters.min_price"
                            type="number"
                            placeholder="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Preço Máximo</label
                        >
                        <input
                            v-model.number="filters.max_price"
                            type="number"
                            placeholder="100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Ordenar por</label
                        >
                        <select
                            v-model="filters.sort_by"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="created_at">Mais Recentes</option>
                            <option value="price">Preço</option>
                            <option value="name">Nome</option>
                            <option value="rating">Avaliação</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex space-x-4">
                    <button
                        @click="searchProducts"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors"
                    >
                        <i class="fas fa-search mr-2"></i>
                        Buscar
                    </button>
                    <button
                        @click="clearFilters"
                        class="border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-50 transition-colors"
                    >
                        Limpar Filtros
                    </button>
                </div>
            </div>

            <!-- Products Grid -->
            <div v-if="loading" class="text-center py-12">
                <div class="spinner mx-auto mb-4"></div>
                <p class="text-gray-600">Carregando produtos...</p>
            </div>

            <div v-else-if="products.length === 0" class="text-center py-12">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">
                    Nenhum produto encontrado
                </h3>
                <p class="text-gray-500">Tente ajustar os filtros de busca</p>
            </div>

            <div
                v-else
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
            >
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
                >
                    <div
                        class="aspect-square bg-gray-200 flex items-center justify-center"
                    >
                        <i class="fas fa-flip-flops text-4xl text-gray-400"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">
                            {{ product.name }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-2 line-clamp-2">
                            {{ product.description }}
                        </p>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i
                                    v-for="n in 5"
                                    :key="n"
                                    :class="
                                        n <= product.rating
                                            ? 'fas fa-star'
                                            : 'far fa-star'
                                    "
                                ></i>
                            </div>
                            <span class="text-sm text-gray-500 ml-2"
                                >({{ product.rating || 0 }})</span
                            >
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-lg font-bold text-blue-600"
                                    >R$ {{ product.price }}</span
                                >
                                <span
                                    v-if="product.discount_price"
                                    class="text-sm text-gray-500 line-through ml-2"
                                >
                                    R$ {{ product.discount_price }}
                                </span>
                            </div>
                            <button
                                @click="handleAddToCart(product)"
                                class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="
                                    product.stock === 0 ||
                                    isAddingToCart(product.id)
                                "
                            >
                                <i class="fas fa-cart-plus mr-1"></i>
                                <span v-if="isAddingToCart(product.id)"
                                    >Adicionando...</span
                                >
                                <span v-else>{{
                                    product.stock === 0
                                        ? "Esgotado"
                                        : "Adicionar"
                                }}</span>
                            </button>
                        </div>
                        <div class="mt-2">
                            <span
                                :class="[
                                    'text-xs px-2 py-1 rounded',
                                    product.stock > 10
                                        ? 'bg-green-100 text-green-800'
                                        : product.stock > 0
                                          ? 'bg-yellow-100 text-yellow-800'
                                          : 'bg-red-100 text-red-800',
                                ]"
                            >
                                {{
                                    product.stock > 10
                                        ? "Em estoque"
                                        : product.stock > 0
                                          ? `Restam ${product.stock}`
                                          : "Esgotado"
                                }}
                            </span>
                            <span
                                v-if="product.featured"
                                class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2"
                            >
                                Destaque
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="pagination.total > pagination.per_page"
                class="mt-8 flex justify-center"
            >
                <nav class="flex space-x-2">
                    <button
                        v-for="page in totalPages"
                        :key="page"
                        @click="changePage(page)"
                        :class="[
                            'px-3 py-2 rounded',
                            page === pagination.current_page
                                ? 'bg-blue-600 text-white'
                                : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50',
                        ]"
                    >
                        {{ page }}
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import { useCart } from "@/composables/useCart";
import { useAuth } from "@/composables/useAuth";
import { useRouter } from "vue-router";
import { useNotification } from "@/composables/useNotification";

export default {
    name: "ProductList",
    setup() {
        const loading = ref(false);
        const products = ref([]);
        const filters = ref({
            search: "",
            min_price: null,
            max_price: null,
            sort_by: "created_at",
            sort_order: "desc",
        });
        const pagination = ref({
            current_page: 1,
            per_page: 12,
            total: 0,
            total_products: 0,
        });

        // Composables
        const { addToCart } = useCart();
        const { isAuthenticated } = useAuth();
        const router = useRouter();
        const { showSuccess, showError } = useNotification();

        // Estado de loading por produto
        const addingToCart = ref(new Set());

        // Produtos simulados
        const mockProducts = [
            {
                id: 1,
                name: "Chinelo Havaianas Brasil",
                description:
                    "Chinelo icônico do Brasil com as cores da bandeira nacional.",
                price: "29.99",
                discount_price: null,
                rating: 4.5,
                stock: 50,
                featured: true,
            },
            {
                id: 2,
                name: "Chinelo Ipanema Fashion",
                description:
                    "Chinelo moderno com estampa exclusiva da praia de Ipanema.",
                price: "39.99",
                discount_price: "49.99",
                rating: 4.2,
                stock: 30,
                featured: true,
            },
            {
                id: 3,
                name: "Chinelo Rider Energy",
                description: "Conforto e tecnologia para o dia a dia.",
                price: "45.99",
                discount_price: null,
                rating: 4.8,
                stock: 25,
                featured: false,
            },
            {
                id: 4,
                name: "Chinelo Personalizado Amor",
                description: "Chinelo romântico com design exclusivo.",
                price: "55.99",
                discount_price: null,
                rating: 4.6,
                stock: 15,
                featured: true,
            },
            {
                id: 5,
                name: "Chinelo Havaianas Top",
                description: "O clássico que nunca sai de moda.",
                price: "29.99",
                discount_price: null,
                rating: 4.3,
                stock: 0,
                featured: false,
            },
        ];

        const totalPages = computed(() => {
            return Math.ceil(
                pagination.value.total / pagination.value.per_page,
            );
        });

        const loadProducts = async () => {
            loading.value = true;
            try {
                // Simular chamada API
                await new Promise((resolve) => setTimeout(resolve, 1000));

                // Filtrar produtos
                let filteredProducts = [...mockProducts];

                if (filters.value.search) {
                    filteredProducts = filteredProducts.filter(
                        (p) =>
                            p.name
                                .toLowerCase()
                                .includes(filters.value.search.toLowerCase()) ||
                            p.description
                                .toLowerCase()
                                .includes(filters.value.search.toLowerCase()),
                    );
                }

                if (filters.value.min_price) {
                    filteredProducts = filteredProducts.filter(
                        (p) => parseFloat(p.price) >= filters.value.min_price,
                    );
                }

                if (filters.value.max_price) {
                    filteredProducts = filteredProducts.filter(
                        (p) => parseFloat(p.price) <= filters.value.max_price,
                    );
                }

                // Ordenar
                if (filters.value.sort_by === "price") {
                    filteredProducts.sort(
                        (a, b) => parseFloat(a.price) - parseFloat(b.price),
                    );
                } else if (filters.value.sort_by === "name") {
                    filteredProducts.sort((a, b) =>
                        a.name.localeCompare(b.name),
                    );
                } else if (filters.value.sort_by === "rating") {
                    filteredProducts.sort((a, b) => b.rating - a.rating);
                }

                products.value = filteredProducts;
                pagination.value.total = filteredProducts.length;
                pagination.value.total_products = filteredProducts.length;
            } catch (error) {
                console.error("Erro ao carregar produtos:", error);
                showError("Erro ao carregar produtos");
            } finally {
                loading.value = false;
            }
        };

        const searchProducts = () => {
            pagination.value.current_page = 1;
            loadProducts();
        };

        const clearFilters = () => {
            filters.value = {
                search: "",
                min_price: null,
                max_price: null,
                sort_by: "created_at",
                sort_order: "desc",
            };
            searchProducts();
        };

        const changePage = (page) => {
            pagination.value.current_page = page;
            loadProducts();
        };

        const handleAddToCart = async (product) => {
            if (!isAuthenticated.value) {
                router.push({
                    name: "Login",
                    query: { redirect: "/produtos" },
                });
                return;
            }

            if (product.stock === 0) return;

            addingToCart.value.add(product.id);
            try {
                await addToCart(product.id, 1);
                showSuccess("Produto adicionado ao carrinho!");
            } catch (error) {
                showError(
                    error.response?.data?.message ||
                        "Erro ao adicionar ao carrinho",
                );
            } finally {
                addingToCart.value.delete(product.id);
            }
        };

        const isAddingToCart = (productId) => {
            return addingToCart.value.has(productId);
        };

        onMounted(() => {
            loadProducts();
        });

        return {
            loading,
            products,
            filters,
            pagination,
            totalPages,
            searchProducts,
            clearFilters,
            changePage,
            handleAddToCart,
            isAddingToCart,
        };
    },
};
</script>

<style scoped>
.aspect-square {
    aspect-ratio: 1 / 1;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid rgba(59, 130, 246, 0.3);
    border-radius: 50%;
    border-top-color: #3b82f6;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
