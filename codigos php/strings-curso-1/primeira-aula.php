<?php

$nome = 'Silvio Luiz';

$ehDaMinhaFamilia = str_contains($nome, 'Luiz');

if ($ehDaMinhaFamilia) {
    echo "$nome é da minha família" . PHP_EOL;
} else {
    echo "$nome não é da minha família" . PHP_EOL;
}
