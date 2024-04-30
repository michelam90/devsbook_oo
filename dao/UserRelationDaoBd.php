<?php 
require_once 'models/UserRelation.php';

class UserRelationDaoBd implements UserRelationDAO {

    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    public function insert(UserRelation $u) {

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
}