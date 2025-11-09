#!/bin/bash

# 🧪 Script de Teste Rápido - Karibe Presente Personalizado
# Testa se a integração Frontend-Backend está funcionando

echo "🧪 Testando Integração Frontend-Backend - Karibe Presente Personalizado"
echo "======================================================================="

# Cores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[✅ SUCCESS]${NC} $1"
}

print_error() {
    echo -e "${RED}[❌ ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[⚠️ WARNING]${NC} $1"
}

# Função para verificar se um serviço está rodando
check_service() {
    local url=$1
    local name=$2
    
    if curl -s --connect-timeout 5 "$url" > /dev/null 2>&1; then
        print_success "$name está rodando em $url"
        return 0
    else
        print_error "$name não está acessível em $url"
        return 1
    fi
}

# Testar Backend Laravel
print_status "Testando Backend Laravel..."
if check_service "http://localhost:8000" "Backend Laravel"; then
    # Testar API específica
    print_status "Testando endpoints da API..."
    
    # Testar rota de produtos
    if curl -s "http://localhost:8000/api/products" | grep -q "data\|products\|error"; then
        print_success "API de produtos respondendo"
    else
        print_warning "API de produtos pode não estar funcionando corretamente"
    fi
    
    # Testar rota de autenticação
    if curl -s -X POST "http://localhost:8000/api/auth/register" \
       -H "Content-Type: application/json" \
       -d '{}' | grep -q "message\|errors"; then
        print_success "API de autenticação respondendo"
    else
        print_warning "API de autenticação pode não estar funcionando"
    fi
else
    print_error "Inicie o backend com: cd backend-laravel && ./start.sh"
fi

echo ""

# Testar Frontend Vue.js
print_status "Testando Frontend Vue.js..."
if check_service "http://localhost:3002" "Frontend Vue.js"; then
    print_success "Frontend acessível"
else
    print_error "Inicie o frontend com: cd frontend && ./start.sh"
fi

echo ""

# Testar conectividade entre Frontend e Backend
print_status "Testando conectividade Frontend → Backend..."

# Simular requisição do frontend para o backend
FRONTEND_TO_BACKEND=$(curl -s -w "%{http_code}" -o /dev/null "http://localhost:8000/api/products")

if [ "$FRONTEND_TO_BACKEND" = "200" ]; then
    print_success "Conectividade Frontend → Backend OK"
elif [ "$FRONTEND_TO_BACKEND" = "000" ]; then
    print_error "Backend não está respondendo"
else
    print_warning "Backend respondeu com código: $FRONTEND_TO_BACKEND"
fi

echo ""

# Verificar arquivos de configuração
print_status "Verificando configurações..."

# Backend .env
if [ -f "backend-laravel/.env" ]; then
    print_success "Arquivo backend-laravel/.env existe"
    
    # Verificar configurações críticas
    if grep -q "APP_KEY=base64:" backend-laravel/.env; then
        print_success "APP_KEY configurada"
    else
        print_warning "APP_KEY pode não estar configurada corretamente"
    fi
    
    if grep -q "DB_CONNECTION=mysql" backend-laravel/.env; then
        print_success "Banco de dados MySQL configurado"
    else
        print_warning "Configuração de banco pode estar pendente"
    fi
else
    print_error "Arquivo backend-laravel/.env não encontrado"
fi

# Frontend .env
if [ -f "frontend/.env" ]; then
    print_success "Arquivo frontend/.env existe"
    
    if grep -q "VITE_API_URL=http://localhost:8000" frontend/.env; then
        print_success "URL da API configurada corretamente"
    else
        print_warning "URL da API pode estar incorreta"
    fi
else
    print_warning "Arquivo frontend/.env não encontrado"
fi

echo ""

# Resumo final
print_status "📋 RESUMO DOS TESTES:"
echo ""

if check_service "http://localhost:8000" "Backend" > /dev/null 2>&1 && \
   check_service "http://localhost:3002" "Frontend" > /dev/null 2>&1; then
    
    print_success "🎉 APLICAÇÃO FUNCIONANDO!"
    echo ""
    echo "🌐 URLs de acesso:"
    echo "   Frontend: http://localhost:3002"
    echo "   Backend:  http://localhost:8000"
    echo "   API:      http://localhost:8000/api"
    echo ""
    echo "📚 Para testar a integração:"
    echo "   1. Acesse http://localhost:3002"
    echo "   2. Vá para a página de produtos"
    echo "   3. Registre um usuário"
    echo "   4. Adicione produtos ao carrinho"
    echo "   5. Finalize um pedido"
    
else
    print_error "❌ APLICAÇÃO NÃO ESTÁ COMPLETAMENTE FUNCIONAL"
    echo ""
    echo "🔧 Para corrigir:"
    echo ""
    echo "1. Iniciar Backend:"
    echo "   cd backend-laravel"
    echo "   ./start.sh"
    echo ""
    echo "2. Iniciar Frontend (em outro terminal):"
    echo "   cd frontend"
    echo "   ./start.sh"
    echo ""
    echo "3. Executar este teste novamente:"
    echo "   ./test.sh"
fi

echo ""
echo "🚀 Script de teste concluído!"