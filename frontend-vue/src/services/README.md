# Services

Esta pasta contém todos os serviços da aplicação, organizados por funcionalidade. Cada serviço é responsável por fazer chamadas para a API do backend Laravel.

## Estrutura dos Services

### `api.js`

Cliente HTTP configurado com Axios, incluindo interceptors para autenticação e tratamento de erros.

### `authService.js`

Serviços de autenticação:

- Login/Logout
- Registro de usuários
- Recuperação de senha
- Renovação de tokens

### `productService.js`

Serviços relacionados a produtos:

- Listar produtos
- Detalhes de produto
- CRUD de produtos (admin)

### `cartService.js`

Serviços do carrinho de compras:

- Adicionar/remover itens
- Atualizar quantidades
- Calcular totais

### `orderService.js`

Serviços de pedidos:

- Listar pedidos do usuário
- Detalhes de pedido
- Criar pedido
- Cancelar pedido

### `userService.js`

Serviços do usuário:

- Perfil do usuário
- Endereços
- Lista de desejos
- Notificações
- Configurações

### `pageService.js`

Serviços de páginas estáticas:

- Página inicial
- Contato
- Sobre
- FAQ
- Políticas

### `adminService.js`

Serviços administrativos:

- Dashboard
- Gerenciamento de produtos
- Gerenciamento de categorias
- Gerenciamento de banners
- Gerenciamento de páginas
- Gerenciamento de pedidos
- Gerenciamento de usuários
- Configurações do sistema
- Métricas e relatórios

### `checkoutService.js`

Serviços de checkout:

- Iniciar checkout
- Calcular frete
- Processar pagamento
- Validar cupons
- Finalizar pedido

### `abTestService.js`

Serviços de A/B Testing:

- Dashboard A/B
- Gerenciamento de testes
- Resultados e estatísticas
- Relatórios de performance

### `reviewService.js`

Serviços de avaliações:

- Listar avaliações
- Criar avaliação
- Moderar avaliações

### `monitoringService.js`

Serviços de monitoramento:

- Health check
- Métricas de performance
- Logs de erro

## Como usar

```javascript
import { authService, productService, cartService } from "@/services";

// Exemplo de uso
const login = async (credentials) => {
    try {
        const response = await authService.login(credentials);
        return response;
    } catch (error) {
        console.error("Erro no login:", error);
    }
};

const getProducts = async () => {
    try {
        const products = await productService.getProducts();
        return products;
    } catch (error) {
        console.error("Erro ao buscar produtos:", error);
    }
};
```

## Tratamento de Erros

Todos os serviços incluem tratamento automático de:

- Erros de autenticação (401)
- Erros de permissão (403)
- Erros de servidor (500)
- Timeouts de requisição

## Autenticação

Os serviços automaticamente incluem o token Bearer nas requisições quando o usuário está autenticado. O token é obtido do localStorage.

## CORS e CSRF

O cliente API está configurado para:

- Enviar cookies para o backend (withCredentials: true)
- Obter automaticamente tokens CSRF quando necessário
- Lidar com preflight requests CORS
