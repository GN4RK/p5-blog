<?php
session_start();

require('src/controller/FrontendController.php');
require('src/controller/BackendController.php');
require_once('vendor/autoload.php'); // Twig
require_once('src/controller/Route.php'); // Include router class

// loading config from JSON file 
$strJsonFileContents = file_get_contents("config.json");
$config = json_decode($strJsonFileContents, true);

// constants
define("BASEFOLDER", "/".$config["baseFolder"]."/");
define("BASEURL", $config["baseURL"].BASEFOLDER);

// ***************************
// * Loading Frontend routes *
// ***************************

// Home Page
Route::add('/', function(){
    FrontendController::home();
}, 'get');
Route::add('/', function(){
    if (FrontendController::sendMail()) {

        FrontendController::home("ok");
    } else {
        FrontendController::home("error");
    }
}, 'post');

// Blog page
Route::add('/blog', function(){
    FrontendController::blog();
});

// One particular blog post
Route::add('/blog/([0-9]*)', function($id){
    FrontendController::blog($id);
}, 'get');
Route::add('/blog/([0-9]*)', function($id){
    FrontendController::blog($id);
}, 'post');

// Register page
Route::add('/enregistrement', function(){
    FrontendController::register();
}, 'get');
Route::add('/enregistrement', function(){
    $registerCheck = FrontendController::registerCheck();
    if ($registerCheck == "user created") {
        FrontendController::sendVerificationMail($_POST['email']);
    }
    FrontendController::register($registerCheck);
}, 'post');

// Legal page
Route::add('/mentions-legales', function(){
    FrontendController::legal();
});

// login page
Route::add('/login', function(){
    FrontendController::login();
}, 'get');
Route::add('/login', function(){
    if (FrontendController::loginCheck()) {
        header('Location: '. BASEURL);
    } else {
        FrontendController::login();
    }
}, 'post');

// logout page
Route::add('/deconnexion', function(){
    FrontendController::logout();
    header('Location: '. BASEURL);
});

// 404
Route::pathNotFound(function(){
    FrontendController::error404();
});

// email validation page
Route::add('/validation', function(){
    FrontendController::validation();
});

// profile page
Route::add('/profil', function(){
    FrontendController::profile();
}, 'get');
// profile page
Route::add('/profil', function(){
    FrontendController::profile();
}, 'post');



// **************************
// * Loading Backend routes *
// ***************************

// admin page
Route::add('/admin', function(){
    BackendController::admin();
});

// new post page
Route::add('/admin/new', function(){
    BackendController::adminNew();
}, 'get');
Route::add('/admin/new', function(){
    BackendController::adminNew();
}, 'post');

// users moderation page
Route::add('/admin/user', function(){
    BackendController::adminUser();
}, 'get');
Route::add('/admin/user', function(){
    BackendController::adminUser();
}, 'post');

// blog posts moderation page
Route::add('/admin/post', function(){
    BackendController::adminPost();
});

// edit one blog post
Route::add('/admin/post/([0-9]*)', function($id){
    BackendController::editPost($id);
}, 'get');
Route::add('/admin/post/([0-9]*)', function($id){
    BackendController::editPost($id);
}, 'post');

// delete one blog post
Route::add('/admin/post/([0-9]*)/delete', function($id){
    BackendController::deletePost($id);
});

// comment moderation page
Route::add('/admin/comment', function(){
    BackendController::adminComment();
});

// comment validation page
Route::add('/admin/comment/validate/([0-9]*)', function($idComment){
    $idPost = BackendController::validateComment($idComment);
    header('Location: '. BASEURL ."blog/$idPost");
});

// comment hiding page
Route::add('/admin/comment/hide/([0-9]*)', function($idComment){
    $idPost = BackendController::hideComment($idComment);
    header('Location: '. BASEURL ."blog/$idPost");
});




// Post route example
Route::add('/contact-form', function(){
    echo '<form method="post"><input type="text" name="test" /><input type="submit" value="send" /></form>';
},'get');

// Post route example
Route::add('/contact-form', function(){
    echo 'Hey! The form has been sent:<br/>';
    print_r($_POST);
},'post');

// Accept only numbers as parameter. Other characters will result in a 404 error
Route::add('/foo/([0-9]*)/bar', function($var1){
    echo $var1.' is a great number!';
});

Route::run(BASEFOLDER);

// echo "<pre>";
// echo "********* \n";
// echo "* DEBUG * \n";
// echo "********* \n";
// echo '$_SESSION';
// var_dump($_SESSION);
// echo '$_POST';
// var_dump($_POST);
// echo '$_GET';
// var_dump($_GET);
// echo "</pre>";


