<?php
$usuario = "michel";
$array = ['michel', 'fabiola', 'miro', 'Fabiana', 'Sabrina'];

echo "<pre/>";
print_r($array);

if(in_array($usuario, $array)) {
    echo "Usuário valido, ".$usuario;
} else {
    echo $usuario." , não é um usuário valido!";
}

