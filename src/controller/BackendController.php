<?php
require_once("src/controller/Controller.php");
// loading classes
require_once('src/model/PostManager.php');
require_once('src/model/CommentManager.php');
require_once('src/model/UserManager.php');
require_once('src/model/View.php');

class BackendController extends Controller
{
    static function admin() {
        View::renderBack('admin.twig');
    }

    static function adminNew() {

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

    static function adminPost() {
        View::renderBack('post.twig');
    }

    static function adminComment() {
        View::renderBack('comment.twig');
    }
}