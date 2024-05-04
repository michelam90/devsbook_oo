<?php 
require_once 'models/UserRelation.php';

class UserRelationDaoBd implements UserRelationDAO {

    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    // Inserindo seguido no bando (començando a seguir)
    public function insert(UserRelation $u) {
        $sql = "INSERT INTO userrelations (user_from, user_to) VALUES (:user_from, :user_to)";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':user_from', $u->user_from);
        $sql->bindValue(':user_to', $u->user_to);
        $sql->execute();

    }

    // Removendo seguidor do banco (Deixando de seguir)
    public function delete(UserRelation $u) {
        $sql = "DELETE FROM userrelations WHERE user_from = :user_from AND user_to = :user_to";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':user_from', $u->user_from);
        $sql->bindValue(':user_to', $u->user_to);
        $sql->execute();
    }

    // Usuários que o ID está seguindo
    public function getFollowing($id) {
        $users = []; // Criando array para retornar a lista de id dos usuários

        $sql = "SELECT user_to FROM userrelations WHERE user_from = :user_from";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':user_from', $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll();
            foreach($data as $item) {
                $users[] = $item['user_to'];
            }
        }

        return $users;
    }

    // Usuários que o ID segue
    public function getFollowers($id) {
       $users = [];
        $sql = "SELECT user_from FROM userrelations WHERE user_to = :user_to";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':user_to', $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll();
            foreach($data as $item) {
                $users[] = $item['user_from'];
            }
        }

        return $users;
    }

    // Verificando se o usuário logado segue o perfil de outro usuário
    public function isFollowing($id1, $id2) {

        $sql = "SELECT * FROM userrelations WHERE user_from = :user_from AND user_to = :user_to";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':user_from', $id1);
        $sql->bindValue(':user_to', $id2);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

}