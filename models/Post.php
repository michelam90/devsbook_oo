<?php 
class Post {
    public $id;
    public $id_user;
    public $type; // text or photo
    public $created_at;
    public $body;
    public $page;
}

interface PostDAO {
    public function insert(Post $p);
    public function getUserFeed($id_user, $page);
    public function getHomeFeed($id_user);
    public function getPhotosFrom($id_user);
    public function delete($id, $id_user);
}