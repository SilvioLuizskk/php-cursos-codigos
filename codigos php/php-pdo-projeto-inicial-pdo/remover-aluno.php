<?php

require_once __DIR__ . '/vendor/autoload.php';

$pdo = \Alura\Pdo\Infrastructure\Persistence\ConnectionCreator::createConnection();

// Verificar se foi fornecido um ID
if (!isset($argv[1])) {
    echo "Uso: php remover-aluno.php <id>" . PHP_EOL;
    echo "Exemplo: php remover-aluno.php 5" . PHP_EOL;
    exit(1);
}

$id = (int) $argv[1];

if ($id <= 0) {
    echo "Erro: ID deve ser um número positivo" . PHP_EOL;
    exit(1);
}

// Primeiro verificar se o aluno existe
$checkStatement = $pdo->prepare('SELECT name FROM students WHERE id = ?;');
$checkStatement->bindValue(1, $id, PDO::PARAM_INT);
$checkStatement->execute();
$student = $checkStatement->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Erro: Aluno com ID {$id} não encontrado" . PHP_EOL;
    exit(1);
}

// Remover o aluno
$preparedStatement = $pdo->prepare('DELETE FROM students WHERE id = ?;');
$preparedStatement->bindValue(1, $id, PDO::PARAM_INT);
$success = $preparedStatement->execute();

if ($success && $preparedStatement->rowCount() > 0) {
    echo "Aluno '{$student['name']}' (ID: {$id}) removido com sucesso!" . PHP_EOL;
} else {
    echo "Erro ao remover o aluno" . PHP_EOL;
}
