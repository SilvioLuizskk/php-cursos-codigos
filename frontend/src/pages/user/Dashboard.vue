<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Minha Conta</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <router-link
        to="/minha-conta/pedidos"
        class="p-6 bg-white rounded-lg shadow hover:shadow-lg"
      >
        <h3 class="text-xl font-semibold mb-2">Meus Pedidos</h3>
        <p class="text-gray-600">Acompanhe seus pedidos</p>
      </router-link>
      <router-link
        to="/minha-conta/perfil"
        class="p-6 bg-white rounded-lg shadow hover:shadow-lg"
      >
        <h3 class="text-xl font-semibold mb-2">Perfil</h3>
        <p class="text-gray-600">Gerencie suas informações</p>
      </router-link>
      <router-link
        to="/minha-conta/favoritos"
        class="p-6 bg-white rounded-lg shadow hover:shadow-lg"
      >
        <h3 class="text-xl font-semibold mb-2">Favoritos</h3>
        <p class="text-gray-600">Seus produtos favoritos</p>
      </router-link>
    </div>
  </div>
</template>
