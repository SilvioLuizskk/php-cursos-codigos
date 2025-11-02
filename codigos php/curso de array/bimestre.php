<?php

$notasBimestre1=[
    'daniel' => 6,
    'ana' => 9,
    'carlos' => 7,
    'bia' => 8,
    'silvio' => 10,
];

$notasBimestre2=[
   
    'carlos' => 9,
    'bia' => 7,
    'silvio' => 10,
];

//var_dump(array_diff_assoc($notasBimestre1, $notasBimestre2));

$alunosFaltantes=array_diff_key($notasBimestre1, $notasBimestre2);
$nomesAlunos=array_keys($alunosFaltantes);
$notasAlunos=array_values($alunosFaltantes);
var_dump(array_combine($nomesAlunos, $notasAlunos));
//var_dump(array_keys($alunosFaltantes));
//var_dump(array_values($alunosFaltantes));


