<?php

$nome = 'Silvio LUIZ';
$email = ' silvio@alura.com.br ';
$senha = 'áéíóú';

echo strlen($senha) . PHP_EOL;

if (strlen($senha) < 5) {
    echo 'Senha insergura' . PHP_EOL;
}

$posicaoDoArroba = strpos($email, '@');

$usuario = substr($email, 0, $posicaoDoArroba);

echo strtoupper($usuario) . PHP_EOL;
echo substr($email, $posicaoDoArroba + 1) . PHP_EOL;

list($nome, $sobrenome) = explode(' ', $nome);

echo 'Nome: ' . $nome . PHP_EOL;
echo 'Sobrenome: ' . $sobrenome . PHP_EOL;

$csv = 'Silvio Luiz,30,silvio@alura.com.br';
var_dump(explode(',', $csv));

echo trim($email) . PHP_EOL;