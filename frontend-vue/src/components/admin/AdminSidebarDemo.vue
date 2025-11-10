<template>
    <div class="admin-sidebar-demo">
        <div class="sidebar-header">
            <h4>📱 Admin Sidebar (Neto)</h4>
            <p class="demo-info">
                Este componente injeta o contexto do AdminLayout (avô) via
                provide/inject
            </p>
        </div>

        <div class="injected-data">
            <h5>🔗 Dados Injetados do Contexto:</h5>
            <ul>
                <li>
                    <strong>Usuário:</strong> {{ injectedUser?.name || "N/A" }}
                </li>
                <li>
                    <strong>Permissões:</strong>
                    {{ injectedPermissions?.length || 0 }} items
                </li>
                <li>
                    <strong>Tema:</strong>
                    {{ injectedTheme?.primaryColor || "N/A" }}
                </li>
            </ul>
        </div>

        <div class="permissions-check">
            <h5>🔐 Verificação de Permissões:</h5>
            <div class="permission-item">
                <span>📖 Pode ler produtos:</span>
                <span :class="hasPermission('products.read') ? 'yes' : 'no'">
                    {{ hasPermission("products.read") ? "✅ Sim" : "❌ Não" }}
                </span>
            </div>
            <div class="permission-item">
                <span>✏️ Pode editar produtos:</span>
                <span :class="hasPermission('products.write') ? 'yes' : 'no'">
                    {{ hasPermission("products.write") ? "✅ Sim" : "❌ Não" }}
                </span>
            </div>
            <div class="permission-item">
                <span>🗑️ Pode excluir produtos:</span>
                <span :class="hasPermission('products.delete') ? 'yes' : 'no'">
                    {{ hasPermission("products.delete") ? "✅ Sim" : "❌ Não" }}
                </span>
            </div>
        </div>

        <nav class="demo-nav">
            <h5>🧭 Menu Items (recebidos via props):</h5>
            <ul>
                <li
                    v-for="item in menuItems"
                    :key="item.path"
                    :class="{ active: item.path === activeRoute }"
                    class="nav-item"
                >
                    <span class="nav-icon">{{ item.icon }}</span>
                    <span class="nav-label">{{ item.label }}</span>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script setup>
import { computed, inject } from "vue";

// Props recebidas do componente pai (ComunicacaoVerticalDemo)
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

// Inject do contexto fornecido pelo AdminContextProvider (simulando avô)
const adminContext = inject("adminContext");

// Dados injetados do contexto
const injectedUser = computed(() => adminContext?.user || null);
const injectedPermissions = computed(() => adminContext?.permissions || []);
const injectedTheme = computed(() => adminContext?.theme || {});

// Métodos injetados do contexto
const hasPermission = (permission) => {
    return injectedPermissions.value.includes(permission);
};
</script>

<style scoped>
.admin-sidebar-demo {
    background: #1f2937;
    color: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    min-width: 300px;
    font-size: 0.875rem;
}

.sidebar-header h4 {
    margin: 0 0 0.5rem 0;
    color: #f3f4f6;
}

.demo-info {
    color: #9ca3af;
    font-size: 0.8rem;
    margin-bottom: 1rem;
    font-style: italic;
}

.injected-data h5,
.permissions-check h5,
.demo-nav h5 {
    color: #f3f4f6;
    margin: 1rem 0 0.5rem 0;
    font-size: 0.9rem;
}

.injected-data ul {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.75rem;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.injected-data li {
    margin-bottom: 0.25rem;
    color: #e5e7eb;
}

.permissions-check {
    margin-bottom: 1rem;
}

.permission-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.permission-item:last-child {
    border-bottom: none;
}

.yes {
    color: #10b981;
    font-weight: 600;
}

.no {
    color: #ef4444;
    font-weight: 600;
}

.demo-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    border-radius: 0.375rem;
    transition: background-color 0.2s;
}

.nav-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

.nav-item.active {
    background: rgba(59, 130, 246, 0.2);
    border-left: 3px solid #3b82f6;
}

.nav-icon {
    font-size: 1rem;
}

.nav-label {
    color: #e5e7eb;
    font-weight: 500;
}
</style>
