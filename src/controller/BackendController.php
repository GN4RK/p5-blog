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
        View::renderBack('new.twig');
    }

    static function adminPost() {
        View::renderBack('post.twig');
    }

    static function adminComment() {
        View::renderBack('comment.twig');
    }
}