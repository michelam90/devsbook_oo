<?php 
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoBd.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$body = filter_input(INPUT_POST, 'body');

if($body) {

    // Instanciando objeto post, atribuindo dados a ele e inserindo no banco
    $postDao = new PostDaoBd($pdo);

    $newPost = new Post();
    $newPost->id_user = $userInfo->id;
    $newPost->type = 'text';
    $newPost->created_at = date('Y-m-d H:i:s');
    $newPost->body = $body;
    
    // Insert in db
    $postDao->insert($newPost);
}

header("Location: ".$base);
exit;