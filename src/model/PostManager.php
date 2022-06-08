<?php
declare(strict_types=1);
require_once("src/model/Manager.php");

class PostManager extends Manager
{
    public function getPosts(): PDOStatement {
        $db = $this->dbConnect();
        $req = $db->query(
            'SELECT id, title, header, content, DATE_FORMAT(publication_date, \'%d/%m/%Y à %Hh%i\') AS publication_date_fr,
            DATE_FORMAT(last_update, \'%d/%m/%Y à %Hh%i\') AS last_update_fr, status 
            FROM post 
            ORDER BY last_update 
            DESC LIMIT 0, 5'
        );

        return $req;
    }

    public function getPost(int $idPost) {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT id, id_user, title, header, content, DATE_FORMAT(publication_date, \'%d/%m/%Y à %Hh%i\') AS publication_date_fr, 
            DATE_FORMAT(last_update, \'%d/%m/%Y à %Hh%i\') AS last_update_fr, status 
            FROM post
            WHERE id = ?'
        );
        $req->execute(array($idPost));
        $post = $req->fetch();

        return $post;
    }

    public function addPost(string $title, string $content, string $status): bool {
        $header = substr($content, 0, 50);
        $db = $this->dbConnect();
        $post = $db->prepare(
            'INSERT INTO post(title, header, content, status) VALUES(?, ?, ?, ?)'
        );
        $affectedLines = $post->execute(array($title, $header, $content, $status));

        return $affectedLines;
    }

    public function editPost(int $idPost, string $title, string $header, string $content, string $status): bool {
        $db = $this->dbConnect();
        $post = $db->prepare(
            'UPDATE post 
            SET title = ?,
            header = ?,
            content = ?,
            last_update = CURRENT_TIMESTAMP,
            status = ?
            WHERE id = ?'
        );
        $affectedLines = $post->execute(array($title, $header, $content, $status, $idPost));

        return $affectedLines;
    }

    public function deletePost(int $idPost) {

        $db = $this->dbConnect();
        $post = $db->prepare(
            'DELETE FROM post WHERE id = ?'
        );
        $affectedLines = $post->execute(array($idPost));

        return $affectedLines;

    }

}