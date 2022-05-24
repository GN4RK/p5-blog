<?php
require_once("src/controller/Controller.php");
// loading classes
require_once('src/model/PostManager.php');
require_once('src/model/CommentManager.php');

class FrontendController extends Controller
{
    static function home() {
        $loader = new \Twig\Loader\FilesystemLoader('src/view');
        $twig = new \Twig\Environment($loader);
    
        $template = $twig->load('home.twig');
        echo $template->render([
            'baseFolder' => BASEFOLDER,
            'navigation' => self::NAV,
        ]);
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

        $loader = new \Twig\Loader\FilesystemLoader('src/view');
        $twig = new \Twig\Environment($loader);
        
        $template = $twig->load('listPostsView.twig');
        echo $template->render([
            'baseFolder' => BASEFOLDER,
            'navigation' => self::NAV,
            'posts' => $posts
        ]);
    }
    
    static function post($id) {
        $postManager = new PostManager();
        $commentManager = new CommentManager();
    
        $loader = new \Twig\Loader\FilesystemLoader('src/view');
        $twig = new \Twig\Environment($loader);
    
        $post = $postManager->getPost($id);
        $comments = $commentManager->getComments($id);
    
        //TODO if id not found, display error
        if (!$post) {
            self::error404();
        } else {    
            $template = $twig->load('postView.twig');
            echo $template->render([
                'baseFolder' => BASEFOLDER,
                'navigation' => self::NAV,
                'post' => $post,
                'comments' => $comments
            ]);
        }
    }

    static function register() {
    
    }

    static function error404() {
        $loader = new \Twig\Loader\FilesystemLoader('src/view');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('error404.twig');
        echo $template->render([
            'baseFolder' => BASEFOLDER,
            'navigation' => self::NAV
        ]);
    }
}