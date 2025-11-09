<template>
    <div class="admin-products-page p-8">
        <h1 class="text-2xl font-bold mb-6">Administração de Produtos</h1>
        <p>Debug: Componente carregado. Produtos: {{ products.length }}</p>
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
                    <input type="file" multiple @change="handleFileChange" />
                    <div class="flex mt-2 space-x-2">
                        <img
                            v-for="(img, idx) in previewImages"
                            :key="idx"
                            :src="img"
                            class="w-20 h-20 object-cover rounded border"
                        />
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">
                Salvar Produto
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
                            :src="product.images?.[0]"
                            class="w-16 h-16 object-cover rounded"
                            v-if="product.images && product.images.length"
                        />
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
import { ref, onMounted } from "vue";
import productService from "@/services/productService";
import { useNotification } from "@/composables/useNotification";

const { showNotification } = useNotification();

const products = ref([]);
const form = ref({ name: "", price: 0, description: "", images: [] });
const previewImages = ref([]);
const editingId = ref(null);

async function fetchProducts() {
    try {
        console.log("Buscando produtos...");
        const data = await productService.getProducts();
        console.log("Produtos recebidos:", data);
        products.value = data.data || data; // API retorna {data: [...], meta: ...}
    } catch (error) {
        console.error("Erro ao buscar produtos:", error);
        showNotification("Erro ao carregar produtos", "error");
    }
}

function handleFileChange(e) {
    const files = Array.from(e.target.files);
    form.value.images = files;
    previewImages.value = files.map((file) => URL.createObjectURL(file));
}

function editProduct(product) {
    editingId.value = product.id;
    form.value = { ...product, images: [] };
    previewImages.value = product.images || [];
}

async function handleSubmit() {
    try {
        // Upload das imagens (se houver arquivos novos)
        let imageUrls = [];
        if (
            form.value.images &&
            form.value.images.length > 0 &&
            form.value.images[0] instanceof File
        ) {
            for (const file of form.value.images) {
                const uploadResult = await productService.uploadImage(file);
                if (
                    uploadResult &&
                    uploadResult.data &&
                    uploadResult.data.url
                ) {
                    imageUrls.push(uploadResult.data.url);
                }
            }
        } else if (previewImages.value && previewImages.value.length > 0) {
            // Se já são URLs (edição)
            imageUrls = previewImages.value;
        }

        const productData = {
            name: form.value.name,
            price: parseFloat(form.value.price) || 0,
            description: form.value.description,
            images: JSON.stringify(imageUrls), // Corrigido: enviar como string JSON
            category_id: 1, // Categoria padrão
            stock_quantity: 10, // Estoque padrão
            is_active: true,
        };

        if (editingId.value) {
            // Atualizar produto
            await productService.updateProduct(editingId.value, productData);
            showNotification("Produto atualizado com sucesso!", "success");
            editingId.value = null;
        } else {
            // Criar novo produto
            await productService.createProduct(productData);
            showNotification("Produto criado com sucesso!", "success");
        }

        // Recarregar produtos
        await fetchProducts();

        // Limpar formulário
        form.value = { name: "", price: 0, description: "", images: [] };
        previewImages.value = [];
    } catch (error) {
        console.error("Erro ao salvar produto:", error);
        showNotification("Erro ao salvar produto", "error");
    }
}

async function deleteProduct(id) {
    if (confirm("Tem certeza que deseja excluir este produto?")) {
        try {
            await productService.deleteProduct(id);
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
    console.log("AdminProducts mounted");
    fetchProducts();
});
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
</style>
