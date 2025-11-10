<template>
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-title">Admin Panel</h2>
            <div class="user-info">
                <img :src="user.avatar" :alt="user.name" class="user-avatar" />
                <span class="user-name">{{ user.name }}</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav-list">
                <li
                    v-for="item in visibleMenuItems"
                    :key="item.path"
                    class="nav-item"
                    :class="{ active: item.path === activeRoute }"
                >
                    <button
                        @click="$emit('menu-click', item.path)"
                        class="nav-button"
                        :disabled="!canAccess(item.requiredPermissions)"
                    >
                        <span class="nav-icon">{{ item.icon }}</span>
                        <span class="nav-label">{{ item.label }}</span>
                        <span
                            v-if="!canAccess(item.requiredPermissions)"
                            class="lock-icon"
                            >🔒</span
                        >
                    </button>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <button
                v-if="hasPermission('settings.write')"
                @click="toggleTheme"
                class="theme-toggle"
            >
                {{ isDarkTheme ? "☀️ Modo Claro" : "🌙 Modo Escuro" }}
            </button>
        </div>
    </div>
</template>

<script setup>
import {
    ref,
    computed,
    inject,
    defineProps,
    defineEmits,
    onMounted,
} from "vue";

// Props recebidas do componente pai (AdminLayout)
const props = defineProps({
    menuItems: {
        type: Array,
        default: () => [],
    },
    activeRoute: {
        type: String,
        default: "",
    },
});

// Emits para o componente pai
const emit = defineEmits(["menu-click"]);

// Inject do contexto fornecido pelo AdminContextProvider (avô)
const adminContext = inject("adminContext");

// Dados injetados do contexto
const user = computed(() => adminContext?.user || {});
const permissions = computed(() => adminContext?.permissions || []);
const theme = computed(() => adminContext?.theme || {});

// Métodos injetados do contexto
const hasPermission = (permission) =>
    adminContext?.hasPermission(permission) || false;
const canAccess = (requiredPermissions) =>
    adminContext?.canAccess(requiredPermissions) || false;
const updateTheme = (newTheme) => adminContext?.updateTheme(newTheme);

// Estado local
const isDarkTheme = ref(false);

// Computed para itens de menu visíveis baseado em permissões
const visibleMenuItems = computed(() => {
    return props.menuItems
        .map((item) => ({
            ...item,
            requiredPermissions: item.requiredPermissions || [],
        }))
        .filter((item) => {
            // Se não tem permissões requeridas, mostrar para todos
            if (
                !item.requiredPermissions ||
                item.requiredPermissions.length === 0
            ) {
                return true;
            }
            // Verificar se usuário tem todas as permissões necessárias
            return canAccess(item.requiredPermissions);
        });
});

// Método para alternar tema
const toggleTheme = () => {
    isDarkTheme.value = !isDarkTheme.value;
    const newTheme = {
        ...theme.value,
        sidebarBg: isDarkTheme.value ? "#111827" : "#1F2937",
        isDark: isDarkTheme.value,
    };
    updateTheme(newTheme);
};

onMounted(() => {
    console.log("AdminSidebar: Contexto injetado do avô:", adminContext);
    console.log("AdminSidebar: Usuário atual:", user.value);
    console.log("AdminSidebar: Permissões:", permissions.value);
});
</script>

<style scoped>
.admin-sidebar {
    height: 100vh;
    background: v-bind('theme.sidebarBg || "#1F2937"');
    color: white;
    padding: 1rem;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    margin-bottom: 2rem;
}

.sidebar-title {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
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
}

.sidebar-nav {
    flex: 1;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 0.25rem;
}

.nav-button {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    background: none;
    border: none;
    color: white;
    text-align: left;
    border-radius: 0.375rem;
    transition: background-color 0.2s;
    cursor: pointer;
}

.nav-button:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.1);
}

.nav-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.nav-item.active .nav-button {
    background: rgba(59, 130, 246, 0.2);
    border-left: 3px solid #3b82f6;
}

.nav-icon {
    font-size: 1.25rem;
}

.nav-label {
    flex: 1;
    font-size: 0.875rem;
}

.lock-icon {
    font-size: 0.75rem;
    opacity: 0.7;
}

.sidebar-footer {
    margin-top: auto;
    padding-top: 1rem;
}

.theme-toggle {
    width: 100%;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: white;
    border-radius: 0.375rem;
    cursor: pointer;
    font-size: 0.875rem;
    transition: background-color 0.2s;
}

.theme-toggle:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>
