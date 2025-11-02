<?php

$notas=[
    'silvio' => 10,
    'ana' => null,
    'bia' => 8,
    'carlos' => 7,
    'daniel' => 6
];

ksort($notas);
var_dump($notas); 

 /*sort(  $notas);
 var_dump($notas);
 
 rsort($notas);
 var_dump($notas);
 
 asort($notas);
 var_dump($notas);
 
 arsort($notas);
 var_dump($notas);
 
 ksort($notas);
 var_dump($notas);
 
 krsort($notas);
 var_dump($notas);*/


if (is_array($notas)){
    echo 'Sim, é um array';
}

var_dump(array_is_list($notas));

echo 'ana fez a prova'. "\n";
var_dump(isset($notas['ana']));

echo 'alguem tirou 10?'. "\n";
var_dump(in_array(10, $notas, true));

echo 'Quem tirou 10?'. "\n";
var_dump(array_search(10, $notas, true));