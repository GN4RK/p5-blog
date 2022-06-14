<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\View;
use App\Model\PostManager;
use App\Model\UserManager;
use App\Model\CommentManager;
use App\Model\Session;
use App\Model\PostSG;

class BackendController {

    static function admin(): void {
        $view = new View();
        $view->renderBack('admin.twig', ["title" => "Administration"]);
    }

    static function adminNew(): void {

        $view = new View();
        $PSG = new PostSG();
        $session = new Session();
        $postStatus = "";
        if (!empty($PSG->get('title')) && !empty($PSG->get('header')) && !empty($PSG->get('content')) && !empty($PSG->get('status'))) {
            $postManager = new PostManager();
            $postManager->addPost((int)$session->get("user")["id"], $PSG->get('title'), $PSG->get('header'), $PSG->get('content'), $PSG->get('status'));
            $postStatus = "post added";
        }

        $view->renderBack('new.twig', ["title" => "Administration - Nouveau billet", "postStatus" => $postStatus]);

    }

    static function adminUser(): void {
        $view = new View();
        $PSG = new PostSG();
        $userManager = new UserManager();
        $users = $userManager->getUsers();

        if (!empty($PSG->getAll())) {
            foreach($PSG->getAll() as $k => $v) {
                $idUser = (int)substr($k, 5);
                $userManager->setRole($idUser, $v);
            }

            $users = $userManager->getUsers();

        }

        $view->renderBack('users.twig', ["title" => "Administration - Utilisateurs", "users" => $users]);
    }

    static function adminPost(): void {
        $view = new View();
        $view->renderBack('post.twig', ["title" => "Administration - Billets"]);
    }

    static function adminComment(): void {
        $view = new View();
        $view->renderBack('comment.twig', ["title" => "Administration - Commentaires"]);
    }

    static function validateComment(int $idComment): int {
        $commentManager = new CommentManager();
        $commentManager->validateComment($idComment);
        return $commentManager->getIdPost($idComment);
    }

    static function hideComment(int $idComment): int {
        $commentManager = new CommentManager();
        $commentManager->unValidateComment($idComment);
        return $commentManager->getIdPost($idComment);
    }

    static function editPost(int $id): void {

        $view = new View();
        $postManager = new PostManager();
        $post = $postManager->getPost($id);
        $postStatus = "";

        if (!$post) {
            $postStatus = "post not found";
            $view->renderBack('edit.twig', ["title" => "Administration - Modification de billet", "post" => $post, "postStatus" => $postStatus]);
            return;
        }

        $PSG = new PostSG();

        $notEmpty = !empty($PSG->get('title')) && !empty($PSG->get('header')) && !empty($PSG->get('content')) && !empty($PSG->get('status'));
        if ($notEmpty) {

            $modified = 
                ($PSG->get('title') != $post['title']) || 
                ($PSG->get('header') != $post['header']) || 
                ($PSG->get('content') != $post['content']) || 
                ($PSG->get('status') != $post['status']);

            if ($modified) {
                $postManager->editPost($id, $PSG->get('title'), $PSG->get('header'), $PSG->get('content'), $PSG->get('status'));
                $postStatus = "post edited";
                $post = $postManager->getPost($id);
            }
        }
        
        $view->renderBack('edit.twig', ["title" => "Administration - Modification de billet", "post" => $post, "postStatus" => $postStatus]);
    }

    static function deletePost(int $id): void {

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
        
        $view->renderBack('delete.twig', ["title" => "Administration - Suppression de billet", "postStatus" => $postStatus]);

    }
}