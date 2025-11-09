# 🎯 Karibe Presente Personalizado

> Sistema completo de e-commerce para personalização de produtos

## 🌟 Sobre o Projeto

**Karibe Presente Personalizado** é uma plataforma moderna de e-commerce focada em produtos personalizados, desenvolvida com as melhores tecnologias do mercado.

### ✨ Características Principais

- 🎨 **Interface Moderna**: Design responsivo com Vue.js 3 e Tailwind CSS
- ⚡ **Performance Otimizada**: Frontend SPA com carregamento rápido
- 🔐 **Segurança Robusta**: Autenticação JWT com Laravel Sanctum
- 📱 **Mobile First**: Totalmente responsivo para todos os dispositivos
- 🛒 **E-commerce Completo**: Carrinho, checkout, pagamentos e pedidos
- 👑 **Painel Admin**: Gestão completa de produtos, usuários e pedidos

## 🛠️ Stack Tecnológica

### Frontend
- **Vue.js 3** - Framework JavaScript progressivo
- **Vite** - Build tool ultrarrápida
- **Tailwind CSS** - Framework CSS utility-first
- **Vue Router 4** - Roteamento SPA
- **Pinia** - Gerenciamento de estado
- **Axios** - Cliente HTTP

### Backend
- **Laravel 10+** - Framework PHP moderno
- **Laravel Sanctum** - Autenticação API
- **MySQL** - Banco de dados relacional
- **Eloquent ORM** - Mapeamento objeto-relacional
- **Laravel Validation** - Validação robusta
- **JWT Authentication** - Tokens seguros

### DevOps
- **Docker** - Containerização
- **Composer** - Gerenciador de dependências PHP
- **npm** - Gerenciador de pacotes Node.js
- **Git** - Controle de versão

## 🚀 Instalação Rápida

### Pré-requisitos

- **PHP 8.2+**
- **Node.js 18+**
- **Composer**
- **MySQL 8+**
- **Git**

### 📦 Deploy Automatizado

```bash
# Clone o repositório
git clone <repository-url>
cd Chinelos-karibe-na\ 2

# Execute o script de deploy
./deploy.sh
```

O script automatizado irá:
- ✅ Verificar pré-requisitos
- ✅ Instalar dependências do frontend e backend
- ✅ Configurar arquivos de ambiente
- ✅ Criar scripts de inicialização
- ✅ Configurar permissões

### 🔧 Instalação Manual

#### Frontend (Vue.js)
```bash
cd frontend-vue
npm install
cp .env.example .env
npm run dev
```

#### Backend (Laravel)
```bash
cd backend-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## 🎮 Como Usar

### Iniciar Aplicação Completa
```bash
./start-all.sh
```

### Iniciar Apenas Frontend
```bash
./start-frontend.sh
```

### Iniciar Apenas Backend
```bash
./start-backend.sh
```

### URLs de Acesso
- **Frontend**: http://localhost:3002
- **Backend**: http://localhost:8000
- **API**: http://localhost:8000/api

## 📋 Funcionalidades

### 🛍️ Para Clientes
- [x] Cadastro e login de usuários
- [x] Catálogo de produtos com filtros
- [x] Carrinho de compras dinâmico
- [x] Processo de checkout seguro
- [x] Histórico de pedidos
- [x] Sistema de avaliações
- [x] Lista de desejos
- [x] Perfil de usuário

### 👑 Para Administradores
- [x] Dashboard administrativo
- [x] Gestão de produtos (CRUD)
- [x] Gestão de usuários
- [x] Gestão de pedidos
- [x] Sistema de cupons
- [x] Relatórios e analytics
- [x] Configurações da loja

### 🔌 API REST
- [x] Endpoints RESTful padronizados
- [x] Autenticação por tokens
- [x] Documentação Swagger
- [x] Rate limiting
- [x] Versionamento de API
- [x] Responses padronizadas

## 📁 Estrutura do Projeto

```
Chinelos-karibe-na 2/
├── 📄 README.md              # Este arquivo
├── 📄 deploy.sh              # Script de deploy automatizado
├── 📄 docker-compose.yml     # Configuração Docker
├── 
├── 🎨 frontend-vue/          # Aplicação Vue.js
│   ├── src/                  # Código fonte
│   ├── public/               # Arquivos públicos
│   ├── package.json          # Dependências Node.js
│   └── vite.config.js        # Configuração Vite
├── 
├── ⚙️ backend-laravel/       # API Laravel
│   ├── app/                  # Código da aplicação
│   ├── database/             # Migrações e seeds
│   ├── routes/               # Definição de rotas
│   ├── composer.json         # Dependências PHP
│   └── README.md             # Documentação específica
└── 
└── 🗄️ database/             # Scripts de banco
    └── init.sql              # Estrutura inicial
```

## 🔐 Configuração de Ambiente

### Frontend (.env)
```env
VITE_APP_NAME="Karibe Presente Personalizado"
VITE_API_URL=http://localhost:8000/api
VITE_APP_URL=http://localhost:3002
```

### Backend (.env)
```env
APP_NAME="Karibe Presente Personalizado"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=karibe_personalizado
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:3002
```

## 🧪 Testes

### Frontend
```bash
cd frontend-vue
npm run test        # Testes unitários
npm run test:e2e    # Testes E2E
```

### Backend
```bash
cd backend-laravel
php artisan test    # PHPUnit
```

## 📚 Documentação

- 📖 [Documentação do Backend](backend-laravel/README.md)
- 🔄 [Changelog](ATUALIZAÇÃO.md)
- 🚀 [Guia de Deploy](deploy.sh)
- 📊 [Monitoramento](MONITORAMENTO.md)
- ⚡ [Performance](PERFORMANCE.md)

## 🤝 Contribuição

1. Fork o projeto
2. Crie sua feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📈 Roadmap

### v2.0.0 (Em Desenvolvimento)
- [ ] Sistema de multi-tenant
- [ ] Chat em tempo real
- [ ] Notificações push
- [ ] App mobile React Native
- [ ] Integração com redes sociais

### v1.1.0 (Próxima Release)
- [ ] Sistema de afiliados
- [ ] Programa de pontos
- [ ] Checkout em múltiplas etapas
- [ ] Integração com correios

## 📞 Suporte

- 📧 **Email**: suporte@karibepersonalizado.com
- 📱 **WhatsApp**: +55 (11) 99999-9999
- 🌐 **Site**: www.karibepersonalizado.com
- 📚 **Documentação**: docs.karibepersonalizado.com

## 📜 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 🙏 Agradecimentos

- Time de desenvolvimento
- Comunidade Laravel
- Comunidade Vue.js
- Todos os contribuidores

---

<div align="center">

**🎯 Karibe Presente Personalizado**

*Desenvolvido com ❤️ no Brasil*

[![Laravel](https://img.shields.io/badge/Laravel-10+-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3+-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

</div>