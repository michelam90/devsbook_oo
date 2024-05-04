<?php 
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostCommentDaoBd.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id = filter_input(INPUT_POST, 'id');
$txt = filter_input(INPUT_POST, 'txt');

$array = [];

if($id && $txt) {
    $postCommentDao = new PostCommentDaoBd($pdo);

    $newComment = new PostComment();
    $newComment->id_post = $id;
    $newComment->id_user = $userInfo->id;
    $newComment->body = $txt;
    $newComment->created_at = date('Y-m-d H:i:s');

/*
    print_r($newComment);
    exit;
*/

    // Inserido comentárip no bando de dados
    $postCommentDao->addComment($newComment);



    // Montando o array com os dados do comentário para ser exibido via json no post após inserção no BD se recarregar a página
    $array = [
        'error' => '',
        'link' => $base.'/perfil.php?id='.$userInfo->id,
        'avatar' => $base.'/media/avatars/'.$userInfo->avatar,
        'name' => $userInfo->name,
        'body' => $txt
    ];


}
// Passando os dados para um Json exibir no post
header("Content-Type: application/json");
echo json_encode($array);
exit;