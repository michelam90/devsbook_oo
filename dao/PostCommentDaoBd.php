<?php 
require_once 'models/PostComment.php';
require_once 'dao/UserDaoBd.php';

class PostCommentDaoBd implements PostCommentDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    // Pegando dados dos comentários
    public function getComments($id_post) {
        $array = [];

        $sql = "SELECT * FROM postcomments WHERE id_post = :id_post";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':id_post', $id_post);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            // Pegando os dados dos usuários para exibir no post
            $userDao = new UserDaoBd($this->pdo);

            // Fazendo o loop para incrementar os dados dos comentários no objeto de comentários
            foreach($data as $item) {
                $commentItem = new PostComment();
                $commentItem->id = $item['id'];
                $commentItem->id_post = $item['id_post'];
                $commentItem->id_user = $item['id_user'];
                $commentItem->body = $item['body'];
                $commentItem->created_at = $item['created_at'];
                // Pegando dados do usuário do post
                $commentItem->user = $userDao->findById($item['id_user']);

                $array[] = $commentItem;

            }
        }

        return $array;

    }

    // Adicionando comentário ao bando de dados
    public function addComment(PostComment $pc) {
        //echo "INSERT INTO postcomments (id_post, id_user, body, created_at) VALUES (".$pc->id_post.", ".$pc->id_user.", ".$pc->body.", ".$pc->created_at.")";
        $sql = "INSERT INTO postcomments (id_post, id_user, body, created_at) VALUES (:id_post, :id_user, :body, :created_at)";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':id_post', $pc->id_post);
        $sql->bindValue(':id_user', $pc->id_user);
        $sql->bindValue(':body', $pc->body);
        $sql->bindValue(':created_at', $pc->created_at);
        $sql->execute();
        
       // echo $pc->id_post.' '.$pc->id_user.' '.$pc->body.' '.$pc->created_at;
    }
}