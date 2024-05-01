<?php 
require_once 'models/Postlike.php';

class PostLikeDaoBd implements PostLikeDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    // Pegando quantidade de curtidas de um post
    public function getLikeCount($id_post) {
        $sql = "SELECT COUNT(*) AS c FROM postlikes WHERE id_post = :id_post";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':id_post', $id_post);
        $sql->execute();

        $data = $sql->fetch();
        return $data['c'];
    }
    
    // Verificando se o usuário logado curtiu este post
    public function isLiked($id_post, $id_user) {
        $sql = "SELECT * FROM postlikes WHERE id_post = :id_post AND id_user = :id_user";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':id_post', $id_post);
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Verificando e Deletando ou Inserindo curtida do usuário logado no BD 
    public function likeToggle($id_post, $id_user) {

        if($this->isLiked($id_post, $id_user)) {
            // Se o usuário logado já tinha curtido e clicou novamente em cutir, vamos deletar (Descurtir)
            $sql = "DELETE FROM postlikes WHERE id_post = :id_post AND id_user = :id_user";
            $sql = $this->pdo->prepare($sql);           
        } else {
            // Caso o usuário nunca tenha cutido esse post, vamos inserir uma nova curtida
            $sql = "INSERT INTO postlikes (id_post, id_user, created_at) VALUES (:id_post, :id_user, NOW())";
            $sql = $this->pdo->prepare($sql);           
        }

        $sql->bindValue(':id_post', $id_post);
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();
    }

}