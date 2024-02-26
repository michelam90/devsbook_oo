<?php 
require "config.php";
require "models/Auth.php";

$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$birthdate = filter_input(INPUT_POST, 'birthdate');

if($name && $email && $password && $birthdate) {

    $auth = new Auth($pdo, $base);

    // Validando data de Nascimento
    $birthdate = explode("/", $birthdate);
    if(count($birthdate) != 3) {
        $_SESSION['flash'] = "Data de nascimento preenchida no formato errado. Use o formato '00/00/0000'";
        header("Location: ".$base."/signup.php"); 
        exit;
    }

    $birthdate = $birthdate[2]."-".$birthdate[1]."-".$birthdate[0];
    if(strtotime($birthdate) == false) {
        $_SESSION['flash'] = "A data de nascimento preenchida é invalida. Verifique os números.";
        header("Location: ".$base."/signup.php"); 
        exit;
    }
  
  /*  
  echo $name."<br>";
  echo $email."<br>";
  echo $password."<br>";
  echo $birthdate."<br>";  
  exit;
  */



    // Verificando se o email existe na base
    if($auth->emailExists($email) === false) {

        $auth->registerUser($name, $email, $password, $birthdate);
        
        header("Location: ".$base); 
        exit;

    } else {
        $_SESSION['flash'] = "O e-mail informado já foi cadastrado anteriormente.";
        header("Location: ".$base."/signup.php"); 
        exit;
    }

}
$_SESSION['flash'] = "Campos não preenchidos.";
// Por padrão, retorna para pagina de login
header("Location: ".$base."/signup.php"); 
exit;