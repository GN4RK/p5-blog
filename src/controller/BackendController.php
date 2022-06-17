<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\View;
use App\Model\PostManager;
use App\Model\UserManager;
use App\Model\CommentManager;
use App\Model\Session;
use App\Model\PostSG;

/**
 * BackendController
 */
class BackendController 
{
    
    /**
     * display admin page
     *
     * @return void
     */
    public static function admin(): void 
    {
        $view = new View();
        $view->renderBack('admin.twig', ["title" => "Administration"]);
    }
    
    /**
     * display adminNew page for posting a new post into the blog
     *
     * @return void
     */
    public static function adminNew(): void 
    {
        $view = new View();
        $PSG = new PostSG();
        $session = new Session();
        $postStatus = "";
        if (
            !empty($PSG->get('title')) 
            && !empty($PSG->get('header')) 
            && !empty($PSG->get('content')) 
            && !empty($PSG->get('status'))
        ) {
            $postManager = new PostManager();
            $postManager->addPost(
                (int) $session->get("user")["id"], 
                $PSG->get('title'), $PSG->get('header'), 
                $PSG->get('content'), $PSG->get('status')
            );
            $postStatus = "post added";
        }

        $view->renderBack('new.twig', [
            "title" => "Administration - Nouveau billet", 
            "postStatus" => $postStatus
        ]);
    }
    
    /**
     * display adminUser page to view the users list of the app
     *
     * @return void
     */
    public static function adminUser(): void 
    {
        $view = new View();
        $PSG = new PostSG();
        $userManager = new UserManager();
        $users = $userManager->getUsers();

        $session = new Session();
        $token = $session->get('security_token');
        if (empty($token)) {
            $token = md5(openssl_random_pseudo_bytes(10));
            $session->set('security_token', $token);
        }

        

        if (!empty($PSG->getAll())) {
            if ($token != $PSG->get('security_token')) {
                return;
            }
            foreach($PSG->getAll() as $k => $v) {
                if (str_contains($k, 'role')) {
                    $idUser = (int) substr($k, 5);
                    $userManager->setRole($idUser, $v);
                }
            }
            
            // generating a new token
            $token = md5(openssl_random_pseudo_bytes(10));
            $session->set('security_token', $token);

            // refreshing users list
            $users = $userManager->getUsers();

        }

        $view->renderBack('users.twig', [
            "title" => "Administration - Utilisateurs", 
            "users" => $users, 
            "token" => $token
        ]);
    }
    
    /**
     * Validate a comment
     *
     * @param  int $idComment
     * @return int return the post id to stay on its page
     */
    public static function validateComment(int $idComment): int 
    {
        $commentManager = new CommentManager();
        $commentManager->validateComment($idComment);
        return $commentManager->getIdPost($idComment);
    }
    
    /**
     * Hide a comment
     *
     * @param  int $idComment
     * @return int return the post id to stay on its page
     */
    public static function hideComment(int $idComment): int 
    {
        $commentManager = new CommentManager();
        $commentManager->unValidateComment($idComment);
        return $commentManager->getIdPost($idComment);
    }
    
    /**
     * Display post edit page
     *
     * @param  int $id
     * @return void
     */
    public static function editPost(int $id): void 
    {
        $view = new View();
        $userManager = new UserManager();
        $authors = $userManager->getUsers();

        $postManager = new PostManager();
        $post = $postManager->getPost($id);
        $postStatus = "";

        if (!$post) {
            $postStatus = "post not found";
            $view->renderBack('edit.twig', [
                "title" => "Administration - Modification de billet", 
                "post" => $post, 
                "postStatus" => $postStatus
            ]);
            return;
        }

        $session = new Session();
        $token = $session->get('security_token');
        if (empty($token)) {
            $token = md5(openssl_random_pseudo_bytes(10));
            $session->set('security_token', $token);
        }
        
        $PSG = new PostSG();
        $notEmpty = 
            !empty($PSG->get('title')) 
            && !empty($PSG->get('id_user'))
            && !empty($PSG->get('header')) 
            && !empty($PSG->get('content')) 
            && !empty($PSG->get('status'))
            && !empty($PSG->get('security_token'));

        if ($notEmpty) {
            if ($token != $PSG->get('security_token')) {
                return;
            }
            $modified = 
                ($PSG->get('title') != $post['title']) || 
                ($PSG->get('id_user') != $post['id_user']) || 
                ($PSG->get('header') != $post['header']) || 
                ($PSG->get('content') != $post['content']) || 
                ($PSG->get('status') != $post['status']);

            if ($modified) {
                $postManager->editPost(
                    $id, 
                    $PSG->get('title'), 
                    (int) $PSG->get('id_user'), 
                    $PSG->get('header'), 
                    $PSG->get('content'), 
                    $PSG->get('status')
                );
                $postStatus = "post edited";
                $post = $postManager->getPost($id);

                // generating a new token
                $token = md5(openssl_random_pseudo_bytes(10));
                $session->set('security_token', $token);
            }
        }
        
        $view->renderBack('edit.twig', [
            "title" => "Administration - Modification de billet", 
            "post" => $post, 
            "postStatus" => $postStatus,
            "token" => $token,
            "authors" => $authors
        ]);
    }
    
    /**
     * Display delete post page
     *
     * @param  int $id
     * @return void
     */
    public static function deletePost(int $id): void 
    {
        $view = new View();
        $postManager = new PostManager();
        $post = $postManager->getPost($id);

        $postStatus = "";

        if ($post) {
            $postManager->deletePost($id);
            $postStatus = "post deleted";
        } else {
            $postStatus = "post not found";
        }
        
        $view->renderBack('deletePost.twig', [
            "title" => "Administration - Suppression de billet", 
            "postStatus" => $postStatus
        ]);
    }

    /**
     * Display delete user page
     *
     * @param  int $id
     * @return void
     */
    public static function deleteUser(int $id): void 
    {

        // preventing super admin being deleted
        if ($id == 1) {
            return;
        }

        $view = new View();
        $userManager = new UserManager();
        $user = $userManager->getUserById($id);

        $userStatus = "";

        if ($user) {
            $userManager->deleteUser($id);
            $userStatus = "user deleted";
        } else {
            $userStatus = "user not found";
        }
        
        $view->renderBack('deleteUser.twig', [
            "title" => "Administration - Suppression d'utilisateur", 
            "userStatus" => $userStatus
        ]);
    }
}