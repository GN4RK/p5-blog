<?php

require('src/controller/frontend.php');
require_once 'vendor/autoload.php'; // Twig
require_once('src/controller/Route.php'); // Include router class

// Home Page
Route::add('/', function(){
    home();
});

// Blog page
Route::add('/blog',function(){
    blog();
});

// Displaying one particular blog post
Route::add('/blog/([0-9]*)',function($id){
    blog($id);
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

Route::run('/P5-blog');

