<?php

// require_once '../vendor/autoload.php';

// $loader = new \Twig\Loader\FilesystemLoader('../templates');
// // $twig = new \Twig\Environment($loader, [
// //     'cache' => '../compilation_cache',
// // ]);
// $twig = new \Twig\Environment($loader);

// $navigation = array(
//     array ('href' => "index.php", 'caption' => 'home'),
//     array ('href' => "index.php", 'caption' => 'blog'),
//     array ('href' => "index.php", 'caption' => 'register'),
// );

// $template = $twig->load('home.twig');
// echo $template->render([
//     'navigation' => $navigation,
//     'a_variable' => 5
// ]);

require('src/controller/frontend.php');
require_once 'vendor/autoload.php'; // Twig

try {
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'listPosts') {
            listPosts();
        }
        elseif ($_GET['action'] == 'post') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                post();
            }
            else {
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
        elseif ($_GET['action'] == 'addComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                    addComment($_GET['id'], $_POST['author'], $_POST['comment']);
                }
                else {
                    throw new Exception('Tous les champs ne sont pas remplis !');
                }
            }
            else {
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
    }
    else {
        home();
    }
}
catch(Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
