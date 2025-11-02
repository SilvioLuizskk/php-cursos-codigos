<?php 

declare(strict_types=1);

//escalares
$string = 'Valores textuais';
$int = 27;
$float = 15.5;
$bool = true;
$bool = false;

//compostos
$array = [];
$array = [134, 348417, 945718];

$array = [$string, $int, $float, $bool];
//type cast
$valorNumerico = '27';
$valorInteiro = (int) $valorNumerico;

$valorDecimal = 27.5;
$valorInteiro = (int) $valorDecimal;

//var_dump($valorInteiro);
//var_dump((bool) '');

//var_dump($valorInteiro);
//var_dump((bool) $string);
//var_dump((bool) '');
//var_dump((float) '27 maçãs');
//var_dump((float) '27.5');
//var_dump((float) '27.5maçãs');
//var_dump((float) '27.5e');
//var_dump((float) '27 .5'); 
if ($string) {
    echo 'Verdadeiro' . "\n";
}

if ('') {
    echo 'Verdadeiro' . "\n";
} else {
    echo 'Falso' . "\n";
}
if (1) {
    echo 'Verdadeiro' . "\n";
}

if (0) {
    echo 'Falso' . "\n";
}

if (0.0) {
    echo 'Falso' . "\n";
}

if (0.1) {
    echo 'Verdadeiro' . "\n";
}

if (-0.1) {
    echo 'Verdadeiro' . "\n";
}
/*var_dump('Valor numérico' == 0);
var_dump('27' == 27);
var_dump('27' > 28);
var_dump('teste' > 28);
declare(strict_types=1);
var_dump('27' == 27);
var_dump('27' === 27);*/


require __DIR__ . '/screen-match/src/funcoes.php';

var_dump(exibeMensagemLancamento((int) 2025));