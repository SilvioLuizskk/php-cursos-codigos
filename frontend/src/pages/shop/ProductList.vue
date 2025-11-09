<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Produtos</h1>

    <!-- Filtros e Busca -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Busca -->
        <Input
          v-model="filters.search"
          placeholder="Buscar produtos..."
          class="md:col-span-2"
          @input="debouncedSearch"
        />

        <!-- Categoria -->
        <Select
          v-model="filters.category_id"
          :options="categories"
          placeholder="Todas as categorias"
          @change="applyFilters"
        />

        <!-- Ordenação -->
        <Select
          v-model="filters.sort_by"
          :options="sortOptions"
          @change="applyFilters"
        />
      </div>

      <!-- Filtros Avançados -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
        <Input
          v-model.number="filters.min_price"
          type="number"
          placeholder="Preço mínimo"
          @input="debouncedApplyFilters"
        />

        <Input
          v-model.number="filters.max_price"
          type="number"
          placeholder="Preço máximo"
          @input="debouncedApplyFilters"
        />

        <Input
          v-model.number="filters.rating"
          type="number"
          step="0.1"
          min="0"
          max="5"
          placeholder="Avaliação mínima"
          @input="debouncedApplyFilters"
        />

        <div class="flex gap-2">
          <Button @click="applyFilters" variant="primary" size="sm">
            Filtrar
          </Button>
          <Button @click="resetFilters" variant="outline" size="sm">
            Limpar
          </Button>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-12">
      <LoadingSpinner />
    </div>

    <!-- Produtos -->
    <div
      v-else-if="products.length > 0"
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
    >
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
        @add-to-cart="handleAddToCart(product)"
      />
    </div>

    <!-- Sem resultados -->
    <div v-else class="text-center py-12">
      <div class="text-gray-400 mb-4">
        <svg
          class="w-16 h-16 mx-auto"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-5.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
          />
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">
        Nenhum produto encontrado
      </h3>
      <p class="text-gray-600 mb-4">
        Tente ajustar seus filtros ou termos de busca
      </p>
      <Button @click="resetFilters" variant="primary">
        Ver todos os produtos
      </Button>
    </div>

    <!-- Paginação -->
    <div v-if="pagination.last_page > 1" class="mt-8 flex justify-center">
      <Pagination
        :current-page="pagination.current_page"
        :last-page="pagination.last_page"
        :total="pagination.total"
        @change="changePage"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { useProducts } from "@/composables/useProducts";
import { useCart } from "@/composables/useCart";
import { useDebounce } from "@/composables/useDebounce";
import Input from "@/components/base/Input.vue";
import Select from "@/components/base/Select.vue";
import Button from "@/components/base/Button.vue";
import ProductCard from "@/components/product/ProductCard.vue";
import Pagination from "@/components/base/Pagination.vue";
import LoadingSpinner from "@/components/base/LoadingSpinner.vue";

const { products, filters, pagination, loading, fetchProducts, setFilters } =
  useProducts();
const { addToCart } = useCart();
useProducts();

// Categorias (mock - em produção viria da API)
const categories = ref([
  { value: "", label: "Todas as categorias" },
  { value: "1", label: "Camisetas" },
  { value: "2", label: "Canecas" },
  { value: "3", label: "Almofadas" },
]);

// Opções de ordenação
const sortOptions = ref([
  { value: "created_at", label: "Mais recentes" },
  { value: "price", label: "Menor preço" },
  { value: "price_desc", label: "Maior preço" },
  { value: "rating", label: "Melhor avaliado" },
  { value: "name", label: "Nome A-Z" },
]);

// Debounce para busca
const { debounced: debouncedSearch } = useDebounce(() => {
  filters.page = 1;
  fetchProducts();
}, 500);

const { debounced: debouncedApplyFilters } = useDebounce(() => {
  filters.page = 1;
  fetchProducts();
}, 300);

// Aplicar filtros
function applyFilters() {
  filters.page = 1;
  fetchProducts();
}

// Resetar filtros
function resetFilters() {
  setFilters({
    page: 1,
    per_page: 20,
    category_id: "",
    search: "",
    min_price: null,
    max_price: null,
    rating: null,
    sort_by: "created_at",
    sort_order: "desc",
  });
  fetchProducts();
}

// Mudar página
function changePage(page) {
  filters.page = page;
  fetchProducts();
}

// Adicionar ao carrinho
async function handleAddToCart(product) {
  try {
    await addToCart(product.id, 1);
    alert('Produto adicionado ao carrinho!');
  } catch (error) {
    alert(error.response?.data?.message || 'Erro ao adicionar produto');
  }
}

// Carregar produtos ao montar
onMounted(() => {
  fetchProducts();
});
</script>
