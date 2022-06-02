<?php
require_once("src/model/Manager.php");

class PostManager extends Manager
{
    public function getPosts() {
        $db = $this->dbConnect();
        $req = $db->query(
            'SELECT id, title, header, content, DATE_FORMAT(last_update, \'%d/%m/%Y à %Hh%imin%ss\') AS last_update_fr 
            FROM post 
            ORDER BY last_update 
            DESC LIMIT 0, 5'
        );

        return $req;
    }

    public function getPost($postId) {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT id, title, content, DATE_FORMAT(last_update, \'%d/%m/%Y à %Hh%imin%ss\') AS last_update_fr 
            FROM post
            WHERE id = ?'
        );
        $req->execute(array($postId));
        $post = $req->fetch();

        return $post;
    }

    public function addPost($title, $content, $status) {
        $header = substr($content, 0, 50);
        $db = $this->dbConnect();
        $user = $db->prepare(
            'INSERT INTO post(title, header, content, status) VALUES(?, ?, ?, ?)'
        );
        $affectedLines = $user->execute(array($title, $header, $content, $status));

        return $affectedLines;
    }

}