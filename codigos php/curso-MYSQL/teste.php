<?php
echo "<h1>Servidor PHP funcionando!</h1>";
echo "<p>Hora atual: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP versão: " . phpversion() . "</p>";
echo "<br>";
echo "<a href='login.html'>Ir para Login</a><br>";
echo "<a href='admin.php'>Ir para Admin (precisa do banco)</a><br>";
echo "<a href='index.php'>Ir para Index (precisa do banco)</a>";
?>