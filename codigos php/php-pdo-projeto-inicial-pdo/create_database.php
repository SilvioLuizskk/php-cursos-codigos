<?php

require_once __DIR__ . '/vendor/autoload.php';

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

echo "Criando banco de dados e tabela students..." . PHP_EOL;

// Criar conexão - isso criará o arquivo banco.sqlite se não existir
$pdo = ConnectionCreator::createConnection();

// Criar tabela students
$sql = <<<SQL
CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    birth_date TEXT NOT NULL
);
SQL;

$pdo->exec($sql);

echo "Banco e tabela 'students' criados com sucesso!" . PHP_EOL;
echo "Arquivo do banco: " . realpath(__DIR__ . '/banco.sqlite') . PHP_EOL;

// Inserir alguns dados de exemplo
$insertSql = "INSERT OR IGNORE INTO students (name, birth_date) VALUES (?, ?)";
$stmt = $pdo->prepare($insertSql);

$examples = [
    ['Patricia Freitas', '1986-10-25'],
    ['João Silva', '1990-05-15'],
    ['Maria Santos', '1988-12-03']
];

foreach ($examples as $example) {
    $stmt->execute($example);
}

echo "Dados de exemplo inseridos!" . PHP_EOL;