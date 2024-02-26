<?php
require 'config.php';
require 'models/Auth.php';


// Verificando se o usuários está logado
$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

echo "Index";