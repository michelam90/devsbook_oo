<?php 
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/UserrelationDaoBd.php';
require_once 'dao/UserDaoBd.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id = filter_input(INPUT_GET, 'id');

// Verificar se o ID foi passado
if($id) {
    $userRelationDao = new UserRelationDaoBd($pdo);
    $userDao = new UserDaoBd($pdo);
    // Verifica se o ID existe na base de dados
    if($userDao->findById($id)) {
        $relation = new UserRelation();
        $relation->user_from = $userInfo->id;
        $relation->user_to = $id;

        // Se o usuário existe, verificamos se já estamos seguindo ele 
        if($userRelationDao->isFollowing($userInfo->id, $id)) {
            // Deixar de seguir
            $userRelationDao->delete($relation);
        } else {
            // Começar a seguir
            $userRelationDao->insert($relation);
        }

        header("Location: perfil.php?id=".$id);
        exit;
    }
}

header("Location: ".$base);
exit;