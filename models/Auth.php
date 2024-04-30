<?php 

require_once "dao/UserDaoBd.php";

class Auth {
    private $pdo;
    private $base;
    private $dao;

    public function __construct(PDO $pdo, $base) {
        $this->pdo = $pdo;
        $this->base = $base;
        $this->dao = new UserDaoBd($this->pdo);
    }

    public function checkToken() {

        if(!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];
                   
            $user = $this->dao->findByToken($token);

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
                
        $user = $this->dao->findByEmail($email);
        if($user) { // Verifica se encontrou usuário com email
            if(password_verify($password, $user->password)) { // Verifica se identificou a senha
                $token = md5(time().rand(0, 99999)); // Cria o token

                $_SESSION['token'] = $token; // Atribui a sessão
                $user->token = $token; // Atribuiu o novo token ao usuário
                $this->dao->update($user); // Atualiza na base

                return true;

            }
        }

        return false;
    }

    
    public function emailExists($email) {
        
        return $this->dao->findByEmail($email) ? true : false;
        
    }

    public function registerUser($name, $email, $password, $birthdate) {
       
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
       
        $this->dao->insert($newUser);

        $_SESSION['token'] = $token;
    }
}