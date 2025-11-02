<?php
$filme=[
    'nome' => $_POST['nome'],
    'ano' => $_POST['ano'],
    'genero' => $_POST['genero'],
    'nota' => $_POST['nota']
];

file_put_contents(
  'filme.json', json_encode($filme)
);

header('Location: /sucesso.php?filme=' . $filme['nome']);