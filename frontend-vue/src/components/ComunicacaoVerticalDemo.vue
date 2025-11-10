<template>
    <div class="communication-demo">
        <h2>🎯 Demonstração: Comunicação Vertical Descendente</h2>

        <div class="demo-section">
            <h3>1. Props (Pai → Filho)</h3>
            <div class="demo-container">
                <div class="parent-component">
                    <h4>🅿️ Componente Pai (AdminDashboard)</h4>
                    <div class="props-data">
                        <p><strong>Dados enviados como props:</strong></p>
                        <ul>
                            <li>title: "{{ statCardData.title }}"</li>
                            <li>value: {{ statCardData.value }}</li>
                            <li>format: "{{ statCardData.format }}"</li>
                            <li>icon: "{{ statCardData.icon }}"</li>
                        </ul>
                    </div>
                </div>

                <div class="arrow">⬇️ Props</div>

                <div class="child-component">
                    <h4>🧒 Componente Filho (StatCard)</h4>
                    <StatCard
                        :title="statCardData.title"
                        :value="statCardData.value"
                        :format="statCardData.format"
                        :icon="statCardData.icon"
                        @view-details="handleChildEvent"
                    />
                </div>
            </div>
        </div>

        <div class="demo-section">
            <h3>2. Events (Filho → Pai)</h3>
            <div class="event-log">
                <h4>📝 Log de Eventos Recebidos:</h4>
                <div class="log-entries">
                    <div
                        v-for="(event, index) in eventLog"
                        :key="index"
                        class="log-entry"
                    >
                        <span class="timestamp">{{ event.timestamp }}</span>
                        <span class="event-name">{{ event.name }}</span>
                        <span class="event-data">{{ event.data }}</span>
                    </div>
                    <div v-if="eventLog.length === 0" class="no-events">
                        Nenhum evento recebido ainda. Clique no botão "Ver
                        detalhes" acima.
                    </div>
                </div>
            </div>
        </div>

        <div class="demo-section">
            <h3>3. Provide/Inject (Avô → Neto)</h3>
            <div class="demo-container">
                <div class="grandparent-component">
                    <h4>👴 Componente Avô (AdminLayout)</h4>
                    <div class="context-data">
                        <p><strong>Contexto fornecido via provide:</strong></p>
                        <ul>
                            <li>user: {{ providedContext.user.name }}</li>
                            <li>
                                permissions:
                                {{
                                    providedContext.permissions.length
                                }}
                                permissões
                            </li>
                            <li>
                                theme: {{ providedContext.theme.primaryColor }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="arrow">⬇️ Provide/Inject</div>

                <div class="grandchild-component">
                    <h4>👶 Componente Neto (AdminSidebar)</h4>
                    <AdminSidebarDemo
                        :menu-items="menuItems"
                        :active-route="activeRoute"
                    />
                </div>
            </div>
        </div>

        <div class="demo-controls">
            <button @click="changePropsData" class="demo-button">
                🔄 Alterar Props
            </button>
            <button @click="clearEventLog" class="demo-button">
                🗑️ Limpar Log
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import StatCard from "@/components/admin/StatCard.vue";
import AdminSidebarDemo from "@/components/admin/AdminSidebarDemo.vue";

// Dados reativos para demonstração
const statCardData = reactive({
    title: "Vendas do Mês",
    value: 15420.5,
    format: "currency",
    icon: "💰",
});

const eventLog = ref([]);

// Contexto fornecido (simulando provide/inject)
const providedContext = reactive({
    user: { name: "Admin User", role: "admin" },
    permissions: ["read", "write", "delete"],
    theme: { primaryColor: "#3B82F6" },
});

const menuItems = ref([
    { path: "/admin", label: "Dashboard", icon: "🏠" },
    { path: "/admin/produtos", label: "Produtos", icon: "📦" },
    { path: "/admin/pedidos", label: "Pedidos", icon: "🧾" },
]);

const activeRoute = ref("/admin");

// Handlers para eventos
const handleChildEvent = (data) => {
    eventLog.value.unshift({
        timestamp: new Date().toLocaleTimeString(),
        name: "view-details",
        data: data || "sem dados",
    });

    // Limitar log a 5 entradas
    if (eventLog.value.length > 5) {
        eventLog.value = eventLog.value.slice(0, 5);
    }
};

const changePropsData = () => {
    const options = [
        { title: "Produtos Ativos", value: 156, format: "number", icon: "📦" },
        {
            title: "Total de Vendas",
            value: 15420.5,
            format: "currency",
            icon: "💰",
        },
        {
            title: "Taxa de Conversão",
            value: 3.2,
            format: "percentage",
            icon: "📈",
        },
    ];

    const randomOption = options[Math.floor(Math.random() * options.length)];
    Object.assign(statCardData, randomOption);
};

const clearEventLog = () => {
    eventLog.value = [];
};
</script>

<style scoped>
.communication-demo {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    font-family: "Inter", sans-serif;
}

.demo-section {
    margin-bottom: 3rem;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: #f9fafb;
}

.demo-section h3 {
    color: #1f2937;
    margin-bottom: 1rem;
    font-size: 1.25rem;
}

.demo-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.parent-component,
.child-component,
.grandparent-component,
.grandchild-component {
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 1.5rem;
    min-width: 300px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.parent-component h4,
.child-component h4,
.grandparent-component h4,
.grandchild-component h4 {
    margin: 0 0 1rem 0;
    color: #374151;
    font-size: 1rem;
}

.props-data ul,
.context-data ul {
    background: #f3f4f6;
    padding: 1rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.props-data li,
.context-data li {
    margin-bottom: 0.25rem;
}

.arrow {
    font-size: 1.5rem;
    color: #6b7280;
    margin: 0.5rem 0;
}

.event-log {
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 1.5rem;
}

.log-entries {
    max-height: 200px;
    overflow-y: auto;
    background: #f9fafb;
    border-radius: 0.375rem;
    padding: 1rem;
}

.log-entry {
    display: flex;
    gap: 1rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.log-entry:last-child {
    border-bottom: none;
}

.timestamp {
    color: #6b7280;
    font-weight: 500;
}

.event-name {
    color: #3b82f6;
    font-weight: 600;
}

.event-data {
    color: #374151;
    font-style: italic;
}

.no-events {
    color: #6b7280;
    font-style: italic;
    text-align: center;
    padding: 2rem;
}

.demo-controls {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

.demo-button {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
}

.demo-button:hover {
    background: #2563eb;
}
</style>
