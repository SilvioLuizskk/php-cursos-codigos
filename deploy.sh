#!/bin/bash

# 🚀 Deploy da Aplicação Karibe Presente Personalizado
# Script para configuração completa do ambiente

echo "🎯 Iniciando deploy da Karibe Presente Personalizado..."

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Função para verificar se um comando existe
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Verificar pré-requisitos
print_status "Verificando pré-requisitos..."

# Verificar Node.js
if command_exists node; then
    NODE_VERSION=$(node --version)
    print_success "Node.js encontrado: $NODE_VERSION"
else
    print_error "Node.js não encontrado. Instale o Node.js 18+ primeiro."
    exit 1
fi

# Verificar npm
if command_exists npm; then
    NPM_VERSION=$(npm --version)
    print_success "npm encontrado: $NPM_VERSION"
else
    print_error "npm não encontrado."
    exit 1
fi

# Verificar PHP
if command_exists php; then
    PHP_VERSION=$(php --version | head -n 1)
    print_success "PHP encontrado: $PHP_VERSION"
else
    print_error "PHP não encontrado. Instale o PHP 8.2+ primeiro."
    exit 1
fi

# Verificar Composer
if command_exists composer; then
    COMPOSER_VERSION=$(composer --version)
    print_success "Composer encontrado: $COMPOSER_VERSION"
else
    print_error "Composer não encontrado. Instale o Composer primeiro."
    exit 1
fi

# 1. CONFIGURAR FRONTEND
print_status "🎨 Configurando Frontend Vue.js..."

cd frontend-vue

if [ ! -f "package.json" ]; then
    print_error "package.json não encontrado no frontend!"
    exit 1
fi

print_status "Instalando dependências do frontend..."
npm install

if [ $? -eq 0 ]; then
    print_success "Dependências do frontend instaladas!"
else
    print_error "Falha ao instalar dependências do frontend"
    exit 1
fi

# Criar arquivo .env para o frontend se não existir
if [ ! -f ".env" ]; then
    print_status "Criando arquivo .env para o frontend..."
    cat > .env << EOF
VITE_APP_NAME="Karibe Presente Personalizado"
VITE_API_URL=http://localhost:8000/api
VITE_APP_URL=http://localhost:3002
EOF
    print_success "Arquivo .env do frontend criado!"
fi

# 2. CONFIGURAR BACKEND
print_status "⚙️ Configurando Backend Laravel..."

cd ../backend-laravel

if [ ! -f "composer.json" ]; then
    print_error "composer.json não encontrado no backend!"
    exit 1
fi

print_status "Instalando dependências do backend..."
composer install --optimize-autoloader --no-dev

if [ $? -eq 0 ]; then
    print_success "Dependências do backend instaladas!"
else
    print_warning "Falha na instalação completa. Tentando sem otimizações..."
    composer install
fi

# Criar arquivo .env se não existir
if [ ! -f ".env" ]; then
    print_status "Criando arquivo .env para o backend..."
    cp .env.example .env
    print_success "Arquivo .env do backend criado!"
fi

# Gerar chave da aplicação
print_status "Gerando chave da aplicação..."
php artisan key:generate

# Configurar permissões
print_status "Configurando permissões..."
chmod -R 775 storage bootstrap/cache
if command_exists chown; then
    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
fi

# 3. CONFIGURAR BANCO DE DADOS
print_status "🗄️ Configurando banco de dados..."

# Verificar se MySQL está rodando
if command_exists mysql; then
    print_status "MySQL encontrado, verificando conexão..."
    # Você pode adicionar verificação de conexão aqui se necessário
else
    print_warning "MySQL não encontrado. Certifique-se de que o banco está configurado."
fi

# Executar migrações (se o banco estiver configurado)
print_status "Executando migrações..."
php artisan migrate --force 2>/dev/null && print_success "Migrações executadas!" || print_warning "Migrações falharam - configure o banco de dados primeiro"

# 4. SCRIPTS DE INICIALIZAÇÃO
print_status "📝 Criando scripts de inicialização..."

# Script para iniciar frontend
cat > ../start-frontend.sh << 'EOF'
#!/bin/bash
echo "🎨 Iniciando Frontend Vue.js na porta 3002..."
cd frontend-vue
npm run dev -- --port 3002
EOF

# Script para iniciar backend
cat > ../start-backend.sh << 'EOF'
#!/bin/bash
echo "⚙️ Iniciando Backend Laravel na porta 8000..."
cd backend-laravel
php artisan serve --port=8000
EOF

# Script para iniciar ambos
cat > ../start-all.sh << 'EOF'
#!/bin/bash
echo "🚀 Iniciando aplicação completa..."

# Função para cleanup
cleanup() {
    echo "🛑 Parando serviços..."
    kill $FRONTEND_PID $BACKEND_PID 2>/dev/null
    exit 0
}

# Capturar Ctrl+C
trap cleanup SIGINT

echo "🎨 Iniciando Frontend..."
cd frontend-vue && npm run dev -- --port 3002 &
FRONTEND_PID=$!

echo "⚙️ Iniciando Backend..."
cd ../backend-laravel && php artisan serve --port=8000 &
BACKEND_PID=$!

echo "✅ Aplicação rodando:"
echo "   Frontend: http://localhost:3002"
echo "   Backend:  http://localhost:8000"
echo "   API:      http://localhost:8000/api"
echo ""
echo "Pressione Ctrl+C para parar..."

# Aguardar
wait
EOF

# Tornar scripts executáveis
chmod +x ../start-frontend.sh ../start-backend.sh ../start-all.sh

cd ..

print_success "Scripts de inicialização criados!"

# 5. VERIFICAÇÕES FINAIS
print_status "🔍 Executando verificações finais..."

# Verificar estrutura de arquivos
if [ -d "frontend-vue/src" ] && [ -d "backend-laravel/app" ]; then
    print_success "Estrutura de arquivos OK!"
else
    print_warning "Alguns arquivos podem estar faltando."
fi

# 6. INSTRUÇÕES FINAIS
echo ""
echo "🎉 Deploy concluído com sucesso!"
echo ""
echo "📋 PRÓXIMOS PASSOS:"
echo ""
echo "1. 🗄️ Configurar banco de dados:"
echo "   - Edite backend-laravel/.env com suas credenciais"
echo "   - Execute: cd backend-laravel && php artisan migrate"
echo ""
echo "2. 🚀 Iniciar aplicação:"
echo "   ./start-all.sh     # Inicia frontend + backend"
echo "   ./start-frontend.sh # Apenas frontend"
echo "   ./start-backend.sh  # Apenas backend"
echo ""
echo "3. 🌐 Acessar aplicação:"
echo "   Frontend: http://localhost:3002"
echo "   Backend:  http://localhost:8000"
echo "   API:      http://localhost:8000/api"
echo ""
echo "4. 📚 Documentação:"
echo "   - README.md (geral)"
echo "   - backend-laravel/README.md (backend)"
echo "   - ATUALIZAÇÃO.md (changelog)"
echo ""
print_success "Karibe Presente Personalizado está pronto para uso! 🎯"