<?php

$dbPath = __DIR__ . '/banco.sqlite';
$pdo = new PDO("sqlite:$dbPath");

// Verificar se o banco existe e tem tabelas
echo "Verificando tabelas no banco de dados...\n";

try {
    $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table';");
    $tables = $result->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "Nenhuma tabela encontrada no banco de dados.\n";
    } else {
        echo "Tabelas encontradas:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    }
    
    // Verificar especificamente a tabela videos
    $videoTableExists = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='videos';")->fetch();
    
    if ($videoTableExists) {
        echo "\nTabela 'videos' existe!\n";
        // Mostrar estrutura da tabela
        $structure = $pdo->query("PRAGMA table_info(videos);")->fetchAll(PDO::FETCH_ASSOC);
        echo "Estrutura da tabela 'videos':\n";
        foreach ($structure as $column) {
            echo sprintf("- %s (%s)\n", $column['name'], $column['type']);
        }
    } else {
        echo "\nTabela 'videos' NÃO existe!\n";
    }
    
} catch (Exception $e) {
    echo "Erro ao verificar banco: " . $e->getMessage() . "\n";
}