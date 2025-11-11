<template>
    <div
        class="product-list min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50"
    >
        <!-- Hero Section -->
        <div
            class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white py-16"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1
                        class="text-5xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent"
                    >
                        Nossos Produtos
                    </h1>
                    <p
                        class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto"
                    >
                        Descubra nossa coleção exclusiva de chinelos
                        personalizados
                    </p>
                    <div class="flex flex-wrap justify-center gap-4 text-sm">
                        <div
                            class="bg-white/10 backdrop-blur-sm rounded-full px-4 py-2"
                        >
                            <i class="fas fa-box mr-2"></i>
                            {{ adminProducts.length }} produtos disponíveis
                        </div>
                        <div
                            class="bg-white/10 backdrop-blur-sm rounded-full px-4 py-2"
                        >
                            <i class="fas fa-tags mr-2"></i>
                            {{ adminCategories.length }} categorias
                        </div>
                        <div
                            class="bg-white/10 backdrop-blur-sm rounded-full px-4 py-2"
                        >
                            <i class="fas fa-star mr-2"></i>
                            Qualidade garantida
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filtros -->
                <div class="lg:w-80 flex-shrink-0">
                    <div class="sticky top-8">
                        <SidebarFilters
                            class="hidden lg:block"
                            :categories="adminCategories"
                            :initial-filters="filters"
                            @apply-filters="applySidebarFilters"
                        />

                        <!-- Mobile Filter Button -->
                        <div class="lg:hidden mb-6">
                            <button
                                @click="showMobileFilters = true"
                                class="w-full bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 flex items-center justify-center group"
                            >
                                <i
                                    class="fas fa-filter text-blue-600 mr-3 group-hover:scale-110 transition-transform"
                                ></i>
                                <span class="font-semibold text-gray-700"
                                    >Filtros Avançados</span
                                >
                                <i
                                    class="fas fa-chevron-right text-gray-400 ml-auto group-hover:translate-x-1 transition-transform"
                                ></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex-1">
                    <!-- Controls Bar -->
                    <div
                        class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100"
                    >
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6"
                        >
                            <!-- Results Count & View Toggle -->
                            <div class="flex items-center gap-4">
                                <div class="bg-blue-50 rounded-xl px-4 py-2">
                                    <span
                                        class="text-sm font-medium text-blue-700"
                                    >
                                        <i class="fas fa-box mr-2"></i>
                                        {{ products.length }} produto{{
                                            products.length !== 1 ? "s" : ""
                                        }}
                                    </span>
                                </div>

                                <div class="flex bg-gray-100 rounded-xl p-1">
                                    <button
                                        @click="viewMode = 'grid'"
                                        :class="[
                                            'p-3 rounded-lg transition-all duration-200 flex items-center gap-2',
                                            viewMode === 'grid'
                                                ? 'bg-white text-blue-600 shadow-md transform scale-105'
                                                : 'text-gray-600 hover:text-blue-600 hover:bg-white/50',
                                        ]"
                                    >
                                        <i class="fas fa-th"></i>
                                        <span
                                            class="hidden sm:inline text-sm font-medium"
                                            >Grid</span
                                        >
                                    </button>
                                    <button
                                        @click="viewMode = 'list'"
                                        :class="[
                                            'p-3 rounded-lg transition-all duration-200 flex items-center gap-2',
                                            viewMode === 'list'
                                                ? 'bg-white text-blue-600 shadow-md transform scale-105'
                                                : 'text-gray-600 hover:text-blue-600 hover:bg-white/50',
                                        ]"
                                    >
                                        <i class="fas fa-list"></i>
                                        <span
                                            class="hidden sm:inline text-sm font-medium"
                                            >Lista</span
                                        >
                                    </button>
                                </div>
                            </div>

                            <!-- Sort Controls -->
                            <div class="flex items-center gap-3">
                                <label
                                    class="text-sm font-medium text-gray-700 hidden sm:block"
                                >
                                    <i
                                        class="fas fa-sort mr-2 text-blue-600"
                                    ></i>
                                    Ordenar por:
                                </label>
                                <select
                                    v-model="sortBy"
                                    @change="applySorting"
                                    class="bg-white border-2 border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 hover:border-blue-300"
                                >
                                    <option value="name">Nome (A-Z)</option>
                                    <option value="price-low">
                                        Preço: Menor → Maior
                                    </option>
                                    <option value="price-high">
                                        Preço: Maior → Menor
                                    </option>
                                    <option value="rating">
                                        Melhor Avaliados
                                    </option>
                                    <option value="newest">
                                        Mais Recentes
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div
                        v-if="loading"
                        class="flex flex-col items-center justify-center py-20"
                    >
                        <div class="relative">
                            <div
                                class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"
                            ></div>
                            <div
                                class="absolute inset-0 w-16 h-16 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin animation-delay-150"
                            ></div>
                        </div>
                        <p class="text-gray-600 font-medium mt-6 text-lg">
                            Carregando produtos incríveis...
                        </p>
                        <p class="text-gray-500 text-sm mt-2">
                            Conectando com nosso admin
                        </p>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-else-if="products.length === 0"
                        class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100"
                    >
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6"
                        >
                            <i class="fas fa-search text-4xl text-blue-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            Nenhum produto encontrado
                        </h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            Não encontramos produtos com os filtros aplicados.
                            Tente ajustar sua busca ou limpe os filtros.
                        </p>
                        <button
                            @click="clearAllFilters"
                            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
                        >
                            <i class="fas fa-undo mr-2"></i>
                            Limpar Filtros
                        </button>
                    </div>

                    <!-- Products by Category -->
                    <div v-else>
                        <div
                            v-for="categoryGroup in productsByCategory"
                            :key="categoryGroup.category.id"
                            :data-category="categoryGroup.category.id"
                            class="mb-12"
                        >
                            <!-- Category Header -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center"
                                    >
                                        <i
                                            class="fas fa-tag text-white text-sm"
                                        ></i>
                                    </div>
                                    <div>
                                        <h2
                                            class="text-2xl font-bold text-gray-900"
                                        >
                                            {{ categoryGroup.category.name }}
                                        </h2>
                                        <p class="text-sm text-gray-600">
                                            {{ categoryGroup.products.length }}
                                            produto{{
                                                categoryGroup.products
                                                    .length !== 1
                                                    ? "s"
                                                    : ""
                                            }}
                                            nesta categoria
                                        </p>
                                    </div>
                                </div>
                                <button
                                    @click="
                                        scrollToCategory(
                                            categoryGroup.category.id,
                                        )
                                    "
                                    class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center gap-2"
                                >
                                    Ver todos
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>

                            <!-- Products Grid for this Category -->
                            <TransitionGroup
                                name="product-fade"
                                tag="div"
                                :class="[
                                    'gap-6 mb-8',
                                    viewMode === 'grid'
                                        ? 'grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4'
                                        : 'space-y-6',
                                ]"
                            >
                                <div
                                    v-for="product in categoryGroup.products"
                                    :key="product.id"
                                    :class="[
                                        'group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 overflow-hidden',
                                        viewMode === 'list' ? 'flex' : '',
                                    ]"
                                >
                                    <!-- Product Image -->
                                    <div
                                        :class="[
                                            'relative overflow-hidden bg-gradient-to-br from-blue-50 to-purple-50',
                                            viewMode === 'grid'
                                                ? 'aspect-square'
                                                : 'w-80 h-80 flex-shrink-0',
                                        ]"
                                    >
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-purple-600/10 group-hover:from-blue-600/20 group-hover:to-purple-600/20 transition-all duration-500"
                                        ></div>

                                        <!-- Product Icon/Image Placeholder -->
                                        <div
                                            class="w-full h-full flex items-center justify-center group-hover:scale-110 transition-transform duration-500"
                                        >
                                            <div
                                                class="text-center w-full h-full"
                                            >
                                                <!-- Show actual product images if available -->
                                                <template
                                                    v-if="
                                                        product.images &&
                                                        product.images.length >
                                                            0
                                                    "
                                                >
                                                    <img
                                                        :src="product.images[0]"
                                                        :alt="product.name"
                                                        class="w-full h-full object-cover rounded-lg"
                                                        @error="
                                                            handleImageError
                                                        "
                                                    />
                                                </template>
                                                <!-- Fallback to icon if no images -->
                                                <template v-else>
                                                    <i
                                                        class="fas fa-flip-flops text-6xl text-blue-500 group-hover:text-blue-600 transition-colors duration-300 mb-2"
                                                    ></i>
                                                    <div
                                                        class="text-xs text-gray-500 font-medium"
                                                    >
                                                        Produto #{{
                                                            product.id
                                                        }}
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Badges -->
                                        <div
                                            class="absolute top-4 left-4 flex flex-col gap-2"
                                        >
                                            <div
                                                v-if="
                                                    product.discount_price &&
                                                    product.discount_price >
                                                        product.price
                                                "
                                                class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg animate-pulse"
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
                                            <div
                                                v-if="product.is_new"
                                                class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg"
                                            >
                                                <i
                                                    class="fas fa-sparkles mr-1"
                                                ></i>
                                                Novo
                                            </div>
                                            <div
                                                v-if="product.featured"
                                                class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg"
                                            >
                                                <i class="fas fa-star mr-1"></i>
                                                Destaque
                                            </div>
                                        </div>

                                        <!-- Quick Actions Overlay -->
                                        <div
                                            class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100"
                                        >
                                            <div class="flex gap-3">
                                                <button
                                                    @click="quickView(product)"
                                                    class="bg-white/90 backdrop-blur-sm text-gray-700 p-3 rounded-full hover:bg-white hover:scale-110 transition-all duration-200 shadow-lg"
                                                    title="Visualização rápida"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button
                                                    @click="
                                                        handleAddToCart(product)
                                                    "
                                                    class="bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 hover:scale-110 transition-all duration-200 shadow-lg"
                                                    title="Adicionar ao carrinho"
                                                >
                                                    <i
                                                        class="fas fa-cart-plus"
                                                    ></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div
                                        :class="
                                            viewMode === 'grid'
                                                ? 'p-6'
                                                : 'p-6 flex-1 flex flex-col justify-between'
                                        "
                                    >
                                        <div>
                                            <div
                                                class="flex items-start justify-between mb-3"
                                            >
                                                <h3
                                                    class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300 text-lg leading-tight"
                                                >
                                                    {{ product.name }}
                                                </h3>
                                                <button
                                                    class="text-gray-400 hover:text-red-500 transition-colors p-1"
                                                >
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </div>

                                            <p
                                                class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed"
                                            >
                                                {{ product.description }}
                                            </p>

                                            <!-- Rating -->
                                            <div class="flex items-center mb-4">
                                                <div
                                                    class="flex text-yellow-400 mr-2"
                                                >
                                                    <i
                                                        v-for="n in 5"
                                                        :key="n"
                                                        :class="[
                                                            'transition-colors duration-200',
                                                            n <=
                                                            (product.rating ||
                                                                0)
                                                                ? 'fas fa-star text-yellow-400'
                                                                : 'far fa-star text-gray-300',
                                                        ]"
                                                    ></i>
                                                </div>
                                                <span
                                                    class="text-sm text-gray-500"
                                                >
                                                    ({{ product.reviews || 0 }}
                                                    avaliações)
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Price & Actions -->
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div class="flex flex-col">
                                                <div
                                                    class="flex items-baseline gap-2"
                                                >
                                                    <span
                                                        class="text-2xl font-bold text-blue-600"
                                                    >
                                                        R$
                                                        {{
                                                            formatPrice(
                                                                product.price,
                                                            )
                                                        }}
                                                    </span>
                                                    <span
                                                        v-if="
                                                            product.discount_price &&
                                                            product.discount_price >
                                                                product.price
                                                        "
                                                        class="text-sm text-gray-500 line-through"
                                                    >
                                                        R$
                                                        {{
                                                            formatPrice(
                                                                product.discount_price,
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                                <div
                                                    v-if="
                                                        product.stock_quantity <=
                                                            5 &&
                                                        product.stock_quantity >
                                                            0
                                                    "
                                                    class="text-xs text-orange-600 font-medium mt-1"
                                                >
                                                    <i
                                                        class="fas fa-exclamation-triangle mr-1"
                                                    ></i>
                                                    Apenas
                                                    {{ product.stock_quantity }}
                                                    em estoque
                                                </div>
                                                <div
                                                    v-else-if="
                                                        product.stock_quantity ===
                                                        0
                                                    "
                                                    class="text-xs text-red-600 font-medium mt-1"
                                                >
                                                    <i
                                                        class="fas fa-times-circle mr-1"
                                                    ></i>
                                                    Fora de estoque
                                                </div>
                                            </div>

                                            <div class="flex gap-2">
                                                <button
                                                    @click="quickView(product)"
                                                    class="p-3 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200"
                                                    title="Visualização rápida"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button
                                                    @click="
                                                        handleAddToCart(product)
                                                    "
                                                    :disabled="
                                                        product.stock_quantity ===
                                                        0
                                                    "
                                                    :class="[
                                                        'p-3 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg',
                                                        product.stock_quantity ===
                                                        0
                                                            ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                                            : 'bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 hover:shadow-xl',
                                                    ]"
                                                    title="Adicionar ao carrinho"
                                                >
                                                    <i
                                                        class="fas fa-cart-plus"
                                                    ></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </TransitionGroup>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="pagination.total > pagination.per_page"
                        class="mt-12 flex justify-center"
                    >
                        <nav
                            class="flex items-center gap-2 bg-white rounded-2xl shadow-lg p-2 border border-gray-100"
                        >
                            <button
                                @click="changePage(pagination.current_page - 1)"
                                :disabled="pagination.current_page === 1"
                                class="p-3 rounded-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-50 hover:text-blue-600"
                            >
                                <i class="fas fa-chevron-left"></i>
                            </button>

                            <template v-for="page in visiblePages" :key="page">
                                <span
                                    v-if="page === '...'"
                                    :key="'ellipsis-' + Math.random()"
                                    class="px-4 py-3 text-gray-500 cursor-default"
                                >
                                    ...
                                </span>
                                <button
                                    v-else
                                    :key="'page-' + page"
                                    @click="changePage(page)"
                                    :class="[
                                        'px-4 py-3 rounded-xl font-semibold transition-all duration-200',
                                        page === pagination.current_page
                                            ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg transform scale-105'
                                            : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600',
                                    ]"
                                >
                                    {{ page }}
                                </button>
                            </template>

                            <button
                                @click="changePage(pagination.current_page + 1)"
                                :disabled="
                                    pagination.current_page === totalPages
                                "
                                class="p-3 rounded-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-blue-50 hover:text-blue-600"
                            >
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Filters Modal -->
        <Transition name="modal">
            <div
                v-if="showMobileFilters"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                @click.self="showMobileFilters = false"
            >
                <div
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-sm max-h-[90vh] overflow-hidden"
                >
                    <div
                        class="p-6 border-b border-gray-100 flex items-center justify-between"
                    >
                        <h3 class="text-lg font-bold text-gray-900">Filtros</h3>
                        <button
                            @click="showMobileFilters = false"
                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                        <SidebarFilters
                            :categories="adminCategories"
                            :initial-filters="filters"
                            @apply-filters="applySidebarFiltersMobile"
                        />
                    </div>
                    <div class="p-6 border-t border-gray-100 bg-gray-50">
                        <button
                            @click="showMobileFilters = false"
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300"
                        >
                            Aplicar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Quick View Modal -->
        <Transition name="modal">
            <div
                v-if="quickViewProduct"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                @click.self="closeQuickView"
            >
                <div
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden"
                >
                    <div
                        class="p-6 border-b border-gray-100 flex items-center justify-between"
                    >
                        <h3 class="text-xl font-bold text-gray-900">
                            Visualização Rápida
                        </h3>
                        <button
                            @click="closeQuickView"
                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                        <div class="grid md:grid-cols-2 gap-8">
                            <!-- Product Image -->
                            <div
                                class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-8 flex items-center justify-center"
                            >
                                <template
                                    v-if="
                                        quickViewProduct.images &&
                                        quickViewProduct.images.length > 0
                                    "
                                >
                                    <img
                                        :src="quickViewProduct.images[0]"
                                        :alt="quickViewProduct.name"
                                        class="w-full h-64 object-cover rounded-lg"
                                        @error="handleImageError"
                                    />
                                </template>
                                <template v-else>
                                    <i
                                        class="fas fa-flip-flops text-8xl text-blue-500"
                                    ></i>
                                </template>
                            </div>

                            <!-- Product Details -->
                            <div>
                                <h4
                                    class="text-2xl font-bold text-gray-900 mb-4"
                                >
                                    {{ quickViewProduct.name }}
                                </h4>
                                <p class="text-gray-600 mb-6 leading-relaxed">
                                    {{ quickViewProduct.description }}
                                </p>

                                <div class="flex items-center mb-6">
                                    <div class="flex text-yellow-400 mr-2">
                                        <i
                                            v-for="n in 5"
                                            :key="n"
                                            :class="
                                                n <=
                                                (quickViewProduct.rating || 0)
                                                    ? 'fas fa-star'
                                                    : 'far fa-star'
                                            "
                                        ></i>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        ({{ quickViewProduct.reviews || 0 }}
                                        avaliações)
                                    </span>
                                </div>

                                <div class="mb-6">
                                    <span
                                        class="text-3xl font-bold text-blue-600"
                                    >
                                        R$
                                        {{
                                            formatPrice(quickViewProduct.price)
                                        }}
                                    </span>
                                    <span
                                        v-if="quickViewProduct.discount_price"
                                        class="text-lg text-gray-500 line-through ml-3"
                                    >
                                        R$
                                        {{
                                            formatPrice(
                                                quickViewProduct.discount_price,
                                            )
                                        }}
                                    </span>
                                </div>

                                <button
                                    @click="handleAddToCart(quickViewProduct)"
                                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                                >
                                    <i class="fas fa-cart-plus mr-2"></i>
                                    Adicionar ao Carrinho
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount, watch } from "vue";
import SidebarFilters from "@/components/common/SidebarFilters.vue";
import { useCart } from "@/composables/useCart";
import { useAuth } from "@/composables/useAuth";
import { useRouter } from "vue-router";
import { useNotification } from "@/composables/useNotification";
import { useAdminSync } from "@/composables/useAdminSync";

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
        const quickViewProduct = ref(null);

        // Usar produtos e categorias do admin via useAdminSync
        const { adminProducts, adminCategories, startPolling, stopPolling } =
            useAdminSync();

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

        const visiblePages = computed(() => {
            const current = pagination.value.current_page;
            const total = totalPages.value;
            const pages = [];

            if (total <= 7) {
                for (let i = 1; i <= total; i++) {
                    pages.push(i);
                }
            } else {
                pages.push(1);

                if (current > 4) pages.push("...");

                const start = Math.max(2, current - 1);
                const end = Math.min(total - 1, current + 1);

                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }

                if (current < total - 3) pages.push("...");

                if (total > 1) pages.push(total);
            }

            return pages;
        });

        // Agrupar produtos por categoria
        const productsByCategory = computed(() => {
            const grouped = {};

            // Primeiro, agrupa produtos por categoria
            products.value.forEach((product) => {
                const categoryId = product.category_id;
                if (!grouped[categoryId]) {
                    // Encontra a categoria correspondente
                    const category = adminCategories.value.find(
                        (cat) => cat.id == categoryId,
                    );
                    if (category) {
                        grouped[categoryId] = {
                            category: category,
                            products: [],
                        };
                    }
                }
                if (grouped[categoryId]) {
                    grouped[categoryId].products.push(product);
                }
            });

            // Converte para array e ordena por nome da categoria
            return Object.values(grouped).sort((a, b) =>
                a.category.name.localeCompare(b.category.name),
            );
        });

        const formatPrice = (price) => {
            return new Intl.NumberFormat("pt-BR", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(price);
        };

        const loadProducts = async () => {
            loading.value = true;
            try {
                // Filtrar produtos do admin
                let filteredProducts = [...adminProducts.value];

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

                if (filters.value.category) {
                    filteredProducts = filteredProducts.filter(
                        (p) => p.category_id == filters.value.category,
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

        const clearAllFilters = () => {
            filters.value = {
                search: "",
                category: "",
                min_price: null,
                max_price: null,
                rating: 0,
                featured: false,
            };
            sortBy.value = "name";
            pagination.value.current_page = 1;
            loadProducts();
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
            quickViewProduct.value = product;
        };

        const closeQuickView = () => {
            quickViewProduct.value = null;
        };

        const handleImageError = (event) => {
            // Fallback to icon when image fails to load
            const img = event.target;
            const container = img.parentElement;
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full">
                    <i class="fas fa-flip-flops text-6xl text-blue-500 mb-2"></i>
                    <div class="text-xs text-gray-500 font-medium">Produto #${img.alt.split("#")[1] || "N/A"}</div>
                </div>
            `;
        };

        const scrollToCategory = (categoryId) => {
            // Rola para a categoria específica
            const element = document.querySelector(
                `[data-category="${categoryId}"]`,
            );
            if (element) {
                element.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        };

        onMounted(() => {
            loadProducts();
            // Iniciar polling para sincronizar produtos e categorias do admin
            startPolling(3000);
        });

        onBeforeUnmount(() => {
            // Parar polling quando componente for destruído
            stopPolling();
        });

        // Assistir mudanças nos produtos do admin
        watch(
            () => adminProducts.value,
            (newProducts) => {
                console.log(
                    "[ProductList] adminProducts changed:",
                    newProducts.length,
                );
                // Debug: verificar estrutura das imagens
                if (newProducts.length > 0) {
                    console.log(
                        "[ProductList] Primeiro produto:",
                        newProducts[0],
                    );
                    console.log(
                        "[ProductList] Imagens do primeiro produto:",
                        newProducts[0].images,
                    );
                }
                loadProducts();
            },
            { immediate: true },
        );

        return {
            loading,
            products,
            filters,
            pagination,
            totalPages,
            visiblePages,
            productsByCategory,
            searchProducts,
            clearFilters,
            clearAllFilters,
            changePage,
            handleAddToCart,
            isAddingToCart,
            showMobileFilters,
            quickViewProduct,
            closeQuickView,
            scrollToCategory,
            adminCategories,
            adminProducts,
            applySidebarFilters,
            applySidebarFiltersMobile,
            viewMode,
            sortBy,
            applySorting,
            quickView,
            formatPrice,
            handleImageError,
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
.product-fade-enter-active,
.product-fade-leave-active {
    transition: all 0.5s ease;
}

.product-fade-enter-from {
    opacity: 0;
    transform: translateY(30px) scale(0.95);
}

.product-fade-leave-to {
    opacity: 0;
    transform: translateY(-30px) scale(0.95);
}

.product-fade-move {
    transition: transform 0.5s ease;
}

/* Transições para modal */
.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}

/* Animação de delay para spinner */
.animation-delay-150 {
    animation-delay: 0.15s;
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}
</style>
