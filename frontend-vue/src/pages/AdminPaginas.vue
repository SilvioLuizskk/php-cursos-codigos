<template>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">
                Gerenciar Páginas Estáticas
            </h1>
            <button
                @click="
                        showModal = true;
                        editingPage = null;
                        formErrors = {};
                        form = {
                            title: '',
                            slug: '',
                            content: '',
                            active: true,
                            order: pages.length + 1,
                        };
                    "
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
            >
                + Nova Página
            </button>
        </div>

        <!-- Lista de Páginas -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-900">
                    Páginas do Site
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Página
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Slug
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Ordem
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="page in pages"
                            :key="page.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ page.title }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ page.content.substring(0, 50) }}...
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900"
                                    >/{{ page.slug }}</span
                                >
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'px-2 py-1 rounded text-xs',
                                        page.active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{ page.active ? "Ativa" : "Inativa" }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                            >
                                {{ page.order }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                            >
                                <button
                                    @click="editPage(page)"
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                >
                                    Editar
                                </button>
                                <button
                                    @click="deletePage(page.id)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div
                v-if="pages.length === 0"
                class="text-center py-8 text-gray-500"
            >
                Nenhuma página cadastrada
            </div>
        </div>

        <!-- Modal de Criação/Edição -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div
                class="bg-white rounded-lg p-6 w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto"
            >
                <h3 class="text-lg font-semibold mb-4">
                    {{ editingPage ? "Editar Página" : "Nova Página" }}
                </h3>

                <form @submit.prevent="savePage" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Título da Página</label
                            >
                            <input
                                    v-model="form.title"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            <p v-if="formErrors.title" class="text-sm text-red-600 mt-1">{{ formErrors.title[0] }}</p>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Slug (URL)</label
                            >
                            <input
                                v-model="form.slug"
                                type="text"
                                required
                                placeholder="sobre-nos"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                            <p v-if="formErrors.slug" class="text-sm text-red-600 mt-1">{{ formErrors.slug[0] }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Será acessível em: /{{ form.slug }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Conteúdo</label
                        >
                        <textarea
                            v-model="form.content"
                            rows="12"
                            required
                            placeholder="Digite o conteúdo da página aqui..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                        <p v-if="formErrors.content" class="text-sm text-red-600 mt-1">{{ formErrors.content[0] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Suporte a HTML básico e quebras de linha
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <input
                                v-model="form.active"
                                type="checkbox"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label class="ml-2 block text-sm text-gray-900"
                                >Página ativa</label
                            >
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Ordem de exibição</label
                            >
                            <input
                                v-model.number="form.order"
                                type="number"
                                min="1"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                            <p v-if="formErrors.order" class="text-sm text-red-600 mt-1">{{ formErrors.order[0] }}</p>
                        </div>
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
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useNotification } from "@/composables/useNotification";
import pageService from "@/services/pageService";

const { showNotification } = useNotification();

const pages = ref([]);
const loading = ref(false);
const saving = ref(false);
const formErrors = ref({});
const showModal = ref(false);
const editingPage = ref(null);

const form = ref({
    title: "",
    slug: "",
    content: "",
    active: true,
    order: 1,
});

// Carregar páginas da API
const loadPages = async () => {
    loading.value = true;
    try {
        const response = await pageService.getPages();
        pages.value = response.data || response;
    } catch (error) {
        console.error("Erro ao carregar páginas:", error);
        showNotification("Erro ao carregar páginas", "error");
    } finally {
        loading.value = false;
    }
};

// Função para gerar slug automaticamente
const generateSlug = (title) => {
    return title
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "") // Remove acentos
        .replace(/[^a-z0-9\s-]/g, "") // Remove caracteres especiais
        .trim()
        .replace(/\s+/g, "-") // Substitui espaços por hífens
        .replace(/-+/g, "-"); // Remove hífens consecutivos
};

// Watcher para gerar slug automaticamente quando o título muda
watch(
    () => form.value.title,
    (newTitle) => {
        if (newTitle && !editingPage.value) {
            // Só gera automaticamente para novas páginas
            form.value.slug = generateSlug(newTitle);
        }
    },
);

const savePage = async () => {
    console.log("savePage chamado com form:", form.value);
    saving.value = true;
    try {
        // Validar campos obrigatórios
        if (!form.value.title.trim()) {
            showNotification("O título da página é obrigatório.", "error");
            return;
        }

        if (!form.value.slug.trim()) {
            showNotification("O slug da página é obrigatório.", "error");
            return;
        }

        if (!form.value.content.trim()) {
            showNotification("O conteúdo da página é obrigatório.", "error");
            return;
        }

        // Validar formato do slug (apenas letras, números e hífens)
        const slugRegex = /^[a-z0-9-]+$/;
        if (!slugRegex.test(form.value.slug)) {
            showNotification(
                "O slug deve conter apenas letras minúsculas, números e hífens.",
                "error",
            );
            return;
        }

        // Validar slug único
        const slugExists = pages.value.some(
            (page) =>
                page.slug === form.value.slug &&
                page.id !== (editingPage.value?.id || null),
        );

        if (slugExists) {
            showNotification(
                "Este slug já está em uso. Escolha outro.",
                "error",
            );
            return;
        }

        if (editingPage.value) {
            console.log("Atualizando página existente:", editingPage.value.id);
            await pageService.updatePage(editingPage.value.id, form.value);
            showNotification("Página atualizada com sucesso!", "success");
        } else {
            console.log("Criando nova página");
            await pageService.createPage(form.value);
            showNotification("Página criada com sucesso!", "success");
        }

        // Recarregar páginas
        console.log("Recarregando páginas após salvar...");
        await loadPages();
        console.log("Páginas recarregadas:", pages.value);

        showModal.value = false;
        form.value = {
            title: "",
            slug: "",
            content: "",
            active: true,
            order: pages.value.length + 1,
        };
        editingPage.value = null;
    } catch (error) {
        console.error("Erro ao salvar página:", error);
        formErrors.value = {};

        // Tentar extrair mensagem de erro da resposta da API
        let errorMessage = "Erro ao salvar página";
        if (error.response) {
            if (error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            }

            if (error.response.data && error.response.data.errors) {
                // Se houver erros de validação específicos
                formErrors.value = error.response.data.errors || {};
                const errors = Object.values(formErrors.value).flat();
                errorMessage = errors.join(", ");
            } else if (error.response.status === 422) {
                errorMessage =
                    "Dados inválidos. Verifique os campos e tente novamente.";
            } else if (error.response.status === 500) {
                errorMessage =
                    "Erro interno do servidor. Tente novamente mais tarde.";
            }
        } else if (error.message) {
            errorMessage = error.message;
        }

        showNotification(errorMessage, "error");
    } finally {
        saving.value = false;
    }
};

const editPage = (page) => {
    editingPage.value = page;
    form.value = { ...page };
    showModal.value = true;
};

const deletePage = async (id) => {
    if (
        !confirm(
            "Tem certeza que deseja excluir esta página? Esta ação não pode ser desfeita.",
        )
    )
        return;

    try {
        await pageService.deletePage(id);
        showNotification("Página excluída com sucesso!", "success");
        // Recarregar páginas
        await loadPages();
    } catch (error) {
        console.error("Erro ao excluir página:", error);
        showNotification("Erro ao excluir página", "error");
    }
};

onMounted(() => {
    loadPages();
});
</script>

<style scoped>
.space-y-6 > * + * {
    margin-top: 1.5rem;
}
</style>
