<?php 
require_once "models/User.php";
require_once "UserRelationDaoBd.php";
require_once "dao/PostDaoBd.php";

class UserDaoBd implements UserDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    // Criando função auxiliar que recebe um array e monta um objeto
    private function generateUser($array, $full = false) {
        $u = new User();
        $u->id = $array['id'] ?? 0;
        $u->email = $array['email'] ?? '';
        $u->password = $array['password'] ?? '';
        $u->name = $array['name'] ?? '';
        $u->birthdate = $array['birthdate'] ?? '';
        $u->city = $array['city'] ?? '';
        $u->work = $array['work'] ?? '';
        $u->avatar = $array['avatar'] ?? '';
        $u->cover = $array['cover'] ?? '';
        $u->token = $array['token'] ?? '';

        if($full) { // Só será usado quando o paramentro $full form verdadeiro
            $urDaoBd = new UserRelationDaoBd($this->pdo);
            $postDaoBd = new PostDaoBd($this->pdo);

            // Followers = Quem segue o usuário logado
            $u->followers = $urDaoBd->getFollowers($u->id); // O próprio ID que é passado nessa função para o Objeto 
            // Pegando dados dos usuários pelo ID reutilizando a função findByID
            foreach($u->followers as $key => $follower_id) {
                $newUser = $this->findById($follower_id);
                $u->followers[$key] = $newUser;
            }

            // Following = Quem segue o usuário logado
            $u->following = $urDaoBd->getFollowing($u->id);
            foreach($u->following as $key => $following_id) {
                $newUser = $this->findById($following_id);
                $u->following[$key] = $newUser;
            }
            // Fotos
            $u->photos = $postDaoBd->getPhotosFrom($u->id);

        }

        return $u;
    }

    public function findByToken($token) {
        /*
            * Se achou o toke, busca no bd e cria o objeto
            * E retorna o objeto criado
        */
        if(!empty($token)) {
            $sql = "SELECT * FROM users WHERE token = :token";
            $sql = $this->pdo->prepare($sql);
            $sql->bindValue(":token", $token);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch();
                // Atribuir ao objeto usuário os dados do bd
                $user = $this->generateUser($data);
                return $user;
            }
        }
        // Se não achou o token retorna false
        return false;
    }


    public function findByEmail($email) {

        if(!empty($email)) {
            $sql = "SELECT * FROM users WHERE email = :email";
            $sql = $this->pdo->prepare($sql);
            $sql->bindValue(":email", $email);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch();
                $user = $this->generateUser($data);
                return $user;
            }
        }

        return false;
    }

    // Buscando pelo nome
    public function findByName($name) {

        $array = [];

        if(!empty($name)) {
            $sql = "SELECT * FROM users WHERE name LIKE :name";
            $sql = $this->pdo->prepare($sql);
            $sql->bindValue(':name', '%'.$name.'%');
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);
                
                foreach($data as $item) {
                    $array[] = $this->generateUser($item);
                }
            }
        }

        return $array;
    }

    public function findById($id, $full = false) {

        if(!empty($id)) {
            $sql = "SELECT * FROM users WHERE id = :id";
            $sql = $this->pdo->prepare($sql);
            $sql->bindValue(":id", $id);
            $sql->execute();

            if($sql->rowCount() > 0) {
                $data = $sql->fetch();
                $user = $this->generateUser($data, $full);
                return $user;
            }
        }

        return false;

    }

    public function update(User $u) {
        $sql = "UPDATE users SET
                email = :email,
                password = :password,
                name = :name,
                birthdate = :birthdate,
                city = :city,
                work = :work,
                avatar = :avatar,
                cover = :cover,
                token = :token,
                data = now()
                where id = :id";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":email", $u->email);
        $sql->bindValue(":password", $u->password);
        $sql->bindValue(":name", $u->name);
        $sql->bindValue(":birthdate", $u->birthdate);
        $sql->bindValue(":city", $u->city);
        $sql->bindValue(":work", $u->work);
        $sql->bindValue(":avatar", $u->avatar);
        $sql->bindValue(":cover", $u->cover);
        $sql->bindValue(":token", $u->token);
        $sql->bindValue(":id", $u->id);
        $sql->execute();

        return true;
    }


    public function insert(User $u) {
        $sql = "INSERT INTO users (email, password, name, birthdate, token, data)
                VALUES (:email, :password, :name, :birthdate, :token, now())";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":email", $u->email);
        $sql->bindValue(":password", $u->password);
        $sql->bindValue(":name", $u->name);        
        $sql->bindValue(":birthdate", $u->birthdate);
        $sql->bindValue(":token", $u->token);
        $sql->execute();

        return true;
    }
}