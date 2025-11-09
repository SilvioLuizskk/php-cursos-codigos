<template>
    <div
        class="trust-badges flex flex-wrap gap-4 my-8 justify-center md:justify-start"
    >
        <div
            class="badge flex items-center gap-2 bg-green-50 border border-green-200 rounded px-4 py-2 shadow-sm"
        >
            <i class="fas fa-lock text-green-600 text-xl"></i>
            <span class="font-semibold text-green-700">Compra 100% Segura</span>
        </div>
        <div
            class="badge flex items-center gap-2 bg-blue-50 border border-blue-200 rounded px-4 py-2 shadow-sm"
        >
            <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
            <span class="font-semibold text-blue-700">Site Blindado</span>
        </div>
        <div
            class="badge flex items-center gap-2 bg-yellow-50 border border-yellow-200 rounded px-4 py-2 shadow-sm"
        >
            <i class="fas fa-undo text-yellow-600 text-xl"></i>
            <span class="font-semibold text-yellow-700">Troca Garantida</span>
        </div>
        <div
            class="badge flex items-center gap-2 bg-purple-50 border border-purple-200 rounded px-4 py-2 shadow-sm"
        >
            <i class="fas fa-headset text-purple-600 text-xl"></i>
            <span class="font-semibold text-purple-700">Suporte Rápido</span>
        </div>
        <div
            class="badge flex items-center gap-2 bg-pink-50 border border-pink-200 rounded px-4 py-2 shadow-sm"
        >
            <i class="fas fa-award text-pink-600 text-xl"></i>
            <span class="font-semibold text-pink-700">Produtos Originais</span>
        </div>
    </div>
</template>

<script setup>
// Nenhuma lógica JS necessária para badges estáticos
</script>

<style scoped>
.trust-badges {
    flex-wrap: wrap;
    gap: 1rem;
    margin: 2rem 0;
}
.badge {
    min-width: 180px;
    justify-content: flex-start;
}
</style>
