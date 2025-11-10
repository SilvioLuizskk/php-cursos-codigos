<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">
                Editor da Página Inicial
            </h1>
            <div class="flex items-center space-x-4">
                <button
                    @click="previewMode = !previewMode"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                >
                    {{ previewMode ? "Editar" : "Visualizar" }}
                </button>
                <button
                    @click="saveHomePage"
                    :disabled="saving"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition"
                >
                    {{ saving ? "Salvando..." : "Salvar Alterações" }}
                </button>
            </div>
        </div>

        <!-- Preview Mode -->
        <div v-if="previewMode" class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">
                Preview da Página Inicial
            </h2>
            <div class="border rounded-lg p-4 bg-gray-50">
                <div class="max-w-4xl mx-auto space-y-6">
                    <!-- Hero Section -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-bold mb-4">Hero Section</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h1
                                    class="text-3xl font-bold text-gray-900 mb-2"
                                >
                                    {{ homeData.hero.title }}
                                </h1>
                                <p class="text-gray-600 mb-4">
                                    {{ homeData.hero.subtitle }}
                                </p>
                                <button
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
                                    @click="saveHomePage"
                                >
                                    {{ homeData.hero.buttonText }}
                                </button>
                            </div>
                            <div
                                v-if="homeData.hero.image"
                                class="flex justify-center"
                            >
                                <img
                                    :src="homeData.hero.image"
                                    alt="Hero"
                                    class="max-w-full h-48 object-cover rounded-lg"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Featured Categories -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-bold mb-4">
                            Categorias em Destaque
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                v-for="category in homeData.featuredCategories"
                                :key="category.id"
                                class="text-center"
                            >
                                <img
                                    :src="category.image"
                                    :alt="category.name"
                                    class="w-full h-32 object-cover rounded-lg mb-2"
                                />
                                <h4 class="font-semibold">
                                    {{ category.name }}
                                </h4>
                                <div class="flex justify-center gap-2 mt-2">
                                    <button
                                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition"
                                        @click="saveCategory(category)"
                                    >
                                        Salvar
                                    </button>
                                    <button
                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                                        @click="editCategory(category)"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                        @click="deleteCategory(category)"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Promotional Banner -->
                    <div
                        v-if="homeData.promotionalBanner.active"
                        class="bg-white rounded-lg shadow p-6"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-8 text-center"
                        >
                            <h3 class="text-2xl font-bold mb-2">
                                {{ homeData.promotionalBanner.title }}
                            </h3>
                            <p class="mb-4">
                                {{ homeData.promotionalBanner.description }}
                            </p>
                            <button
                                class="bg-white text-blue-600 px-6 py-2 rounded-lg hover:bg-gray-100"
                                @click="saveBanner"
                            >
                                {{ homeData.promotionalBanner.buttonText }}
                            </button>
                        </div>
                    </div>

                    <!-- Featured Products -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-bold mb-4">
                            {{ homeData.featuredProducts.title }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div
                                v-for="product in homeData.featuredProducts
                                    .items"
                                :key="product.id"
                                class="border rounded-lg p-4"
                            >
                                <img
                                    :src="product.image"
                                    :alt="product.name"
                                    class="w-full h-32 object-cover rounded mb-2"
                                />
                                <h4 class="font-semibold text-sm">
                                    {{ product.name }}
                                </h4>
                                <p class="text-blue-600 font-bold">
                                    R$ {{ formatCurrency(product.price) }}
                                </p>
                                <div class="flex justify-center gap-2 mt-2">
                                    <button
                                        class="px-3 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition"
                                        @click="saveProduct(product)"
                                    >
                                        Salvar
                                    </button>
                                    <button
                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                                        @click="editProduct(product)"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                        @click="deleteProduct(product)"
                                    >
                                        Excluir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Mode -->
        <div v-else class="space-y-6">
            <!-- Hero Section Editor -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b bg-blue-50">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Seção Hero (Topo da Página)
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Título Principal</label
                            >
                            <input
                                v-model="homeData.hero.title"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Subtítulo</label
                            >
                            <input
                                v-model="homeData.hero.subtitle"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Texto do Botão</label
                            >
                            <input
                                v-model="homeData.hero.buttonText"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <div>
                            <ImageUpload
                                v-model="homeData.hero.image"
                                label="Imagem de Fundo do Hero"
                                preview-alt="Preview do hero"
                            />
                        </div>
                    </div>
                </div>

                <!-- Featured Categories Editor -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b bg-green-50">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Categorias em Destaque
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                v-for="category in homeData.featuredCategories"
                                :key="category.id"
                                class="border rounded p-4"
                            >
                                <div class="space-y-3">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                            >Nome da Categoria</label
                                        >
                                        <input
                                            v-model="category.name"
                                            type="text"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                    </div>
                                    <div>
                                        <ImageUpload
                                            v-model="category.image"
                                            label="Imagem da Categoria"
                                            preview-alt="Preview da categoria"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Promotional Banner Editor -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b bg-purple-50">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Banner Promocional
                            </h2>
                            <label class="flex items-center">
                                <input
                                    v-model="homeData.promotionalBanner.active"
                                    type="checkbox"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                />
                                <span class="ml-2 text-sm text-gray-700"
                                    >Ativar banner</span
                                >
                            </label>
                        </div>
                    </div>
                    <div
                        v-if="homeData.promotionalBanner.active"
                        class="p-6 space-y-4"
                    >
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Título do Banner</label
                            >
                            <input
                                v-model="homeData.promotionalBanner.title"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Descrição</label
                            >
                            <textarea
                                v-model="homeData.promotionalBanner.description"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            ></textarea>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Texto do Botão</label
                            >
                            <input
                                v-model="homeData.promotionalBanner.buttonText"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                    </div>
                </div>

                <!-- Featured Products Editor -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b bg-yellow-50">
                        <h2 class="text-lg font-semibold text-gray-900">
                            Produtos em Destaque
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Título da Seção</label
                            >
                            <input
                                v-model="homeData.featuredProducts.title"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>

                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                        >
                            <div
                                v-for="product in homeData.featuredProducts
                                    .items"
                                :key="product.id"
                                class="border rounded p-4"
                            >
                                <div class="space-y-3">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                            >Nome do Produto</label
                                        >
                                        <input
                                            v-model="product.name"
                                            type="text"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1"
                                            >Preço</label
                                        >
                                        <input
                                            v-model.number="product.price"
                                            type="number"
                                            step="0.01"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        />
                                    </div>
                                    <div>
                                        <ImageUpload
                                            v-model="product.image"
                                            label="Imagem do Produto"
                                            preview-alt="Preview do produto"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { useNotification } from "@/composables/useNotification";
import ImageUpload from "@/components/ImageUpload.vue";
import { apiClient } from "@/services/api";

const { showNotification } = useNotification();

const previewMode = ref(false);
const saving = ref(false);

const homeData = ref({
    hero: {
        title: "Chinelos Karibe - Conforto e Estilo",
        subtitle:
            "Descubra nossa coleção de chinelos de alta qualidade para toda a família",
        buttonText: "Ver Produtos",
        image: "https://via.placeholder.com/800x400?text=Hero+Banner",
    },
    featuredCategories: [
        {
            id: 1,
            name: "Chinelos Praia",
            image: "https://via.placeholder.com/300x200?text=Praia",
        },
        {
            id: 2,
            name: "Chinelos Casual",
            image: "https://via.placeholder.com/300x200?text=Casual",
        },
        {
            id: 3,
            name: "Chinelos Esportivos",
            image: "https://via.placeholder.com/300x200?text=Esportivos",
        },
    ],
    promotionalBanner: {
        active: true,
        title: "Frete Grátis para Todo o Brasil!",
        description:
            "Em compras acima de R$ 150,00. Aproveite esta oferta por tempo limitado.",
        buttonText: "Comprar Agora",
    },
    featuredProducts: {
        title: "Produtos em Destaque",
        items: [
            {
                id: 1,
                name: "Chinelo Praia Azul",
                price: 49.9,
                image: "https://via.placeholder.com/200x200?text=P1",
            },
            {
                id: 2,
                name: "Chinelo Casual Preto",
                price: 39.9,
                image: "https://via.placeholder.com/200x200?text=P2",
            },
            {
                id: 3,
                name: "Chinelo Esportivo Branco",
                price: 69.9,
                image: "https://via.placeholder.com/200x200?text=P3",
            },
            {
                id: 4,
                name: "Chinelo Infantil Colorido",
                price: 29.9,
                image: "https://via.placeholder.com/200x200?text=P4",
            },
        ],
    },
});

// Simulação de carregamento dos dados da home
const loadHomeData = async () => {
    try {
        // Simulação - substitua pela chamada real da API
        // const response = await api.get('/home-config')
        // homeData.value = response.data
        console.log("Dados da home carregados:", homeData.value);
    } catch (error) {
        console.error("Erro ao carregar dados da home:", error);
        showNotification(
            "Erro ao carregar configuração da página inicial",
            "error",
        );
    }
};

const saveHomePage = async () => {
    saving.value = true;
    try {
        // Simulação - substitua pela chamada real da API
        // await api.put('/home-config', homeData.value)

        // Simular delay de salvamento
        await new Promise((resolve) => setTimeout(resolve, 1000));

        showNotification("Página inicial salva com sucesso!", "success");
    } catch (error) {
        console.error("Erro ao salvar página inicial:", error);
        showNotification(
            "Erro ao salvar configuração da página inicial",
            "error",
        );
    } finally {
        saving.value = false;
    }
};

// Salvar apenas o banner promocional
const saveBanner = async () => {
    try {
        await apiClient.put(
            "/home-config/banner",
            homeData.value.promotionalBanner,
        );
        showNotification("Banner salvo com sucesso!", "success");
    } catch (error) {
        showNotification("Erro ao salvar banner", "error");
    }
};

// Salvar apenas uma categoria
const saveCategory = async (category) => {
    try {
        await apiClient.put(`/home-config/category/${category.id}`, category);
        showNotification(`Categoria '${category.name}' salva!`, "success");
    } catch (error) {
        showNotification("Erro ao salvar categoria", "error");
    }
};

// Salvar apenas um produto
const saveProduct = async (product) => {
    try {
        await apiClient.put(`/home-config/product/${product.id}`, product);
        showNotification(`Produto '${product.name}' salvo!`, "success");
    } catch (error) {
        showNotification("Erro ao salvar produto", "error");
    }
};

// Editar categoria (exemplo: abrir modal, aqui só mostra notificação)
const editCategory = (category) => {
    showNotification(`Editar categoria: ${category.name}`, "info");
    // Aqui você pode abrir um modal de edição real
};

// Excluir categoria
const deleteCategory = async (category) => {
    try {
        await apiClient.delete(`/home-config/category/${category.id}`);
        // Remove do array local
        homeData.value.featuredCategories =
            homeData.value.featuredCategories.filter(
                (c) => c.id !== category.id,
            );
        showNotification(`Categoria '${category.name}' excluída!`, "success");
    } catch (error) {
        showNotification("Erro ao excluir categoria", "error");
    }
};

// Editar produto (exemplo: abrir modal, aqui só mostra notificação)
const editProduct = (product) => {
    showNotification(`Editar produto: ${product.name}`, "info");
    // Aqui você pode abrir um modal de edição real
};

// Excluir produto
const deleteProduct = async (product) => {
    try {
        await apiClient.delete(`/home-config/product/${product.id}`);
        // Remove do array local
        homeData.value.featuredProducts.items =
            homeData.value.featuredProducts.items.filter(
                (p) => p.id !== product.id,
            );
        showNotification(`Produto '${product.name}' excluído!`, "success");
    } catch (error) {
        showNotification("Erro ao excluir produto", "error");
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("pt-BR", {
        style: "decimal",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value);
};

onMounted(() => {
    loadHomeData();
});
</script>

<style scoped>
.space-y-6 > * + * {
    margin-top: 1.5rem;
}
</style>
