<?php
require_once("src/controller/Controller.php");
// loading classes
require_once('src/model/PostManager.php');
require_once('src/model/CommentManager.php');

class BackendController extends Controller
{
    static function admin() {
        $loader = new \Twig\Loader\FilesystemLoader('src/view/backend');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('admin.twig');
        echo $template->render([
            'baseFolder' => BASEFOLDER,
            'navigation' => self::NAV
        ]);    
    }

    static function adminNew() {
        $loader = new \Twig\Loader\FilesystemLoader('src/view/backend');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('new.twig');
        echo $template->render([
            'baseFolder' => BASEFOLDER,
            'navigation' => self::NAV
        ]);    
    }

    static function adminPost() {
        $loader = new \Twig\Loader\FilesystemLoader('src/view/backend');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('post.twig');
        echo $template->render([
            'baseFolder' => BASEFOLDER,
            'navigation' => self::NAV
        ]);    
    }

    static function adminComment() {
        $loader = new \Twig\Loader\FilesystemLoader('src/view/backend');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('comment.twig');
        echo $template->render([
            'baseFolder' => BASEFOLDER,
            'navigation' => self::NAV
        ]);    
    }
}