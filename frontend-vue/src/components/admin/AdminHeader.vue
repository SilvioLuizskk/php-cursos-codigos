<template>
    <header class="admin-header">
        <div class="header-left">
            <h1 class="page-title">{{ currentPageTitle }}</h1>
        </div>

        <div class="header-right">
            <!-- Notificações -->
            <div class="notifications-dropdown">
                <button
                    @click="toggleNotifications"
                    class="notification-button"
                    :class="{ 'has-unread': hasUnreadNotifications }"
                >
                    🔔
                    <span v-if="unreadCount > 0" class="notification-badge">{{
                        unreadCount
                    }}</span>
                </button>

                <div v-if="showNotifications" class="notifications-panel">
                    <div class="notifications-header">
                        <h3>Notificações</h3>
                        <button @click="markAllAsRead" class="mark-read-button">
                            Marcar todas como lidas
                        </button>
                    </div>

                    <div class="notifications-list">
                        <div
                            v-for="notification in notifications"
                            :key="notification.id"
                            class="notification-item"
                            :class="{ unread: !notification.read }"
                        >
                            <p>{{ notification.message }}</p>
                            <button
                                v-if="!notification.read"
                                @click="
                                    $emit('notification-read', notification.id)
                                "
                                class="mark-read-icon"
                            >
                                ✓
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do usuário -->
            <div class="user-menu">
                <button @click="toggleUserMenu" class="user-button">
                    <img
                        :src="user.avatar"
                        :alt="user.name"
                        class="user-avatar"
                    />
                    <span class="user-name">{{ user.name }}</span>
                    <span class="dropdown-arrow">▼</span>
                </button>

                <div v-if="showUserMenu" class="user-menu-panel">
                    <div class="user-info">
                        <img
                            :src="user.avatar"
                            :alt="user.name"
                            class="user-avatar-large"
                        />
                        <div>
                            <div class="user-name-large">{{ user.name }}</div>
                            <div class="user-email">{{ user.email }}</div>
                            <div class="user-role">{{ user.role }}</div>
                        </div>
                    </div>

                    <div class="menu-divider"></div>

                    <button
                        v-if="hasPermission('settings.write')"
                        @click="openSettings"
                        class="menu-item"
                    >
                        ⚙️ Configurações
                    </button>

                    <button @click="$emit('logout')" class="menu-item logout">
                        🚪 Sair
                    </button>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed, inject, defineProps, defineEmits } from "vue";
import { useRoute } from "vue-router";

// Props recebidas do componente pai (AdminLayout)
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    notifications: {
        type: Array,
        default: () => [],
    },
});

// Emits para o componente pai
const emit = defineEmits(["logout", "notification-read"]);

// Inject do contexto fornecido pelo AdminContextProvider (avô)
const adminContext = inject("adminContext");

// Dados injetados do contexto
const permissions = computed(() => adminContext?.permissions || []);

// Métodos injetados do contexto
const hasPermission = (permission) =>
    adminContext?.hasPermission(permission) || false;

// Estado local
const showNotifications = ref(false);
const showUserMenu = ref(false);
const route = useRoute();

// Computed properties
const unreadCount = computed(() => {
    return props.notifications.filter((n) => !n.read).length;
});

const hasUnreadNotifications = computed(() => unreadCount.value > 0);

const currentPageTitle = computed(() => {
    const routeTitles = {
        "/admin": "Dashboard",
        "/admin/produtos": "Produtos",
        "/admin/categorias": "Categorias",
        "/admin/pedidos": "Pedidos",
        "/admin/metricas": "Métricas",
        "/admin/configuracoes": "Configurações",
        "/admin/banners": "Banners",
        "/admin/paginas": "Páginas",
    };
    return routeTitles[route.path] || "Admin";
});

// Métodos
const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value;
    showUserMenu.value = false;
};

const toggleUserMenu = () => {
    showUserMenu.value = !showUserMenu.value;
    showNotifications.value = false;
};

const markAllAsRead = () => {
    props.notifications.forEach((notification) => {
        if (!notification.read) {
            emit("notification-read", notification.id);
        }
    });
};

const openSettings = () => {
    console.log("Abrindo configurações");
    // Implementar navegação para configurações
};
</script>

<style scoped>
.admin-header {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.header-left {
    flex: 1;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.notification-button {
    position: relative;
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition: background-color 0.2s;
}

.notification-button:hover {
    background: #f3f4f6;
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: #ef4444;
    color: white;
    font-size: 0.75rem;
    padding: 0.125rem 0.375rem;
    border-radius: 9999px;
    min-width: 1.25rem;
    text-align: center;
}

.notifications-panel {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    width: 320px;
    z-index: 50;
    margin-top: 0.5rem;
}

.notifications-header {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notifications-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}

.mark-read-button {
    background: none;
    border: none;
    color: #3b82f6;
    font-size: 0.875rem;
    cursor: pointer;
}

.notifications-list {
    max-height: 300px;
    overflow-y: auto;
}

.notification-item {
    padding: 1rem;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-item.unread {
    background: #fef3c7;
}

.notification-item p {
    margin: 0;
    font-size: 0.875rem;
    color: #374151;
}

.mark-read-icon {
    background: none;
    border: none;
    color: #10b981;
    cursor: pointer;
    font-size: 1rem;
}

.user-menu {
    position: relative;
}

.user-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition: background-color 0.2s;
}

.user-button:hover {
    background: #f3f4f6;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.user-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.dropdown-arrow {
    font-size: 0.75rem;
    color: #6b7280;
}

.user-menu-panel {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    width: 280px;
    z-index: 50;
    margin-top: 0.5rem;
}

.user-info {
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar-large {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}

.user-name-large {
    font-weight: 600;
    color: #111827;
}

.user-email {
    font-size: 0.875rem;
    color: #6b7280;
}

.user-role {
    font-size: 0.75rem;
    color: #3b82f6;
    background: #ebf4ff;
    padding: 0.125rem 0.375rem;
    border-radius: 0.25rem;
    display: inline-block;
    margin-top: 0.25rem;
}

.menu-divider {
    height: 1px;
    background: #e5e7eb;
}

.menu-item {
    width: 100%;
    padding: 0.75rem 1rem;
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
    font-size: 0.875rem;
    color: #374151;
    transition: background-color 0.2s;
}

.menu-item:hover {
    background: #f9fafb;
}

.menu-item.logout {
    color: #dc2626;
}

.menu-item.logout:hover {
    background: #fef2f2;
}
</style>
