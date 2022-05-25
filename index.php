<?php

require('src/controller/FrontendController.php');
require_once 'vendor/autoload.php'; // Twig
require_once('src/controller/Route.php'); // Include router class

// loading config from JSON file 
$strJsonFileContents = file_get_contents("config.json");
$config = json_decode($strJsonFileContents, true);

// constants
define("BASEFOLDER", "/".$config["baseFolder"]."/");

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

Route::pathNotFound(function(){
    FrontendController::error404();
});

Route::run(BASEFOLDER);

