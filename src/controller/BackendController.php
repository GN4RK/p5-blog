<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\View;
use App\Model\PostManager;
use App\Model\UserManager;
use App\Model\CommentManager;
use App\Model\Session;
use App\Model\PostSG;

class BackendController extends Controller
{
    static function admin(): void {
        View::renderBack('admin.twig', ["title" => "Administration"]);
    }

    static function adminNew(): void {

        $postStatus = "";
        if (!empty(PostSG::get('title')) && !empty(PostSG::get('header')) && !empty(PostSG::get('content')) && !empty(PostSG::get('status'))) {
            $postManager = new PostManager();
            $postManager->addPost((int)Session::get("user")["id"], PostSG::get('title'), PostSG::get('header'), PostSG::get('content'), PostSG::get('status'));
            $postStatus = "post added";
        }

        View::renderBack('new.twig', ["title" => "Administration - Nouveau billet", "postStatus" => $postStatus]);

    }

    static function adminUser(): void {
        $userManager = new UserManager();
        $users = $userManager->getUsers();

        if (!empty(PostSG::getAll())) {
            foreach(PostSG::getAll() as $k => $v) {
                $idUser = (int)substr($k, 5);
                $userManager->setRole($idUser, $v);
            }

            $users = $userManager->getUsers();

        }

        View::renderBack('users.twig', ["title" => "Administration - Utilisateurs", "users" => $users]);
    }

    static function adminPost(): void {
        View::renderBack('post.twig', ["title" => "Administration - Billets"]);
    }

    static function adminComment(): void {
        View::renderBack('comment.twig', ["title" => "Administration - Commentaires"]);
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

    static function editPost(int $id) {

        $postManager = new PostManager();
        $post = $postManager->getPost($id);

        $postStatus = "";


        if ($post) {
            $notEmpty = !empty(PostSG::get('title')) && !empty(PostSG::get('header')) && !empty(PostSG::get('content')) && !empty(PostSG::get('status'));
            if ($notEmpty) {

                $modified = 
                    (PostSG::get('title') != $post['title']) || 
                    (PostSG::get('header') != $post['header']) || 
                    (PostSG::get('content') != $post['content']) || 
                    (PostSG::get('status') != $post['status']);

                if ($modified) {
                    $postManager->editPost($id, PostSG::get('title'), PostSG::get('header'), PostSG::get('content'), PostSG::get('status'));
                    $postStatus = "post edited";
                    $post = $postManager->getPost($id);
                }
            }
        } else {
            $postStatus = "post not found";
        }
        
        
        View::renderBack('edit.twig', ["title" => "Administration - Modification de billet", "post" => $post, "postStatus" => $postStatus]);

    }

    static function deletePost(int $id): void {

        $postManager = new PostManager();
        $post = $postManager->getPost($id);

        $postStatus = "";

        if ($post) {
            $postManager->deletePost($id);
            $postStatus = "post deleted";
        } else {
            $postStatus = "post not found";
        }
        
        View::renderBack('delete.twig', ["title" => "Administration - Suppression de billet", "postStatus" => $postStatus]);

    }
}