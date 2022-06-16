<?php
declare(strict_types=1);

namespace App\Model;

/**
 * PostManager class
 */
class PostManager extends Manager {
    
    /**
     * Return all posts from the database
     *
     * @return PDOStatement
     */
    public function getPosts(int $page): \PDOStatement 
    {
        $db = $this->dbConnect();
        $req = $db->query(
            'SELECT id, 
                    title, 
                    header, 
                    content, 
                    DATE_FORMAT(publication_date, \'%d/%m/%Y à %Hh%i\') AS publication_date_fr,
                    DATE_FORMAT(last_update, \'%d/%m/%Y à %Hh%i\') AS last_update_fr, 
                    status 
            FROM post 
            ORDER BY publication_date 
            DESC 
            LIMIT '. $page*5 - 5 .', 5'
        );

        return $req;
    }
    
    /**
     * Return the number of pages
     *
     * @return int
     */
    public function getPageAmount(): int 
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT COUNT(*) FROM post;');
        $req->execute();
        $nbPost = $req->fetch()[0];
        return (int) ceil($nbPost/5);
    }
    
    /**
     * Return one post from the database
     *
     * @param  int $idPost
     * @return array|false return false if not found
     */
    public function getPost(int $idPost): array|false 
    {
        $db = $this->dbConnect();
        $req = $db->prepare(
            'SELECT id, 
                    id_user, 
                    title, 
                    header, 
                    content, 
                    DATE_FORMAT(publication_date, \'%d/%m/%Y à %Hh%i\') AS publication_date_fr, 
                    DATE_FORMAT(last_update, \'%d/%m/%Y à %Hh%i\') AS last_update_fr, 
                    status
            FROM post
            WHERE id = ?'
        );
        $req->execute(array($idPost));
        $post = $req->fetch();

        return $post;
    }
    
    /**
     * Add a post in the database
     *
     * @param  int $idAuthor
     * @param  string $title
     * @param  string $header
     * @param  string $content
     * @param  string $status
     * @return bool
     */
    public function addPost(
        int $idAuthor, 
        string $title, 
        string $header, 
        string $content, 
        string $status
    ): bool {
        $db = $this->dbConnect();
        $post = $db->prepare(
            'INSERT INTO post(id_user, title, header, content, status) 
            VALUES(?, ?, ?, ?, ?)'
        );
        $affectedLines = $post->execute(array(
            $idAuthor,
            $title, 
            $header, 
            $content, 
            $status
        ));

        return $affectedLines;
    }
    
    /**
     * Edit a post from the database
     *
     * @param  int $idPost
     * @param  string $title
     * @param  string $header
     * @param  string $content
     * @param  string $status
     * @return bool
     */
    public function editPost(
        int $idPost, 
        string $title, 
        string $header, 
        string $content, 
        string $status
    ): bool {
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
    
    /**
     * Delete a post from the database
     *
     * @param  int $idPost
     * @return bool
     */
    public function deletePost(int $idPost): bool 
    {
        $db = $this->dbConnect();
        $post = $db->prepare(
            'DELETE FROM post WHERE id = ?'
        );
        $affectedLines = $post->execute(array($idPost));

        return $affectedLines;
    }
}