<?php
require_once("src/controller/Controller.php");
// loading classes
require_once('src/model/PostManager.php');
require_once('src/model/CommentManager.php');
require_once('src/model/UserManager.php');
require_once('src/model/View.php');

class FrontendController extends Controller
{
    static function home() {        
        View::renderFront('home.twig');        
    }

    static function blog($id = null) {
        if ($id != null) {
            self::post($id);
        } else {
            self::listPosts();
        }
    }
    
    static function listPosts() {
        $postManager = new PostManager();
        $posts = $postManager->getPosts();
        View::renderFront('listPostsView.twig', ['posts' => $posts]);
    }
    
    static function post($id) {
        $postManager = new PostManager();
        $commentManager = new CommentManager();
        $post = $postManager->getPost($id);
        $comments = $commentManager->getComments($id);
    
        // if id not found, display error
        if (!$post) {
            self::error404();
        } else {
            View::renderFront('postView.twig', [
                'post' => $post, 
                'comments' => $comments
            ]);
        }
    }

    static function register() {
        View::renderFront('register.twig');
    }

    static function login() {
        View::renderFront('login.twig');
    }

    static function loginCheck() {
        $userManager = new UserManager();

        $user = $userManager->checkUser($_POST['email'], $_POST['pass']);
        if ($user) {
            echo "OK";
            $_SESSION['user'] = $user;
        } else {
            $_SESSION['user'] = null;
        }
   
    }

    static function disconnection() {
        $_SESSION['user'] = null;
    }

    static function legal() {
        View::renderFront('legal.twig');
    }

    static function error404() {
        View::renderFront('error404.twig');
    }
}