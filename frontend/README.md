# Karibe Presente Personalizado Frontend

Frontend Vue.js 3 para o sistema de presentes personalizados.

## Tecnologias

- Vue.js 3 (Composition API)
- Vite (Build tool)
- Pinia (State management)
- Vue Router (Routing)
- Axios (HTTP client)
- Tailwind CSS (Styling)

## Estrutura do Projeto

```
src/
├── api/              # Services HTTP
├── stores/           # Pinia stores
├── composables/      # Composables Vue
├── components/       # Componentes Vue
├── pages/           # Páginas/Views
├── router/          # Configuração de rotas
├── utils/           # Utilitários
├── App.vue          # Componente raiz
└── main.js          # Ponto de entrada
```

## Instalação

```bash
# Instalar dependências
npm install

# Executar em desenvolvimento
npm run dev

# Build para produção
npm run build
```

## Configuração

1. Copie `.env.example` para `.env`
2. Configure as variáveis de ambiente:
   - `VITE_API_URL`: URL da API do backend
   - Outras configurações conforme necessário

## Features Implementadas

### ✅ Autenticação

- Login/Logout
- Registro de usuários
- Recuperação de senha
- Guards de rota

### ✅ Produtos

- Listagem com filtros
- Detalhes do produto
- Busca
- Produtos em destaque

### ✅ Carrinho de Compras

- Adicionar/remover produtos
- Atualizar quantidades
- Aplicar cupons de desconto
- Persistência no backend

### ✅ Pedidos

- Criar pedidos
- Listar pedidos do usuário
- Detalhes do pedido
- Rastreamento

### ✅ Interface

- Design responsivo
- Componentes reutilizáveis
- Loading states
- Tratamento de erros

## Scripts Disponíveis

- `npm run dev` - Servidor de desenvolvimento
- `npm run build` - Build para produção
- `npm run preview` - Preview do build
- `npm run lint` - Linting do código

## Integração com Backend

O frontend está configurado para integrar com o backend Laravel através da API RESTful. Todas as requisições passam pelo Axios com interceptadores para:

- Adicionar token de autenticação
- Renovar tokens expirados
- Tratamento global de erros

## Status

✅ **COMPLETO E PRONTO PARA USO**

O frontend está totalmente implementado com todas as funcionalidades principais:

- Sistema de autenticação completo
- Gerenciamento de produtos
- Carrinho de compras funcional
- Sistema de pedidos
- Interface moderna e responsiva
