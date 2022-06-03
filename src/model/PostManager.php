<?php
declare(strict_types=1);
require_once("src/model/Manager.php");

class PostManager extends Manager
{
    public function getPosts(): PDOStatement {
        $db = $this->dbConnect();
        $req = $db->query(
            'SELECT id, title, header, content, DATE_FORMAT(last_update, \'%d/%m/%Y à %Hh%imin%ss\') AS last_update_fr 
            FROM post 
            ORDER BY last_update 
            DESC LIMIT 0, 5'
        );

        return $req;
    }

    public function getPost(int $idPost): array {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT id, title, content, DATE_FORMAT(last_update, \'%d/%m/%Y à %Hh%imin%ss\') AS last_update_fr, status 
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
        $user = $db->prepare(
            'INSERT INTO post(title, header, content, status) VALUES(?, ?, ?, ?)'
        );
        $affectedLines = $user->execute(array($title, $header, $content, $status));

        return $affectedLines;
    }

    public function editPost(int $idPost, string $title, string $content, string $status): bool {
        $header = substr($content, 0, 50);
        $db = $this->dbConnect();
        $user = $db->prepare(
            'UPDATE post 
            SET title = ?,
            header = ?,
            content = ?,
            last_update = CURRENT_TIMESTAMP,
            status = ?
            WHERE id = ?'
        );
        $affectedLines = $user->execute(array($title, $header, $content, $status, $idPost));

        return $affectedLines;
    }

}