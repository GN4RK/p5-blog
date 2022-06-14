<?php
session_start();

// loading config from JSON file 
$strJsonFileContents = file_get_contents("config.json");
$config = json_decode($strJsonFileContents, true);

// constants
define("BASEFOLDER", "/".$config["baseFolder"]."/");
define("BASEURL", $config["baseURL"].BASEFOLDER);

// autoload
require_once 'vendor/autoload.php';

use App\Controller\Route;
use App\Controller\FrontendController;
use App\Controller\BackendController;
use App\Model\PostSG;
use App\Model\Session;

$route = new Route();

// ***************************
// * Loading Frontend routes *
// ***************************

// Home Page
$route->add('/', function(){
    FrontendController::home();
}, 'get');
$route->add('/', function(){
    if (FrontendController::sendMail()) {
        FrontendController::home("ok");
    } else {
        FrontendController::home("error");
    }
}, 'post');

// Blog page
$route->add('/blog', function(){
    FrontendController::blog();
});

// One particular blog post
$route->add('/blog/([0-9]*)', function($id){
    FrontendController::blog($id);
}, 'get');
$route->add('/blog/([0-9]*)', function($id){
    FrontendController::blog($id);
}, 'post');

// Register page
$route->add('/enregistrement', function(){
    FrontendController::register();
}, 'get');
$route->add('/enregistrement', function(){
    $registerCheck = FrontendController::registerCheck();
    if ($registerCheck == "user created") {
        FrontendController::sendVerificationMail(PostSG::get('email'));
    }
    FrontendController::register($registerCheck);
}, 'post');

// Legal page
$route->add('/mentions-legales', function(){
    FrontendController::legal();
});

// login page
$route->add('/login', function(){
    FrontendController::login();
}, 'get');
$route->add('/login', function(){
    if (FrontendController::loginCheck()) {
        header('Location: '. BASEURL);
    } else {
        FrontendController::login();
    }
}, 'post');

// logout page
$route->add('/deconnexion', function(){
    FrontendController::logout();
    header('Location: '. BASEURL);
});

// 404
$route->pathNotFound(function(){
    FrontendController::error404();
});

// email validation page
$route->add('/validation', function(){
    FrontendController::validation();
});

// profile page
$route->add('/profil', function(){
    FrontendController::profile();
}, 'get');
// profile page
$route->add('/profil', function(){
    FrontendController::profile();
}, 'post');



// **************************
// * Loading Backend routes *
// ***************************

if (Session::getRole() == 'admin') {
    
    // admin page
    $route->add('/admin', function(){
        BackendController::admin();
    });

    // new post page
    $route->add('/admin/new', function(){
        BackendController::adminNew();
    }, 'get');
    $route->add('/admin/new', function(){
        BackendController::adminNew();
    }, 'post');

    // users moderation page
    $route->add('/admin/user', function(){
        BackendController::adminUser();
    }, 'get');
    $route->add('/admin/user', function(){
        BackendController::adminUser();
    }, 'post');

    // edit one blog post
    $route->add('/admin/post/([0-9]*)', function($id){
        BackendController::editPost($id);
    }, 'get');
    $route->add('/admin/post/([0-9]*)', function($id){
        BackendController::editPost($id);
    }, 'post');

    // delete one blog post
    $route->add('/admin/post/([0-9]*)/delete', function($id){
        BackendController::deletePost($id);
    });

    // comment validation page
    $route->add('/admin/comment/validate/([0-9]*)', function($idComment){
        $idPost = BackendController::validateComment($idComment);
        header('Location: '. BASEURL ."blog/$idPost");
    });

    // comment hiding page
    $route->add('/admin/comment/hide/([0-9]*)', function($idComment){
        $idPost = BackendController::hideComment($idComment);
        header('Location: '. BASEURL ."blog/$idPost");
    });
}

$route->run(BASEFOLDER);
