<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">
                Gerenciar Categorias
            </h1>
            <button
                @click="
                    showModal = true;
                    editingCategory = null;
                    formErrors = {};
                    form = {
                        name: '',
                        description: '',
                        image: '',
                        order: categories.length + 1,
                        active: true,
                    };
                "
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
            >
                + Nova Categoria
            </button>
        </div>

        <!-- Lista de Categorias -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-900">
                    Categorias Cadastradas
                </h2>
            </div>

            <div v-if="loading" class="p-8 text-center">
                <div
                    class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"
                ></div>
                <p class="mt-2 text-gray-600">Carregando categorias...</p>
            </div>

            <div
                v-else-if="categories.length === 0"
                class="p-8 text-center text-gray-500"
            >
                Nenhuma categoria cadastrada ainda.
            </div>

            <div v-else class="divide-y">
                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="p-6 hover:bg-gray-50 transition"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500"
                                    >Ordem:</span
                                >
                                <input
                                    v-model.number="category.order"
                                    @change="updateOrder(category)"
                                    type="number"
                                    min="1"
                                    class="w-16 px-2 py-1 border rounded text-sm"
                                />
                            </div>

                            <div
                                v-if="category.image"
                                class="w-12 h-12 rounded-lg overflow-hidden bg-gray-200"
                            >
                                <img
                                    :src="category.image"
                                    :alt="category.name"
                                    class="w-full h-full object-cover"
                                />
                            </div>

                            <div>
                                <h3 class="font-medium text-gray-900">
                                    {{ category.name }}
                                </h3>
                                <p
                                    v-if="category.description"
                                    class="text-sm text-gray-600"
                                >
                                    {{ category.description }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <button
                                @click="editCategory(category)"
                                class="text-blue-600 hover:text-blue-600 px-3 py-1 rounded text-sm"
                            >
                                Editar
                            </button>
                            <button
                                @click="deleteCategory(category.id)"
                                class="text-red-600 hover:text-red-800 px-3 py-1 rounded text-sm"
                            >
                                Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Criação/Edição -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-semibold mb-4">
                    {{
                        editingCategory ? "Editar Categoria" : "Nova Categoria"
                    }}
                </h3>

                <form @submit.prevent="saveCategory" class="space-y-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nome</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        <p
                            v-if="formErrors.name"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ formErrors.name[0] }}
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Descrição</label
                        >
                        <textarea
                            v-model="form.description"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                        <p
                            v-if="formErrors.description"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ formErrors.description[0] }}
                        </p>
                    </div>

                    <div>
                        <ImageUpload
                            v-model="form.image"
                            label="Imagem da Categoria"
                            preview-alt="Preview da categoria"
                        />
                        <p
                            v-if="formErrors.image"
                            class="text-sm text-red-600 mt-1"
                        >
                            {{ formErrors.image[0] }}
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Ordem</label
                        >
                        <input
                            v-model.number="form.order"
                            type="number"
                            min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            :disabled="saving"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ saving ? "Salvando..." : "Salvar" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Confirm modal -->
        <ConfirmModal
            :visible="confirmVisible"
            title="Excluir categoria"
            message="Deseja realmente excluir esta categoria?"
            @confirm="onConfirmDelete"
            @cancel="onCancelDelete"
        />
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useNotification } from "@/composables/useNotification";
import ImageUpload from "@/components/ImageUpload.vue";
import ConfirmModal from "@/components/ConfirmModal.vue";
import { adminService } from "@/services/adminService";
import { resolveAssetUrl } from "@/services/api";

const { showNotification } = useNotification();

const categories = ref([]);
const loading = ref(false);
const saving = ref(false);
const formErrors = ref({});
const showModal = ref(false);
const editingCategory = ref(null);

const form = ref({
    name: "",
    description: "",
    image: "",
    order: 1,
    active: true,
});

const fetchCategories = async () => {
    loading.value = true;
    try {
        const res = await adminService.getCategories();
        const data = Array.isArray(res) ? res : (res?.data ?? []);
        categories.value = data.map((c) => ({
            ...c,
            image: resolveAssetUrl(c.image),
        }));
    } catch (error) {
        console.error("Erro ao carregar categorias:", error);
        showNotification("Erro ao carregar categorias", "error");
        categories.value = [];
    } finally {
        loading.value = false;
    }
};

const saveCategory = async () => {
    saving.value = true;
    formErrors.value = {};
    try {
        if (editingCategory.value) {
            const res = await adminService.updateCategory(
                editingCategory.value.id,
                form.value,
            );
            const updated = res?.data ?? res;
            const idx = categories.value.findIndex(
                (c) => c.id === editingCategory.value.id,
            );
            if (idx !== -1)
                categories.value[idx] = {
                    ...categories.value[idx],
                    ...updated,
                };
            showNotification("Categoria atualizada com sucesso!", "success");
        } else {
            const res = await adminService.createCategory(form.value);
            const created = res?.data ?? res;
            categories.value.push(created);
            showNotification("Categoria criada com sucesso!", "success");
        }

        showModal.value = false;
        form.value = {
            name: "",
            description: "",
            image: "",
            order: categories.value.length + 1,
            active: true,
        };
        formErrors.value = {};
    } catch (error) {
        console.error("Erro ao salvar categoria:", error);
        if (error?.response && error.response.status === 422) {
            formErrors.value = error.response.data.errors || {};
            const messages = Object.values(formErrors.value).flat().join(", ");
            showNotification(messages || "Dados inválidos", "error");
        } else {
            showNotification("Erro ao salvar categoria", "error");
        }
    } finally {
        saving.value = false;
    }
};

const editCategory = (category) => {
    editingCategory.value = category;
    form.value = { ...category };
    showModal.value = true;
};

const deleteCategory = async (id) => {
    // Abrir modal de confirmação
    confirmTargetId.value = id;
    confirmVisible.value = true;
};

const confirmVisible = ref(false);
const confirmTargetId = ref(null);

const onConfirmDelete = async () => {
    const id = confirmTargetId.value;
    confirmVisible.value = false;
    confirmTargetId.value = null;
    try {
        await adminService.deleteCategory(id);
        categories.value = categories.value.filter((c) => c.id !== id);
        showNotification("Categoria excluída com sucesso!", "success");
    } catch (error) {
        console.error("Erro ao excluir categoria:", error);
        showNotification("Erro ao excluir categoria", "error");
    }
};

const onCancelDelete = () => {
    confirmVisible.value = false;
    confirmTargetId.value = null;
};

const updateOrder = async (category) => {
    try {
        // Se o backend possuir endpoint para ordem, chame-o aqui. Caso contrário,
        // atualizamos localmente e assumimos que o adminService.updateCategory cuidará.
        await adminService.updateCategory(category.id, {
            order: category.order,
        });
        showNotification("Ordem atualizada!", "success");
    } catch (error) {
        console.error("Erro ao atualizar ordem:", error);
        showNotification("Erro ao atualizar ordem", "error");
    }
};

onMounted(() => {
    fetchCategories();
});
</script>

<style scoped>
.space-y-6 > * + * {
    margin-top: 1.5rem;
}
</style>
