<?php
$array=[
    1> 'um',
    2=> 'dois',
    3=> 'três',
];

foreach($array as $numeral => $nomeNumero) {
    echo "$numeral em portugues é $nomeNumero\n";
}
 echo "total: " . count($array) . "\n";