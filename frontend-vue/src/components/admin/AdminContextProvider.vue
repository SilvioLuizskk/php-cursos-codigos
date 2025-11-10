<template>
    <div class="admin-context-provider">
        <!-- Slot para renderizar os componentes filhos -->
        <slot />
    </div>
</template>

<script setup>
import { provide, watch, defineProps, defineEmits } from "vue";

// Props recebidas do componente pai (AdminLayout)
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    permissions: {
        type: Array,
        default: () => [],
    },
    theme: {
        type: Object,
        default: () => ({}),
    },
});

// Emits para comunicar mudanças de volta ao pai
const emit = defineEmits(["theme-change", "permission-update"]);

// Provide context para os componentes filhos (e netos)
provide("adminContext", {
    // Dados do usuário
    user: props.user,

    // Permissões
    permissions: props.permissions,

    // Tema
    theme: props.theme,

    // Métodos utilitários
    hasPermission: (permission) => {
        return props.permissions.includes(permission);
    },

    updateTheme: (newTheme) => {
        emit("theme-change", newTheme);
    },

    updatePermissions: (newPermissions) => {
        emit("permission-update", newPermissions);
    },

    // Método para verificar se usuário pode acessar uma rota
    canAccess: (requiredPermissions) => {
        if (!Array.isArray(requiredPermissions)) {
            requiredPermissions = [requiredPermissions];
        }
        return requiredPermissions.every((perm) =>
            props.permissions.includes(perm),
        );
    },
});

// Watch para mudanças nas props e emitir eventos
watch(
    () => props.theme,
    (newTheme) => {
        console.log("AdminContext: Tema atualizado", newTheme);
    },
    { deep: true },
);

watch(
    () => props.permissions,
    (newPermissions) => {
        console.log("AdminContext: Permissões atualizadas", newPermissions);
    },
    { deep: true },
);
</script>
