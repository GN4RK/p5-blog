<?php
session_start();

require('src/controller/FrontendController.php');
require('src/controller/BackendController.php');
require_once 'vendor/autoload.php'; // Twig
require_once('src/controller/Route.php'); // Include router class

// loading config from JSON file 
$strJsonFileContents = file_get_contents("config.json");
$config = json_decode($strJsonFileContents, true);

// constants
define("BASEFOLDER", "/".$config["baseFolder"]."/");

// ***************************
// * Loading Frontend routes *
// ***************************

// Home Page
Route::add('/', function(){
    FrontendController::home();
});

// Blog page
Route::add('/blog',function(){
    FrontendController::blog();
});

// One particular blog post
Route::add('/blog/([0-9]*)',function($id){
    FrontendController::blog($id);
});

// Register page
Route::add('/enregistrement',function(){
    FrontendController::register();
});

// Legal page
Route::add('/mentions-legales',function(){
    FrontendController::legal();
});

// login page
Route::add('/login',function(){
    FrontendController::login();
}, 'get');
Route::add('/login',function(){
    FrontendController::loginCheck();
    FrontendController::home();
}, 'post');

// disconnection page
Route::add('/disconnection',function(){
    FrontendController::disconnection();
    FrontendController::home();
});

// 404
Route::pathNotFound(function(){
    FrontendController::error404();
});



// **************************
// * Loading Backend routes *
// ***************************

// admin page
Route::add('/admin',function(){
    BackendController::admin();
});

// new post page
Route::add('/admin/new',function(){
    BackendController::adminNew();
}, 'get');
Route::add('/admin/new',function(){
    BackendController::adminNew();
}, 'post');

// blog posts moderation page
Route::add('/admin/post',function(){
    BackendController::adminPost();
});

// comment moderation page
Route::add('/admin/comment',function(){
    BackendController::adminComment();
});




// Post route example
Route::add('/contact-form',function(){
    echo '<form method="post"><input type="text" name="test" /><input type="submit" value="send" /></form>';
},'get');

// Post route example
Route::add('/contact-form',function(){
    echo 'Hey! The form has been sent:<br/>';
    print_r($_POST);
},'post');

// Accept only numbers as parameter. Other characters will result in a 404 error
Route::add('/foo/([0-9]*)/bar',function($var1){
    echo $var1.' is a great number!';
});

Route::run(BASEFOLDER);

echo "<pre>";
echo "********* \n";
echo "* DEBUG * \n";
echo "********* \n";
echo '$_SESSION';
var_dump($_SESSION);
echo '$_POST';
var_dump($_POST);
echo '$_GET';
var_dump($_GET);
echo "</pre>";


