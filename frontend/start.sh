#!/bin/bash

# Script para iniciar o frontend Vue.js
# Karibe Presente Personalizado

echo "🎨 Iniciando Frontend Vue.js - Karibe Presente Personalizado"
echo "==========================================================="

# Verificar se estamos no diretório correto
if [ ! -f "package.json" ]; then
    echo "❌ Arquivo package.json não encontrado. Execute este script dentro da pasta frontend."
    exit 1
fi

# Verificar se node_modules existe
if [ ! -d "node_modules" ]; then
    echo "📦 Instalando dependências do Node.js..."
    npm install
    if [ $? -ne 0 ]; then
        echo "❌ Erro ao instalar dependências"
        exit 1
    fi
    echo "✅ Dependências instaladas"
fi

# Verificar se .env existe
if [ ! -f ".env" ]; then
    echo "⚙️  Criando arquivo .env..."
    cat > .env << EOF
VITE_APP_NAME="Karibe Presente Personalizado"
VITE_API_URL=http://localhost:8000/api
VITE_APP_URL=http://localhost:3002
EOF
    echo "✅ Arquivo .env criado"
fi

# Iniciar servidor de desenvolvimento
echo ""
echo "🌐 Iniciando servidor de desenvolvimento..."
echo "📍 URL: http://localhost:3002"
echo "🛑 Pressione Ctrl+C para parar"
echo ""

npm run dev -- --port 3002