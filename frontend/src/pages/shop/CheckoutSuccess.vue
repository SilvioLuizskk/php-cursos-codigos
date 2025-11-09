<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-green-600">
      Compra Realizada com Sucesso!
    </h1>
    <div class="text-center py-12">
      <p class="text-gray-600 mb-4">Seu pedido foi processado com sucesso.</p>
      <router-link
        to="/minha-conta/pedidos"
        class="text-blue-600 hover:underline"
      >
        Ver meus pedidos
      </router-link>
    </div>
  </div>
</template>
