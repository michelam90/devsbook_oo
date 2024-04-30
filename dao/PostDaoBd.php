<?php 
require_once 'models/Post.php';
require_once 'dao/UserRelationDaoBd.php';
require_once 'dao/UserDaoBd.php';

class PostDaoBd implements PostDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    public function insert(Post $p) {

        $sql = "INSERT INTO posts (id_user, type, created_at, body)
        VALUES (:id_user, :type, :created_at, :body)";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':id_user', $p->id_user);
        $sql->bindValue(':type', $p->type);
        $sql->bindValue(':created_at', $p->created_at);
        $sql->bindValue(':body', $p->body);
        $sql->execute();

    }

    // Para usar na time line do perfil do usuário
    public function getUserFeed($id_user) {

        $post_list = [];
        
        // Pegar os posts ordenados por data 
        $sql = "SELECT * FROM posts WHERE id_user = :id_user ORDER BY created_at DESC";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            // Transformar resultado em objetos
            $post_list = $this->postListToObject($data, $id_user);

            
        }

        return $post_list;
    }

    // Para usar na time line principal
    public function getHomeFeed($id_user) {

        $post_list = [];

        // Lista dos usuários que eu sigo
        $urDao = new UserRelationDaoBd($this->pdo);
        $userList = $urDao->getFollowing($id_user);
        $userList[] = $id_user;

        // Pegar os posts ordenados por data 
        $sql = "SELECT * FROM posts WHERE id_user IN (".implode(',', $userList).") ORDER BY created_at DESC";
        $sql = $this->pdo->query($sql);

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            // Transformar resultado em objetos
            $post_list = $this->postListToObject($data, $id_user);

            
        }

        return $post_list;
    }


    // Pegando as fotos do usuário
    public function getPhotosFrom($id_user) {
        $lista_fotos = [];

        $sql = "SELECT * FROM posts WHERE id_user = :id_user AND type = 'photos' ORDER BY created_at DESC";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            // Transformar resultado em objetos
            $lista_fotos = $this->postListToObject($data, $id_user);
        }

        return $lista_fotos;
    }

    // Função privada para criar os objetos 
    private function postListToObject($post_list, $id_user) {
        $userDao = new UserDaoBd($this->pdo); // Para pegar os dados do usuário que fez o post
        $posts = [];

        foreach($post_list as $post_item) {
            $newPost = new post();
            $newPost->id = $post_item['id'];
            $newPost->id_user = $post_item['id_user'];
            $newPost->created_at = $post_item['created_at'];
            $newPost->type = $post_item['type'];
            $newPost->body = $post_item['body'];
            $newPost->mine = false;

            // Verificando se é um post meu
            if($id_user == $post_item['id_user']) {
                $newPost->mine = true;
            }

            // Pegar informações do usuário que fez o post (atribuindo todos os dados ao objeto)
            $newPost->user = $userDao->findById($post_item['id_user']);

            // Pegar informações de like
            $newPost->likeCount = 0;
            $newPost->liked = false;

            // Pegar informações de comentários
            $newPost->comments = [];

            $posts[] = $newPost;
        }

        return $posts;
    }






}