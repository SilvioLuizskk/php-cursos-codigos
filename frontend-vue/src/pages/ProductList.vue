<template>
    <div class="product-list py-8">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8"
        >
            <!-- Sidebar Filtros -->
            <SidebarFilters
                class="hidden md:block w-full md:w-64 shrink-0"
                :categories="categories"
                :initial-filters="filters"
                @apply-filters="applySidebarFilters"
            />
            <div class="flex-1">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Nossos Produtos
                    </h1>
                    <p class="text-lg text-gray-600">
                        Descubra nossa coleção de chinelos personalizados
                    </p>
                </div>

                <!-- Controles de Visualização e Ordenação -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 p-4 bg-white rounded-lg shadow-sm"
                >
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600"
                            >{{ products.length }} produtos</span
                        >
                        <div class="flex items-center gap-2">
                            <button
                                @click="viewMode = 'grid'"
                                :class="[
                                    'p-2 rounded-md transition-colors',
                                    viewMode === 'grid'
                                        ? 'bg-blue-100 text-blue-600'
                                        : 'text-gray-400 hover:text-gray-600',
                                ]"
                            >
                                <i class="fas fa-th"></i>
                            </button>
                            <button
                                @click="viewMode = 'list'"
                                :class="[
                                    'p-2 rounded-md transition-colors',
                                    viewMode === 'list'
                                        ? 'bg-blue-100 text-blue-600'
                                        : 'text-gray-400 hover:text-gray-600',
                                ]"
                            >
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-sm text-gray-600"
                            >Ordenar por:</label
                        >
                        <select
                            id="sort"
                            v-model="sortBy"
                            @change="applySorting"
                            class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="name">Nome</option>
                            <option value="price-low">
                                Preço: Menor para Maior
                            </option>
                            <option value="price-high">
                                Preço: Maior para Menor
                            </option>
                            <option value="rating">Avaliação</option>
                            <option value="newest">Mais Recentes</option>
                        </select>
                    </div>
                </div>

                <!-- Filtros rápidos mobile -->
                <div class="md:hidden mb-4">
                    <button
                        @click="showMobileFilters = true"
                        class="btn btn-outline w-full flex items-center justify-center"
                    >
                        <i class="fas fa-filter mr-2"></i> Filtros
                    </button>
                </div>
                <!-- Filtros antigos removidos, agora só na sidebar -->
                <!-- Products Grid -->
                <div v-if="loading" class="text-center py-12">
                    <div class="spinner mx-auto mb-4"></div>
                    <p class="text-gray-600">Carregando produtos...</p>
                </div>
                <div
                    v-else-if="products.length === 0"
                    class="text-center py-12"
                >
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">
                        Nenhum produto encontrado
                    </h3>
                    <p class="text-gray-500">
                        Tente ajustar os filtros de busca
                    </p>
                </div>
                <div
                    v-else
                    :class="[
                        'gap-6',
                        viewMode === 'grid'
                            ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4'
                            : 'flex flex-col',
                    ]"
                >
                    <TransitionGroup
                        name="product"
                        tag="div"
                        :class="viewMode === 'grid' ? 'contents' : ''"
                    >
                        <div
                            v-for="product in products"
                            :key="product.id"
                            :class="[
                                'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 group',
                                viewMode === 'list' ? 'flex mb-4' : '',
                            ]"
                        >
                            <div
                                :class="[
                                    'relative overflow-hidden',
                                    viewMode === 'grid'
                                        ? 'aspect-square'
                                        : 'w-48 h-48 flex-shrink-0',
                                ]"
                            >
                                <div
                                    :class="[
                                        'flex items-center justify-center group-hover:scale-110 transition-transform duration-500',
                                        viewMode === 'grid'
                                            ? 'w-full h-full bg-gradient-to-br from-blue-100 to-purple-100'
                                            : 'w-full h-full bg-gradient-to-br from-blue-100 to-purple-100',
                                    ]"
                                >
                                    <i
                                        class="fas fa-flip-flops text-5xl text-blue-400 group-hover:text-blue-600 transition-colors duration-300"
                                    ></i>
                                </div>
                                <!-- Badge de desconto -->
                                <div
                                    v-if="product.discount_price"
                                    class="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold"
                                >
                                    -{{
                                        Math.round(
                                            (1 -
                                                product.price /
                                                    product.discount_price) *
                                                100,
                                        )
                                    }}%
                                </div>
                                <!-- Badge de novo -->
                                <div
                                    v-if="product.is_new"
                                    class="absolute top-3 right-3 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold"
                                >
                                    Novo
                                </div>
                            </div>
                            <div
                                :class="
                                    viewMode === 'grid' ? 'p-6' : 'p-6 flex-1'
                                "
                            >
                                <h3
                                    :class="[
                                        'font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors',
                                        viewMode === 'grid'
                                            ? 'text-lg'
                                            : 'text-xl',
                                    ]"
                                >
                                    {{ product.name }}
                                </h3>
                                <p
                                    :class="[
                                        'text-gray-600 mb-3 line-clamp-2',
                                        viewMode === 'grid'
                                            ? 'text-sm'
                                            : 'text-base',
                                    ]"
                                >
                                    {{ product.description }}
                                </p>
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        <i
                                            v-for="n in 5"
                                            :key="n"
                                            :class="
                                                n <= (product.rating || 0)
                                                    ? 'fas fa-star'
                                                    : 'far fa-star'
                                            "
                                        ></i>
                                    </div>
                                    <span class="text-sm text-gray-500 ml-2"
                                        >({{ product.reviews || 0 }})</span
                                    >
                                </div>
                                <div
                                    :class="[
                                        'flex items-center justify-between',
                                        viewMode === 'grid' ? 'mb-4' : 'mb-0',
                                    ]"
                                >
                                    <div class="flex flex-col">
                                        <span
                                            :class="[
                                                'font-bold text-blue-600',
                                                viewMode === 'grid'
                                                    ? 'text-lg'
                                                    : 'text-xl',
                                            ]"
                                            >R$ {{ product.price }}</span
                                        >
                                        <span
                                            v-if="product.discount_price"
                                            class="text-sm text-gray-500 line-through"
                                            >R$
                                            {{ product.discount_price }}</span
                                        >
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            @click="quickView(product)"
                                            class="p-2 text-gray-400 hover:text-blue-600 transition-colors"
                                            title="Visualização rápida"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button
                                            @click="handleAddToCart(product)"
                                            class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                            title="Adicionar ao carrinho"
                                        >
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>
                </div>

                <!-- Paginação -->
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
                                'px-3 py-2 rounded transition-all duration-200',
                                page === pagination.current_page
                                    ? 'bg-blue-600 text-white shadow-lg'
                                    : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:shadow-md',
                            ]"
                        >
                            {{ page }}
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Modal Filtros Mobile -->
            <transition name="fade">
                <div
                    v-if="showMobileFilters"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
                >
                    <div
                        class="bg-white rounded-lg shadow-lg w-11/12 max-w-xs p-4 relative"
                    >
                        <button
                            class="absolute top-2 right-2 text-gray-500"
                            @click="showMobileFilters = false"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                        <SidebarFilters
                            :categories="categories"
                            :initial-filters="filters"
                            @apply-filters="applySidebarFiltersMobile"
                        />
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import SidebarFilters from "@/components/common/SidebarFilters.vue";
import { useCart } from "@/composables/useCart";
import { useAuth } from "@/composables/useAuth";
import { useRouter } from "vue-router";
import { useNotification } from "@/composables/useNotification";

export default {
    name: "ProductList",
    components: { SidebarFilters },
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
        const showMobileFilters = ref(false);
        const categories = ref([
            "Havaianas",
            "Ipanema",
            "Rider",
            "Personalizado",
        ]);

        // Estado da visualização
        const viewMode = ref("grid");
        const sortBy = ref("name");

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

        // Filtros avançados
        const applySidebarFilters = (newFilters) => {
            filters.value = { ...filters.value, ...newFilters };
            searchProducts();
        };
        const applySidebarFiltersMobile = (newFilters) => {
            applySidebarFilters(newFilters);
            showMobileFilters.value = false;
        };

        const applySorting = () => {
            // Aplicar ordenação baseada no sortBy
            let sortedProducts = [...products.value];

            switch (sortBy.value) {
                case "name":
                    sortedProducts.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case "price-low":
                    sortedProducts.sort(
                        (a, b) => parseFloat(a.price) - parseFloat(b.price),
                    );
                    break;
                case "price-high":
                    sortedProducts.sort(
                        (a, b) => parseFloat(b.price) - parseFloat(a.price),
                    );
                    break;
                case "rating":
                    sortedProducts.sort(
                        (a, b) => (b.rating || 0) - (a.rating || 0),
                    );
                    break;
                case "newest":
                    sortedProducts.sort((a, b) => b.id - a.id); // Assumindo que IDs maiores são mais recentes
                    break;
                default:
                    break;
            }

            products.value = sortedProducts;
        };

        const quickView = (product) => {
            // Implementar visualização rápida
            router.push(`/produto/${product.slug || product.id}`);
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
            showMobileFilters,
            categories,
            applySidebarFilters,
            applySidebarFiltersMobile,
            viewMode,
            sortBy,
            applySorting,
            quickView,
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
    line-clamp: 2;
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
.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 600;
    transition:
        background 0.2s,
        color 0.2s;
}
.btn-primary {
    background: #2563eb;
    color: #fff;
}
.btn-primary:hover {
    background: #1d4ed8;
}
.btn-outline {
    border: 1px solid #2563eb;
    color: #2563eb;
    background: #fff;
}
.btn-outline:hover {
    background: #f0f6ff;
}

/* Transições para produtos */
.product-enter-active,
.product-leave-active {
    transition: all 0.3s ease;
}

.product-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.product-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

.product-move {
    transition: transform 0.3s ease;
}

/* Transições para modal */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
