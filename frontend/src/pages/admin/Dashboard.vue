<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Dashboard Admin</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <router-link to="/admin/produtos" class="p-6 bg-white rounded-lg shadow hover:shadow-lg">
        <h3 class="text-xl font-semibold mb-2">Produtos</h3>
        <p class="text-gray-600">Gerenciar produtos</p>
      </router-link>
      <router-link to="/admin/pedidos" class="p-6 bg-white rounded-lg shadow hover:shadow-lg">
        <h3 class="text-xl font-semibold mb-2">Pedidos</h3>
        <p class="text-gray-600">Gerenciar pedidos</p>
      </router-link>
      <div class="p-6 bg-white rounded-lg shadow">
        <h3 class="text-xl font-semibold mb-2">Relatórios</h3>
        <p class="text-gray-600">Em desenvolvimento</p>
      </div>
    </div>
  </div>
</template>