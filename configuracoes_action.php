<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/PostDaoBd.php';

// Verificando se o usuários está logado
$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$userDao = new UserDaoBd($pdo);


// Receber todos os campos
$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$birthdate = filter_input(INPUT_POST, 'birthdate');
$city = filter_input(INPUT_POST, 'city');
$work = filter_input(INPUT_POST, 'work');
$password = filter_input(INPUT_POST, 'password');
$password_confirmation = filter_input(INPUT_POST, 'password_confirmation');

// Verificações minimas 
if($name && $email) {
    // Atribuir as variaveis do form as propriedades do objeto userInfo
    $userInfo->name = $name;
    $userInfo->city = $city;
    $userInfo->work = $work;

    // Verficar se o usuário está alterando o email
    // Se sim, verificar se esse novo email já está cadastrado no bd
    if($userInfo->email !== $email) {
        if($userDao->findByEmail($email) === false) {
            $userInfo->email = $email; // Se o email não existe no bd, usamos ele para atualizar
        } else {
            // Se o email já existe, paramos tudo e informamos o usuário
            $_SESSION['flash'] = 'E-mail já existe!';
            header("Location: ".$base."/configuracoes.php");
            exit;
        }
    }

    // Validando formato da data de Nascimento
    $birthdate = explode("/", $birthdate);
    if(count($birthdate) != 3) {
        $_SESSION['flash'] = "Data de nascimento preenchida no formato errado. Use o formato '00/00/0000'";
        header("Location: ".$base."/configuracoes.php"); 
        exit;
    }

    // Validando se a data de nascimento é valida
    $birthdate = $birthdate[2]."-".$birthdate[1]."-".$birthdate[0];
    if(strtotime($birthdate) == false) {
        $_SESSION['flash'] = "A data de nascimento preenchida é invalida. Verifique os números.";
        header("Location: ".$base."/configuracoes.php"); 
        exit;
    }

    // Recebendo a data de nascimento na propriedade do objeto
    $userInfo->birthdate = $birthdate;

    // Verificando se o usuário quer mudar a senha
    if(!empty($password)) {
        if($password === $password_confirmation) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userInfo->password = $hash;           

        } else {
            $_SESSION['flash'] = 'As senhas não são iguais, digite novamente';
            header("Location: ".$base."/configuracoes.php");
            exit;
        }
    }

    // Verificando arquivos enviados
    // echo "<pre>";
    // print_r($_FILES);
   

    // Recebendo imagem do avatar
    if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
        $newAvatar = $_FILES['avatar'];
       
        if(in_array($newAvatar['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {

            // Calculando dimensõe da imagem
            $avatarWidth = 200;
            $avatarHeight = 200;

            // Calculando a proporção
            list($widthOrig, $heightOrig) = getimagesize($newAvatar['tmp_name']);
            $ratio = $widthOrig / $heightOrig;

            $newWidth = $avatarWidth;
            $newHeight = $newWidth / $ratio;

            // Verificando se a imagem está aproximadamente do tamanho pre definido (200X200) sem perder qualidade
            if($newHeight < $avatarHeight) {
                $newHeight = $avatarHeight;
                $newWidth = $newHeight * $ratio;
            }

            //echo $newWidth.' x '.$newHeight;

            // Definindo valor de corte para ficar na proporção que precisamos
            $x = $avatarWidth - $newWidth;
            $y = $avatarHeight - $newHeight;
            $x = $x<0 ? $x/2 : $x;
            $y = $y<0 ? $y/2 : $y;

            //echo $x . ' X '. $y;

            $finalImage = imagecreatetruecolor($avatarWidth, $avatarHeight);

            switch($newAvatar['type']) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = imagecreatefromjpeg($newAvatar['tmp_name']);
                break;
                case 'image/png':
                    $image = imagecreatefrompng($newAvatar['tmp_name']);
                break;
            }

            imagecopyresampled(
                $finalImage, $image,
                $x, $y, 0, 0,
                $newWidth, $newHeight, $widthOrig, $heightOrig
            );

            // Definindo nome da imageme e salvando ela 
            $avatarName = md5(time().rand(0,9999)).'jpg';

            // Gerando imagem e salvando
            imagejpeg($finalImage, './media/avatars/'.$avatarName, 100); // 100 de qualidade

            $userInfo->avatar = $avatarName;
        }
    }
    

    // Atualizando dados no BD
    $userDao->update($userInfo);

    


}

header("Location: ".$base."/configuracoes.php");
exit;