<?php

$dados = ['Vinicius', 10, 24];


list($nome, $nota, $idade) = $dados;


var_dump($nome, $nota, $idade);


[$nome, $nota, $idade] = $dados;


$dados = [
    'nome' => 'Vinicius',
    'nota' => 10,
    'idade' => 24
];


['nome' => $nome, 'nota' => $nota, 'idade' => $idade] = $dados;

var_dump($nome, $nota, $idade);