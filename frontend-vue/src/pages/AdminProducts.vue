<template>
    <div class="admin-products-page p-8">
        <h1 class="text-2xl font-bold mb-6">Administração de Produtos</h1>
        <p>Debug: Componente carregado. Produtos: {{ products.length }}</p>
        <p>Debug: Categorias: {{ categories.length }}</p>
        <p v-if="products.length === 0" class="text-red-500">
            Nenhum produto encontrado. Verifique a API.
        </p>
        <form
            @submit.prevent="handleSubmit"
            class="mb-8 bg-white p-6 rounded shadow"
        >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1"
                        >Nome do Produto</label
                    >
                    <input v-model="form.name" class="input" required />
                </div>
                <div>
                    <label class="block font-semibold mb-1">Preço (R$)</label>
                    <input
                        v-model.number="form.price"
                        type="number"
                        min="0"
                        step="0.01"
                        class="input"
                        required
                    />
                </div>
                <div>
                    <label class="block font-semibold mb-1">Categoria</label>
                    <select v-model="form.category_id" class="input" required>
                        <option value="">Selecione uma categoria</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Estoque</label>
                    <input
                        v-model.number="form.stock_quantity"
                        type="number"
                        min="0"
                        class="input"
                        required
                    />
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold mb-1">Descrição</label>
                    <textarea
                        v-model="form.description"
                        class="input"
                        rows="2"
                    ></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold mb-1">Imagens</label>
                    <div class="space-y-4">
                        <!-- Upload de múltiplas imagens -->
                        <div
                            v-for="(image, index) in form.images"
                            :key="index"
                            class="relative inline-block mr-4 mb-4"
                        >
                            <img
                                :src="image.preview || image.url"
                                class="w-24 h-24 object-cover rounded border"
                                :class="{
                                    'border-green-500':
                                        image.status === 'success',
                                    'border-red-500': image.status === 'error',
                                    'border-blue-500':
                                        image.status === 'uploading',
                                }"
                            />
                            <!-- Spinner de carregamento -->
                            <div
                                v-if="image.status === 'uploading'"
                                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded"
                            >
                                <div
                                    class="animate-spin rounded-full h-6 w-6 border-b-2 border-white"
                                ></div>
                            </div>
                            <!-- Ícone de erro -->
                            <div
                                v-if="image.status === 'error'"
                                class="absolute inset-0 flex items-center justify-center bg-red-500 bg-opacity-75 rounded"
                            >
                                <svg
                                    class="w-6 h-6 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    ></path>
                                </svg>
                            </div>
                            <!-- Botão remover -->
                            <button
                                @click="removeImage(index)"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition"
                                title="Remover imagem"
                            >
                                ×
                            </button>
                        </div>

                        <!-- Botão adicionar imagem -->
                        <div
                            @click="$refs.fileInput.click()"
                            class="w-24 h-24 border-2 border-dashed border-gray-300 rounded flex items-center justify-center cursor-pointer hover:border-gray-400 transition"
                        >
                            <svg
                                class="w-8 h-8 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                ></path>
                            </svg>
                        </div>

                        <input
                            ref="fileInput"
                            type="file"
                            multiple
                            accept="image/*"
                            @change="handleFileChange"
                            class="hidden"
                        />
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        PNG, JPG até 5MB cada
                    </p>
                </div>
            </div>
            <button
                type="submit"
                :disabled="isSubmitting || hasUploadingImages"
                class="btn btn-primary mt-4 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span v-if="isSubmitting">Salvando...</span>
                <span v-else>{{
                    editingId ? "Atualizar Produto" : "Salvar Produto"
                }}</span>
            </button>
        </form>

        <h2 class="text-xl font-semibold mb-2">Produtos Cadastrados</h2>
        <table class="w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="p-2">Imagem</th>
                    <th class="p-2">Nome</th>
                    <th class="p-2">Preço</th>
                    <th class="p-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td class="p-2">
                        <img
                            :src="
                                product.images?.[0] || '/placeholder-image.png'
                            "
                            class="w-16 h-16 object-cover rounded"
                            :alt="product.name"
                            @error="handleImageError"
                            v-if="product.images && product.images.length"
                        />
                        <div
                            v-else
                            class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-gray-500 text-xs"
                        >
                            Sem imagem
                        </div>
                    </td>
                    <td class="p-2">{{ product.name }}</td>
                    <td class="p-2">
                        R$ {{ (parseFloat(product.price) || 0).toFixed(2) }}
                    </td>
                    <td class="p-2">
                        <button
                            class="btn btn-xs btn-warning mr-2"
                            @click="editProduct(product)"
                        >
                            Editar
                        </button>
                        <button
                            class="btn btn-xs btn-danger"
                            @click="deleteProduct(product.id)"
                        >
                            Excluir
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import productService from "@/services/productService";
import { adminService } from "@/services/adminService";
import { useNotification } from "@/composables/useNotification";
import { useImageUpload } from "@/composables/useImageUpload";

const { showNotification } = useNotification();
const { uploading: globalUploading, uploadImage } = useImageUpload();

const route = useRoute();

console.log("AdminProducts: Script executado, componente sendo inicializado");

const products = ref([]);
const categories = ref([]);
const form = ref({
    name: "",
    price: 0,
    description: "",
    category_id: "",
    stock_quantity: 10,
    images: [],
});
const editingId = ref(null);
const isSubmitting = ref(false);
const fileInput = ref(null);

// Computed para verificar se há imagens sendo carregadas
const hasUploadingImages = computed(() =>
    form.value.images.some((img) => img.status === "uploading"),
);

async function fetchProducts() {
    try {
        console.log("Buscando produtos...");
        console.log("adminService disponível:", !!adminService);
        console.log(
            "adminService.getAdminProducts:",
            typeof adminService.getAdminProducts,
        );
        const data = await adminService.getAdminProducts();
        console.log("Produtos recebidos:", data);
        console.log("Tipo de data:", typeof data);
        console.log("data.data:", data.data);
        products.value = data.data || data;
        console.log("Produtos atribuídos:", products.value);
    } catch (error) {
        console.error("Erro ao buscar produtos:", error);
        console.error(
            "Detalhes do erro:",
            error.response?.data || error.message,
        );
        // Não mostrar notificação para evitar spam, apenas log
        products.value = [];
    }
}

async function fetchCategories() {
    try {
        console.log("Buscando categorias...");
        const data = await adminService.getCategories();
        console.log("Categorias recebidas:", data);
        categories.value = data.data || data;
    } catch (error) {
        console.error("Erro ao buscar categorias:", error);
        showNotification("Erro ao carregar categorias", "error");
    }
}

async function handleFileChange(e) {
    const files = Array.from(e.target.files);

    for (const file of files) {
        // Criar objeto de imagem com status
        const imageObj = {
            file: file,
            preview: URL.createObjectURL(file),
            status: "uploading",
            url: null,
        };

        // Adicionar à lista
        form.value.images.push(imageObj);
        const imageIndex = form.value.images.length - 1;

        try {
            console.log("Iniciando upload da imagem:", file.name);
            // Fazer upload com timeout
            const uploadPromise = uploadImage(file, "products");
            const timeoutPromise = new Promise((_, reject) =>
                setTimeout(() => reject(new Error("Upload timeout")), 30000),
            );

            const uploadResult = await Promise.race([
                uploadPromise,
                timeoutPromise,
            ]);

            if (uploadResult) {
                console.log("Upload concluído com sucesso:", uploadResult);
                // Garantir reatividade usando splice
                const updatedImage = {
                    ...form.value.images[imageIndex],
                    status: "success",
                    url: uploadResult,
                };
                form.value.images.splice(imageIndex, 1, updatedImage);
                await nextTick();
            } else {
                console.error("Upload falhou - resultado vazio");
                const updatedImage = {
                    ...form.value.images[imageIndex],
                    status: "error",
                };
                form.value.images.splice(imageIndex, 1, updatedImage);
                await nextTick();
            }
        } catch (error) {
            console.error("Erro no upload da imagem:", file.name, error);
            const updatedImage = {
                ...form.value.images[imageIndex],
                status: "error",
            };
            form.value.images.splice(imageIndex, 1, updatedImage);
            await nextTick();
            showNotification(
                `Erro ao fazer upload da imagem ${file.name}`,
                "error",
            );
        }
    }

    // Reset input
    e.target.value = "";
}

function removeImage(index) {
    const image = form.value.images[index];
    if (image && image.preview) {
        URL.revokeObjectURL(image.preview);
    }
    form.value.images.splice(index, 1);
}

function handleImageError(event) {
    // Quando uma imagem falha ao carregar, substitui por uma imagem placeholder
    event.target.src = "/placeholder-image.png";
}

function editProduct(product) {
    editingId.value = product.id;
    form.value = {
        name: product.name || "",
        price: parseFloat(product.price) || 0,
        description: product.description || "",
        category_id: product.category_id || "",
        stock_quantity: product.stock_quantity || 10,
        images: (product.images || []).map((url) => ({
            url: url,
            preview: url,
            status: "success",
        })),
    };
}

async function handleSubmit() {
    if (hasUploadingImages.value) {
        showNotification("Aguarde o upload das imagens terminar", "warning");
        return;
    }

    isSubmitting.value = true;

    try {
        // Filtrar apenas imagens com sucesso
        const successfulImages = form.value.images
            .filter((img) => img.status === "success")
            .map((img) => img.url);

        console.log("Imagens filtradas:", successfulImages);

        const productData = {
            name: form.value.name,
            price: parseFloat(form.value.price) || 0,
            description: form.value.description,
            category_id: parseInt(form.value.category_id),
            stock: parseInt(form.value.stock_quantity) || 0,
            sku:
                form.value.name
                    .toLowerCase()
                    .replace(/\s+/g, "-")
                    .replace(/[^a-z0-9-]/g, "") +
                "-" +
                Date.now(),
            status: "active",
            images: successfulImages,
        };

        console.log("Dados do produto a serem enviados:", productData);

        if (editingId.value) {
            // Atualizar produto
            console.log("Atualizando produto ID:", editingId.value);
            await adminService.updateProduct(editingId.value, productData);
            showNotification("Produto atualizado com sucesso!", "success");
            editingId.value = null;
        } else {
            // Criar novo produto
            console.log("Criando novo produto");
            await adminService.createProduct(productData);
            showNotification("Produto criado com sucesso!", "success");
        }

        // Recarregar produtos
        await fetchProducts();

        // Limpar formulário
        form.value = {
            name: "",
            price: 0,
            description: "",
            category_id: "",
            stock_quantity: 10,
            images: [],
        };
    } catch (error) {
        console.error("Erro ao salvar produto:", error);
        showNotification("Erro ao salvar produto", "error");
    } finally {
        isSubmitting.value = false;
    }
}

async function deleteProduct(id) {
    if (confirm("Tem certeza que deseja excluir este produto?")) {
        try {
            await adminService.deleteProduct(id);
            showNotification("Produto excluído com sucesso!", "success");
            // Recarregar produtos após exclusão
            await fetchProducts();
        } catch (error) {
            console.error("Erro ao excluir produto:", error);
            showNotification("Erro ao excluir produto", "error");
        }
    }
}

onMounted(() => {
    console.log("AdminProducts: onMounted executado - componente montado");
    console.log("AdminProducts: Iniciando fetchProducts e fetchCategories");
    fetchProducts();
    fetchCategories();
    console.log("AdminProducts: Chamadas de fetch iniciadas");
});

// Watcher para recarregar dados quando a rota muda
watch(
    () => route.path,
    (newPath, oldPath) => {
        if (newPath !== oldPath && newPath === '/admin/produtos') {
            console.log("AdminProducts: Rota voltou para produtos, recarregando dados");
            fetchProducts();
            fetchCategories();
        }
    }
);
</script>

<style scoped>
.input {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
}
.btn {
    padding: 0.75rem 1rem;
    border-radius: 4px;
    font-weight: 600;
}
.btn-primary {
    background-color: #2563eb;
    color: white;
}
.btn-primary:hover {
    background-color: #1d4ed8;
}
.btn-warning {
    background-color: #fbbf24;
    color: black;
}
.btn-warning:hover {
    background-color: #f59e0b;
}
.btn-danger {
    background-color: #dc2626;
    color: white;
}
.btn-danger:hover {
    background-color: #b91c1c;
}
.btn-xs {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* Animações */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Estados das imagens */
.image-success {
    border-color: #10b981;
}

.image-error {
    border-color: #ef4444;
}

.image-uploading {
    border-color: #3b82f6;
}
</style>
