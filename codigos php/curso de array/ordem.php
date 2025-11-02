<?php

$notas = [
  ['nota' => 10,
   'aluno' => 'Ana'
  ],
  [
    'nota' => 6,
    'aluno' => 'Bia'
  ],
  [
    'nota' => 9, 
    'aluno' => 'Carlos'
  ],
  [
    'nota' => 8, 
    'aluno' => 'Daniel'
  ],
];
 function ordenaNotas(array $nota1, array $nota2): int {
  return $nota2['nota'] <=> $nota1['nota'];
 }

usort($notas, callback:'ordenaNotas');
var_dump($notas);