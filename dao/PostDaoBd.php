<?php 
require_once 'models/Post.php';
require_once 'dao/UserRelationDaoBd.php';
require_once 'dao/UserDaoBd.php';
require_once 'dao/PostLikeDaoBd.php';
require_once 'dao/PostCommentDaoBd.php';

class PostDaoBd implements PostDAO {
    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    // Inserindo post
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

    // Deletando post
    public function delete($id, $id_user) {

        $sql = "DELETE FROM posts WHERE id = :id AND id_user = :id_user";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();

    }

    // Para usar na time line do perfil do usuário
    public function getUserFeed($id_user, $page = 1) {

        $array = ['feed'=>[]];

        // Trabalhando a paginação
        $perPage = 3;
        $offset = ($page -1) * $perPage;
        
        // 1. Pegar os posts ordenados por data 
        $sql = "SELECT * FROM posts WHERE id_user = :id_user ORDER BY created_at DESC LIMIT $offset , $perPage";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            // 2. Transformar resultado em objetos e pegar os posts
            $array['feed'] = $this->postListToObject($data, $id_user);            
        }

        // 3. Pegar o total de posts
        $sql = "SELECT COUNT(*) AS c FROM posts WHERE id_user = :id_user";
        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();
        $totalData = $sql->fetch();
        $total = $totalData['c'];

        // Contando quantidade páginas (Conforme núemro de post dividido pela quantidade exibida por pagina)
        $array['pages'] = ceil($total / $perPage);

        // Pegando a pagina atual para exibir a pagina que está ativa no momento na paginação
        $array['correntPage'] = $page;

        return $array;
    }

    // Para usar na time line principal
    public function getHomeFeed($id_user, $page = 1) {

        $array = ['feed'=>[]];

        // Trabalhando a paginação
        $perPage = 3;    
        $offset = ($page -1) * $perPage;

        // 1. Lista dos usuários que eu sigo
        $urDao = new UserRelationDaoBd($this->pdo);
        $userList = $urDao->getFollowing($id_user);
        $userList[] = $id_user;

        // 2. Pegar os posts ordenados por data 
        $sql = "SELECT * FROM posts WHERE id_user IN (".implode(',', $userList).") 
        ORDER BY created_at DESC LIMIT $offset , $perPage";
        $sql = $this->pdo->query($sql);

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            // 3. Transformar resultado em objetos e pegando os posts
            $array['feed'] = $this->postListToObject($data, $id_user);            
        }

        // 4. Pegar o total de posts
        $sql = "SELECT COUNT(*) AS c FROM posts WHERE id_user IN (".implode(',', $userList).")";
        $sql = $this->pdo->query($sql);
        $totalData = $sql->fetch();
        $total = $totalData['c'];

        // Contando quantidade páginas (Conforme núemro de post dividido pela quantidade exibida por pagina)
        $array['pages'] = ceil($total / $perPage);

        // Pegando a pagina atual para exibir a pagina que está ativa no momento na paginação
        $array['correntPage'] = $page;

        return $array;
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
        $posts = [];
        $userDao = new UserDaoBd($this->pdo); // Para pegar os dados do usuário que fez o post
        $postLikeDao = new PostLikeDaoBd($this->pdo); // Para pegar os dados de likes no post
        $postCommentDao = new PostCommentDaoBd($this->pdo); // Para pegar os dados de comentários
        

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
            $newPost->likeCount = $postLikeDao->getLikeCount($newPost->id); // Quantidade de liks do post
            $newPost->liked = $postLikeDao->isLiked($newPost->id, $id_user); // Marcar se o usuário logado curtiu o post

            // Pegar informações de comentários
            $newPost->comments = $postCommentDao->getComments($newPost->id);

            $posts[] = $newPost;
        }

        return $posts;
    }






}