# Comunicação Vertical Descendente em Vue.js

Este documento explica como implementamos a comunicação vertical descendente no projeto Chinelos Karibe, seguindo os princípios do Vue.js para fluxo de dados unidirecional.

## Conceitos Fundamentais

### 1. Props (Pai → Filho)

Os componentes pais passam dados para os filhos através de **props**. Os filhos recebem esses dados como propriedades reativas.

### 2. Events (Filho → Pai)

Os componentes filhos emitem **eventos** para comunicar mudanças ou ações de volta aos pais.

### 3. Provide/Inject (Avô → Neto)

Para comunicação entre componentes não diretamente relacionados, usamos **provide/inject** onde um ancestral fornece dados/contextos que os descendentes podem injetar.

## Implementação no Projeto

### Estrutura dos Componentes Admin

```
AdminLayout (Avô)
├── AdminContextProvider (Provedor de Contexto)
│   ├── AdminSidebar (Pai/Filho)
│   └── AdminMain
│       ├── AdminHeader (Pai/Filho)
│       └── AdminContent
│           └── [Páginas Admin] (Netos)
```

### 1. Props: AdminDashboard → Componentes Filhos

O `AdminDashboard.vue` passa dados como props para seus componentes filhos:

```vue
<!-- AdminDashboard.vue -->
<StatCard
    title="Total de Vendas"
    :value="stats.totalSales"
    subtitle="Este mês"
    icon="💰"
    icon-bg-class="bg-green-500"
    :change="stats.salesChange"
    format="currency"
    @view-details="handleViewSalesDetails"
/>
```

O componente filho `StatCard.vue` recebe essas props:

```javascript
// StatCard.vue
const props = defineProps({
    title: { type: String, required: true },
    value: { type: [Number, String], required: true },
    subtitle: { type: String, default: "" },
    icon: { type: String, default: "📊" },
    // ... outras props
});
```

### 2. Events: Componentes Filhos → AdminDashboard

Os componentes filhos emitem eventos quando ações ocorrem:

```javascript
// StatCard.vue
const emit = defineEmits(["view-details"]);

const handleClick = () => {
    emit("view-details");
};
```

O pai escuta esses eventos:

```javascript
// AdminDashboard.vue
const handleViewSalesDetails = () => {
    console.log("Visualizando detalhes de vendas");
    router.push("/admin/metricas");
};
```

### 3. Provide/Inject: AdminLayout → Todos os Descendentes

O `AdminLayout.vue` fornece contexto através do `AdminContextProvider.vue`:

```javascript
// AdminLayout.vue
provide("adminContext", {
    user: props.user,
    permissions: props.permissions,
    theme: props.theme,
    hasPermission: (permission) => props.permissions.includes(permission),
    updateTheme: (newTheme) => emit("theme-change", newTheme),
    // ... outros métodos
});
```

Qualquer componente descendente pode injetar esse contexto:

```javascript
// AdminSidebar.vue (neto do AdminLayout)
const adminContext = inject("adminContext");

const user = computed(() => adminContext?.user || {});
const hasPermission = (permission) =>
    adminContext?.hasPermission(permission) || false;
```

## Benefícios desta Arquitetura

### 1. **Fluxo de Dados Unidirecional**

- Dados fluem de cima para baixo (props)
- Ações fluem de baixo para cima (events)
- Fácil de rastrear e debugar

### 2. **Reutilização de Componentes**

- Componentes filhos podem ser reutilizados em diferentes contextos
- Props permitem configuração flexível

### 3. **Separação de Responsabilidades**

- Cada componente tem responsabilidades claras
- Lógica de negócio fica no nível apropriado

### 4. **Manutenibilidade**

- Mudanças em um componente não afetam inesperadamente outros
- Fácil de testar componentes isoladamente

## Exemplos Práticos

### Comunicação Props + Events

```vue
<!-- Pai -->
<StatCard :value="salesData.total" @view-details="showSalesModal = true" />

<!-- Filho -->
<template>
    <div @click="$emit('view-details')">
        {{ formattedValue }}
    </div>
</template>
```

### Comunicação Provide/Inject

```javascript
// Provedor (avô)
provide("adminContext", {
    user: currentUser,
    permissions: userPermissions,
});

// Consumidor (neto)
const { user, permissions } = inject("adminContext");
```

## Boas Práticas

1. **Props são somente leitura** - Nunca modifique props diretamente nos filhos
2. **Use eventos para mudanças** - Emita eventos em vez de modificar dados do pai
3. **Valide props** - Use `defineProps` com validação de tipos
4. **Documente eventos** - Use `defineEmits` para declarar eventos emitidos
5. **Provide apenas o necessário** - Não forneça dados desnecessários no contexto
6. **Use computed para dados derivados** - Calcule valores baseados em props quando necessário

## Debugging

Para debugar problemas de comunicação:

1. Verifique se props estão sendo passadas corretamente
2. Confirme que eventos estão sendo emitidos
3. Use Vue DevTools para inspecionar a árvore de componentes
4. Adicione logs nos handlers de eventos
5. Verifique se o contexto está sendo fornecido/injetado corretamente

Esta arquitetura garante um código mais organizado, reutilizável e fácil de manter no projeto Chinelos Karibe.
