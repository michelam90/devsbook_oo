<?php 
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoBd.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

// Definindo dimensões da imagem
$maxWidth = 800;
$maxHeight = 800;

$array = ['error' => ''];

$postDao = new PostDaoBd($pdo);

// Verificando se a imagem foi enviada
if(isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name']) ) {
    $photo = $_FILES['photo'];

    // Extensões validas
    $extensoes = ['image/jpg', 'image/jpeg', 'image/png'];
    // Verificando se a imagem está em um formato aceito
    if(in_array($photo['type'],  $extensoes)) {

        // Pegando tamanho original da imagem
        list($widthOriginal, $heightOriginal) = getimagesize($photo['tmp_name']);

        // Calculando a proporcionalidade da imagem
        $ratio = $widthOriginal / $heightOriginal;

        $newWidth = $maxWidth;
        $newHeight = $maxHeight;
        $ratioMax = $newWidth / $newHeight;

        if($ratioMax > $ratio) {
            $newWidth = $newHeight * $ratio;
        } else {
            $newHeight = $newWidth / $ratio;
        }

        // Criando a imagem
        $finalImage = imagecreatetruecolor($newWidth, $newHeight);
        switch($photo['type']) {
            case 'image/jpg':
            case 'image/jpeg':
                $image = imagecreatefromjpeg($photo['tmp_name']);
            break;
            case 'image/png':
                $image = imagecreatefrompng($photo['tmp_name']);
            break;
        }

        // Passando os novos parametros de redimensionamento da imagem
        imagecopyresampled(
            $finalImage, $image,
            0, 0, 0, 0,
            $newWidth, $newHeight, $widthOriginal, $heightOriginal
        );

        // Salvando a imagem
        $photoName = md5(time().rand(0,9999)).'.jpg';
        imagejpeg($finalImage, 'media/uploads/'.$photoName);

        // Preparando para inserir no banco de dados
        $newPost = new Post();
        $newPost->id_user = $userInfo->id;
        $newPost->type = 'photos';
        $newPost->created_at = date('Y-m-d H:i:s');
        $newPost->body = $photoName;

        // Inserindo do bd
        $postDao->insert($newPost);
    }

} else {
    $array['error'] = 'Nenhuma imagem enviada';
}

header("Content-Type: application/json");
echo json_encode($array);
exit;