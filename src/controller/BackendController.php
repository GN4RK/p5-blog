<?php
declare(strict_types=1);
require_once("src/controller/Controller.php");
// loading classes
require_once('src/model/PostManager.php');
require_once('src/model/CommentManager.php');
require_once('src/model/UserManager.php');
require_once('src/model/View.php');

class BackendController extends Controller
{
    static function admin(): void {
        View::renderBack('admin.twig');
    }

    static function adminNew(): void {

        $postStatus = "";
        if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['status'])) {
            if (!empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['status'])) {
                $postManager = new PostManager();
                $postManager->addPost($_POST['title'], $_POST['content'], $_POST['status']);
                $postStatus = "post added";
            }
        }

        View::renderBack('new.twig', ["postStatus" => $postStatus]);

    }

    static function adminPost(): void {
        View::renderBack('post.twig');
    }

    static function adminComment(): void {
        View::renderBack('comment.twig');
    }

    static function editPost(int $id) {

        $postManager = new PostManager();
        $post = $postManager->getPost($id);

        $postStatus = "";

        if ($post) {
            if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['status'])) {
                if (!empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['status'])) {
                    if (($_POST['title'] != $post['title']) || ($_POST['content'] != $post['content']) || ($_POST['status'] != $post['status'])) {
    
                        $postManager->editPost($id, $_POST['title'], $_POST['content'], $_POST['status']);
                        $postStatus = "post edited";
                        $post = $postManager->getPost($id);
    
                    }
                }
            }
        } else {
            $postStatus = "post not found";
        }
        
        
        View::renderBack('edit.twig', ["post" => $post, "postStatus" => $postStatus]);

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
        
        View::renderBack('delete.twig', ["postStatus" => $postStatus]);

    }
}