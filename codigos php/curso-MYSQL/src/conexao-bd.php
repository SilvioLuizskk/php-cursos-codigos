<?php

// Tenta conectar sem senha primeiro
try {
    $pdo = new PDO('mysql:host=localhost;dbname=serenatto', 'root', '');
} catch (PDOException $e) {
    // Se falhar, tenta com senha
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=serenatto', 'root', 'root');
    } catch (PDOException $e2) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=serenatto', 'root', '1234');
        } catch (PDOException $e3) {
            die("Erro de conexão: " . $e3->getMessage());
        }
    }
}
