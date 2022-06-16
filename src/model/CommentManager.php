<?php
declare(strict_types=1);

namespace App\Model;

/**
 * CommentManager
 */
class CommentManager extends Manager
{        
    /**
     * Return all the comments posted on a blog post or false from the database
     *
     * @param  int $idPost
     * @return PDOStatement
     */
    public function getComments(int $idPost): \PDOStatement 
    {
        $db = $this->dbConnect();
        $comments = $db->query(
            'SELECT comment.id, 
                    comment.id_user, 
                    comment.content, 
                    DATE_FORMAT(comment.date, \'%d/%m/%Y à %Hh%imin%ss\') AS date_fr,
                    comment.status, 
                    user.first_name
            FROM comment 
            INNER JOIN user ON comment.id_user = user.id
            WHERE id_post = '. $idPost .'
            ORDER BY date DESC'
        );

        return $comments;
    }
    
    /**
     * Add a comment into the databse
     *
     * @param  int $idPost post id
     * @param  int $idUser user id
     * @param  string $comment
     * @return bool true if no error
     */
    public function postComment(int $idPost, int $idUser, string $comment): bool
    {
        $db = $this->dbConnect();
        $comments = $db->prepare(
            'INSERT INTO comment(id_post, id_user, content, date, status) 
            VALUES(?, ?, ?, NOW(), "pending")'
        );
        $affectedLines = $comments->execute(array(
            $idPost, 
            $idUser, 
            htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'), 
        ));

        return $affectedLines;
    }
    
    /**
     * Validate a comment updating its status
     *
     * @param  int $idComment
     * @return bool
     */
    public function validateComment(int $idComment): bool 
    {
        $db = $this->dbConnect();
        $comments = $db->prepare(
            'UPDATE comment
            SET status = "public"
            WHERE id = ?'
        );
        $affectedLines = $comments->execute(array($idComment));

        return $affectedLines;
    }
    
    /**
     * Hide a comment updating its status
     *
     * @param  int $idComment
     * @return bool
     */
    public function unValidateComment(int $idComment): bool
    {
        $db = $this->dbConnect();
        $comments = $db->prepare(
            'UPDATE comment
            SET status = "pending"
            WHERE id = ?'
        );
        $affectedLines = $comments->execute(array($idComment));

        return $affectedLines;
    }
    
    /**
     * Return the post id from a comment id.
     *
     * @param  int $idComment
     * @return int post id
     */
    public function getIdPost(int $idComment): int 
    {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT id_post
            FROM comment
            WHERE id = ?'
        );
        $req->execute(array($idComment));
        $comment = $req->fetch();

        return (int) $comment['id_post'];
    }

}