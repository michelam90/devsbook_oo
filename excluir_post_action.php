<?php 
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoBd.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id = filter_input(INPUT_GET, 'id');

if($id) {
    
    // Instanciando objeto post
    $postDao = new PostDaoBd($pdo);

        
    // Deletando o post
    $postDao->delete($id, $userInfo->id);
}

header("Location: ".$base);
exit;