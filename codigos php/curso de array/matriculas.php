<?php

$alunos2021=[
   0=> 'daniel',
    1=> 'ana',
    2=> 'carlos',
    3=> 'bia',
    4=> 'silvio',
];

$novosAlunos=[
   5=> 'luiz',
   6=> 'maria',
   7=> 'joao',
   8=> 'pedro',
   9=> 'julia',
];

//$alunos2022=array_merge($alunos2021, $novosAlunos);
//var_dump($alunos2022);
$alunos2022=[...$alunos2021,'Carlos Vinicios', ... $novosAlunos];
array_push($alunos2022, 'matheus', 'marcos', 'roberto');
$alunos2021[]='rodrigo';
array_unshift($alunos2021, 'paulo', 'andre');
array_shift($alunos2022);
array_pop($alunos2022);


var_dump($alunos2022);
var_dump($alunos2021);
echo array_shift($alunos2021,);


