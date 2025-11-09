<template>
    <div class="admin-dashboard min-h-screen bg-gray-50 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r flex flex-col">
            <div class="h-16 flex items-center justify-center border-b">
                <span class="text-xl font-bold text-blue-700"
                    >Admin Dashboard</span
                >
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <router-link
                    to="/admin/home"
                    class="nav-link"
                    active-class="active"
                    >🏠 Home Editor</router-link
                >
                <router-link
                    to="/admin/produtos"
                    class="nav-link"
                    active-class="active"
                    >📦 Produtos</router-link
                >
                <router-link
                    to="/admin/categorias"
                    class="nav-link"
                    active-class="active"
                    >🗂️ Categorias</router-link
                >
                <router-link
                    to="/admin/banners"
                    class="nav-link"
                    active-class="active"
                    >🎉 Banners & Promoções</router-link
                >
                <router-link
                    to="/admin/paginas"
                    class="nav-link"
                    active-class="active"
                    >📄 Páginas Estáticas</router-link
                >
                <router-link
                    to="/admin/configuracoes"
                    class="nav-link"
                    active-class="active"
                    >⚙️ Configurações</router-link
                >
                <router-link
                    to="/admin/pedidos"
                    class="nav-link"
                    active-class="active"
                    >🧾 Pedidos</router-link
                >
                <router-link
                    to="/admin/metricas"
                    class="nav-link"
                    active-class="active"
                    >📊 Métricas</router-link
                >
            </nav>
            <div class="p-4 border-t">
                <button
                    @click="handleLogout"
                    class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition"
                >
                    Sair
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto">
            <router-view />
        </main>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useNotification } from "@/composables/useNotification";

const router = useRouter();
const { showNotification } = useNotification();

const handleLogout = async () => {
    try {
        // Simulação - em produção, faça logout na API
        // await api.post('/auth/logout')

        // Limpar dados de autenticação (localStorage, cookies, etc.)
        localStorage.removeItem("auth_token");
        localStorage.removeItem("user");

        showNotification("Logout realizado com sucesso!", "success");

        // Redirecionar para a página de login
        router.push("/login");
    } catch (error) {
        console.error("Erro ao fazer logout:", error);
        showNotification("Erro ao fazer logout", "error");
    }
};
</script>

<style scoped>
.admin-dashboard {
    font-family: "Inter", system-ui, sans-serif;
}
.nav-link {
    display: block;
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    color: #374151;
    font-weight: 500;
    transition:
        background 0.2s,
        color 0.2s;
}
.nav-link:hover {
    background: #f3f4f6;
    color: #2563eb;
}
.active {
    background: #2563eb;
    color: #fff !important;
}
</style>
