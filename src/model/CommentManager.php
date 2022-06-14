<?php
declare(strict_types=1);

namespace App\Model;

class CommentManager extends Manager
{
    public function getComments(int $idPost): \PDOStatement {
        $db = $this->dbConnect();
        $comments = $db->query(
            'SELECT comment.id, comment.id_user, comment.content, DATE_FORMAT(comment.date, \'%d/%m/%Y à %Hh%imin%ss\') AS date_fr, comment.status, user.first_name
            FROM comment 
            INNER JOIN user ON comment.id_user = user.id
            WHERE id_post = '. $idPost .'
            ORDER BY date DESC'
        );

        return $comments;
    }

    public function postComment(int $idPost, int $idUser, string $comment): bool {
        $db = $this->dbConnect();
        $comments = $db->prepare(
            'INSERT INTO comment(id_post, id_user, content, date, status) VALUES(?, ?, ?, NOW(), "pending")'
        );
        $affectedLines = $comments->execute(array($idPost, $idUser, $comment));

        return $affectedLines;
    }

    public function validateComment(int $idComment): bool {
        $db = $this->dbConnect();
        $comments = $db->prepare(
            'UPDATE comment
            SET status = "public"
            WHERE id = ?'
        );
        $affectedLines = $comments->execute(array($idComment));

        return $affectedLines;
    }

    public function unValidateComment(int $idComment): bool {
        $db = $this->dbConnect();
        $comments = $db->prepare(
            'UPDATE comment
            SET status = "pending"
            WHERE id = ?'
        );
        $affectedLines = $comments->execute(array($idComment));

        return $affectedLines;
    }

    public function getIdPost(int $idComment): int {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT id_post
            FROM comment
            WHERE id = ?'
        );
        $req->execute(array($idComment));
        $comment = $req->fetch();

        return (int)$comment['id_post'];
    }

}