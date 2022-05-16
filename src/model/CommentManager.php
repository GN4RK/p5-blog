<?php
require_once("src/model/Manager.php");

class CommentManager extends Manager
{
    public function getComments($idPost)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare(
            'SELECT id, id_user, content, DATE_FORMAT(date, \'%d/%m/%Y à %Hh%imin%ss\') AS date_fr 
            FROM comment 
            WHERE id_post = ? 
            ORDER BY date DESC'
        );
        $comments->execute(array($idPost));

        return $comments;
    }

    public function postComment($idPost, $idUser, $comment)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare(
            'INSERT INTO comment(id_post, id_user, content, date) VALUES(?, ?, ?, NOW())'
        );
        $affectedLines = $comments->execute(array($idPost, $idUser, $comment));

        return $affectedLines;
    }

}