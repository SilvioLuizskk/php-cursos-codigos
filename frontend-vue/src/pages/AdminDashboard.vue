<template>
    <div class="p-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Dashboard Administrativo</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pedidos Hoje</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Receita Hoje</p>
                            <p class="text-2xl font-bold text-gray-900">R$ 0,00</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <i class="fas fa-box text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Produtos</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <i class="fas fa-users text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Clientes</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Ações Rápidas</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <router-link
                        to="/admin/produtos"
                        class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition"
                    >
                        <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                        <span class="font-medium">Adicionar Produto</span>
                    </router-link>

                    <router-link
                        to="/admin/categorias"
                        class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition"
                    >
                        <i class="fas fa-tags text-green-600 mr-3"></i>
                        <span class="font-medium">Gerenciar Categorias</span>
                    </router-link>

                    <router-link
                        to="/admin/pedidos"
                        class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition"
                    >
                        <i class="fas fa-list text-purple-600 mr-3"></i>
                        <span class="font-medium">Ver Pedidos</span>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
// Dashboard component - no additional logic needed
</script>

<style scoped>
/* Dashboard specific styles if needed */
</style>
