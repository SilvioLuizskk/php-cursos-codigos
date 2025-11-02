<?php
$caminhoArquivo = __DIR__ . "/filme.json";  
$conteudoDoArquivoFilme = file_get_contents($caminhoArquivo);
$filme = json_decode($conteudoDoArquivoFilme, true);

var_dump($filme);