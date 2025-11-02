<?php

declare(strict_types=1);

echo "🚀 Configurando banco de dados...\n";

$dbPath = __DIR__ . '/banco.sqlite';

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Criar tabela de usuários
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            senha TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Criar tabela de vídeos
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS videos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            url TEXT NOT NULL,
            titulo TEXT NOT NULL,
            descricao TEXT,
            image_path TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Usuário admin
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmt->execute(['admin@aluraplay.com']);
    
    if ($stmt->fetchColumn() == 0) {
        $senhaHash = password_hash('123456', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (email, senha) VALUES (?, ?)");
        $stmt->execute(['admin@aluraplay.com', $senhaHash]);
        echo "✅ Usuário admin criado\n";
    }
    
    // Vídeos de exemplo
    $stmt = $pdo->query("SELECT COUNT(*) FROM videos");
    if ($stmt->fetchColumn() == 0) {
        $videos = [
            ['url' => 'https://youtube.com/watch?v=exemplo1', 'titulo' => 'Vídeo Exemplo 1', 'descricao' => 'Descrição do vídeo 1'],
            ['url' => 'https://youtube.com/watch?v=exemplo2', 'titulo' => 'Vídeo Exemplo 2', 'descricao' => 'Descrição do vídeo 2'],
            ['url' => 'https://youtube.com/watch?v=exemplo3', 'titulo' => 'Vídeo Exemplo 3', 'descricao' => 'Descrição do vídeo 3']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO videos (url, titulo, descricao) VALUES (?, ?, ?)");
        foreach ($videos as $video) {
            $stmt->execute([$video['url'], $video['titulo'], $video['descricao']]);
        }
        echo "✅ Vídeos de exemplo criados\n";
    }
    
    echo "✅ Banco configurado com sucesso!\n";
    
} catch (PDOException $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}