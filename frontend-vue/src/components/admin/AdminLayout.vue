<template>
    <div class="admin-layout">
        <!-- Admin Context Provider -->
        <AdminContextProvider
            :user="currentUser"
            :permissions="userPermissions"
            :theme="adminTheme"
            @theme-change="handleThemeChange"
            @permission-update="handlePermissionUpdate"
        >
            <!-- Sidebar -->
            <aside class="admin-sidebar">
                <AdminSidebar
                    :menu-items="menuItems"
                    :active-route="$route.path"
                    @menu-click="handleMenuClick"
                />
            </aside>

            <!-- Main Content -->
            <main class="admin-main">
                <AdminHeader
                    :user="currentUser"
                    :notifications="notifications"
                    @logout="handleLogout"
                    @notification-read="handleNotificationRead"
                />

                <div class="admin-content">
                    <router-view />
                </div>
            </main>
        </AdminContextProvider>
    </div>
</template>

<script setup>
import { ref, provide, onMounted } from "vue";
import { useRouter } from "vue-router";
import AdminContextProvider from "@/components/admin/AdminContextProvider.vue";
import AdminSidebar from "@/components/admin/AdminSidebar.vue";
import AdminHeader from "@/components/admin/AdminHeader.vue";

// Dados do usuário atual (passados para o contexto)
const currentUser = ref({
    id: 1,
    name: "Admin User",
    email: "admin@chineloskaribe.com",
    avatar: "/avatar-admin.jpg",
    role: "admin",
});

// Permissões do usuário (passadas para o contexto)
const userPermissions = ref([
    "products.read",
    "products.write",
    "orders.read",
    "orders.write",
    "users.read",
    "settings.write",
]);

// Tema do admin (passado para o contexto)
const adminTheme = ref({
    primaryColor: "#3B82F6",
    sidebarBg: "#1F2937",
    headerBg: "#FFFFFF",
});

// Itens do menu (passados para a sidebar)
const menuItems = ref([
    { path: "/admin", label: "Dashboard", icon: "🏠" },
    { path: "/admin/produtos", label: "Produtos", icon: "📦" },
    { path: "/admin/categorias", label: "Categorias", icon: "🗂️" },
    { path: "/admin/pedidos", label: "Pedidos", icon: "🧾" },
    { path: "/admin/metricas", label: "Métricas", icon: "📊" },
    { path: "/admin/configuracoes", label: "Configurações", icon: "⚙️" },
]);

// Notificações (passadas para o header)
const notifications = ref([
    { id: 1, message: "Novo pedido recebido", read: false },
    { id: 2, message: "Produto sem estoque", read: false },
]);

const router = useRouter();

// Handlers para eventos dos componentes filhos
const handleMenuClick = (path) => {
    console.log("Menu clicked:", path);
    router.push(path);
};

const handleLogout = () => {
    console.log("Logout requested");
    // Implementar logout
    localStorage.removeItem("auth_token");
    router.push("/login");
};

const handleNotificationRead = (notificationId) => {
    console.log("Notification read:", notificationId);
    const notification = notifications.value.find(
        (n) => n.id === notificationId,
    );
    if (notification) {
        notification.read = true;
    }
};

const handleThemeChange = (newTheme) => {
    console.log("Theme changed:", newTheme);
    adminTheme.value = { ...adminTheme.value, ...newTheme };
};

const handlePermissionUpdate = (newPermissions) => {
    console.log("Permissions updated:", newPermissions);
    userPermissions.value = newPermissions;
};

// Provide additional context that grandchildren can inject
provide("adminLayout", {
    refreshData: () => {
        console.log("Refreshing admin data...");
        // Implementar refresh de dados
    },
    showGlobalLoading: (show) => {
        console.log("Global loading:", show);
        // Implementar loading global
    },
});

onMounted(() => {
    console.log(
        "AdminLayout mounted - Comunicação vertical descendente estabelecida",
    );
});
</script>

<style scoped>
.admin-layout {
    display: flex;
    min-height: 100vh;
    background: #f9fafb;
}

.admin-sidebar {
    width: 250px;
    background: #1f2937;
    color: white;
}

.admin-main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.admin-content {
    flex: 1;
    padding: 2rem;
    overflow-y: auto;
}
</style>
