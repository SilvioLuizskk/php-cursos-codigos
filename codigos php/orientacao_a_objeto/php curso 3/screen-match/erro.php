<?php
use ScreenMatch\Calculos\ConversorNotaEstrela;
use ScreenMatch\Modelo\Episodio;
use ScreenMatch\Modelo\Genero;
use ScreenMatch\Modelo\Serie;
use ScreenMatch\Exception\NotaInvalidaException;
 require 'autoload.php';

    $serie = new Serie('nome da serie', 2024 , Genero::Acao, 7 ,20, 30);

 $serie = new Serie('nome da serie', 2024 , Genero::Acao, 7 ,20, 30);
 $episodio = new Episodio($serie, 'Piloto', 1);

try {
    $episodio->avalia(45);
    $episodio->avalia(-35);

    $conversor = new ConversorNotaEstrela();
    echo $conversor->converte($Episodio);
} catch (NotaInvalidaException $e) {
    echo "Um problema aconteceu: " . $e->getMessage();
}
