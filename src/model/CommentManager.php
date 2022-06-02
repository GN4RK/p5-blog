<?php
require_once("src/model/Manager.php");

class CommentManager extends Manager
{
    public function getComments($idPost) {
        $db = $this->dbConnect();
        $comments = $db->query(
            'SELECT comment.id_user, comment.content, DATE_FORMAT(comment.date, \'%d/%m/%Y à %Hh%imin%ss\') AS date_fr, user.first_name
            FROM comment 
            INNER JOIN user ON comment.id_user = user.id
            WHERE id_post = '. $idPost .'
            ORDER BY date DESC'
        );

        return $comments;
    }

    public function postComment($idPost, $idUser, $comment) {
        $db = $this->dbConnect();
        $comments = $db->prepare(
            'INSERT INTO comment(id_post, id_user, content, date) VALUES(?, ?, ?, NOW())'
        );
        $affectedLines = $comments->execute(array($idPost, $idUser, $comment));

        return $affectedLines;
    }

}