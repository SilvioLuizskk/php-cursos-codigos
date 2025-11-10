<template>
    <div class="admin-layout min-h-screen bg-gray-50 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r flex flex-col">
            <div class="h-16 flex items-center justify-center border-b">
                <span class="text-xl font-bold text-blue-700"
                    >Admin Dashboard</span
                >
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <router-link to="/admin" class="nav-link" active-class="active">
                    🏠 Dashboard
                </router-link>
                <router-link
                    to="/admin/produtos"
                    class="nav-link"
                    active-class="active"
                >
                    📦 Produtos
                </router-link>
                <router-link
                    to="/admin/categorias"
                    class="nav-link"
                    active-class="active"
                >
                    🗂️ Categorias
                </router-link>
                <router-link
                    to="/admin/banners"
                    class="nav-link"
                    active-class="active"
                >
                    🎉 Banners & Promoções
                </router-link>
                <router-link
                    to="/admin/paginas"
                    class="nav-link"
                    active-class="active"
                >
                    📄 Páginas Estáticas
                </router-link>
                <router-link
                    to="/admin/configuracoes"
                    class="nav-link"
                    active-class="active"
                >
                    ⚙️ Configurações
                </router-link>
                <router-link
                    to="/admin/pedidos"
                    class="nav-link"
                    active-class="active"
                >
                    🧾 Pedidos
                </router-link>
                <router-link
                    to="/admin/metricas"
                    class="nav-link"
                    active-class="active"
                >
                    📊 Métricas
                </router-link>
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
        <main class="flex-1 overflow-y-auto">
            <router-view />
        </main>
    </div>
</template>

<script setup>
import { onMounted } from "vue";

onMounted(() => {
    console.log("AdminLayout: Componente montado");
});

// Função de logout
const handleLogout = () => {
    console.log("AdminLayout: Logout executado");
    localStorage.removeItem("auth_token");
    localStorage.removeItem("auth_user");
    window.location.href = "/admin-login";
};
</script>

<style scoped>
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

/* Transições */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
