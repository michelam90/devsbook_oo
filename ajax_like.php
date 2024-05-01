<?php 
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostLikeDaoBd.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id = filter_input(INPUT_GET, 'id');

if(!empty($id)) {
    echo 'oi!';
    $postLikeDao = new PostLikeDaoBd($pdo);
    $postLikeDao->likeToggle($id, $userInfo->id);    
}
