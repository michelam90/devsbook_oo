<?php 

require_once "dao/UserDaoBd.php";

class Auth {
    private $pdo;
    private $base;

    public function __construct(PDO $pdo, $base) {
        $this->pdo = $pdo;
        $this->base = $base;
    }

    public function checkToken() {

        if(!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];
       // if(!empty($token)) {

            $userDao = new UserDaoBd($this->pdo);
            $user = $userDao->findByToken($token);

            if($user) { // Verificar se o o token do usuário foi encontrado
                return $user;
            }
        }

        /* 
         * Padrão da função será retornar para página de login, 
         * caso os ifs anteriores não tenham sido atendidos
        */
        header("Location: ".$this->base."/login.php");
        exit;
    }


    public function validatelogin($email, $password) {
        $userDao = new UserDaoBd($this->pdo);
        
        $user = $userDao->findByEmail($email);
        if($user) { // Verifica se encontrou usuário com email
            if(password_verify($password, $user->password)) { // Verifica se identificou a senha
                $token = md5(time().rand(0, 99999)); // Cria o token

                $_SESSION['token'] = $token; // Atribui a sessão
                $user->token = $token; // Atribuiu o novo token ao usuário
                $userDao->update($user); // Atualiza na base

                return true;

            }
        }

        return false;
    }

    public function emailExists($email) {
        $userDao = new UserDaoBd($this->pdo);
        return $userDao->findByEmail($email) ? true : false;
        
    }

    public function registerUser($name, $email, $password, $birthdate) {
        $userDao = new UserDaoBd($this->pdo);

        // Criando hash da senha
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Criando token para logar após o cadastro
        $token = md5(time().rand(0, 99999)); // Cria o token

        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->birthdate = $birthdate;
        $newUser->token = $token; // Para logar depois do cadastro
       
        $userDao->insert($newUser);

        $_SESSION['token'] = $token;
    }
}