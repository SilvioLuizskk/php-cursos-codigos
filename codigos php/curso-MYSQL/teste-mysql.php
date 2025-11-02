<?php
echo "<h1>Teste de Conexão MySQL</h1>";

// Lista de credenciais para testar
$credenciais = [
    ['root', ''],           // sem senha
    ['root', 'root'],       // senha root
    ['root', '1234'],       // senha 1234
    ['root', 'mysql'],      // senha mysql
    ['root', 'password'],   // senha password
];

foreach ($credenciais as $cred) {
    $usuario = $cred[0];
    $senha = $cred[1];
    $senha_display = $senha === '' ? '(sem senha)' : $senha;
    
    try {
        $pdo = new PDO('mysql:host=localhost', $usuario, $senha);
        echo "<p style='color: green;'>✅ SUCESSO: Usuário '$usuario' com senha '$senha_display'</p>";
        
        // Testa se o banco serenatto existe
        try {
            $pdo_db = new PDO('mysql:host=localhost;dbname=serenatto', $usuario, $senha);
            echo "<p style='color: blue;'>✅ Banco 'serenatto' existe e é acessível!</p>";
        } catch (PDOException $e) {
            echo "<p style='color: orange;'>⚠️ Conectou ao MySQL, mas banco 'serenatto' não existe</p>";
        }
        break;
        
    } catch (PDOException $e) {
        echo "<p style='color: red;'>❌ FALHOU: Usuário '$usuario' com senha '$senha_display'</p>";
    }
}
?>