<?php

// Chargement des classes
require_once('src/model/PostManager.php');
require_once('src/model/CommentManager.php');

function home() {
    $loader = new \Twig\Loader\FilesystemLoader('src/view');
    $twig = new \Twig\Environment($loader);

    $navigation = array(
        array ('href' => "", 'caption' => 'accueil'),
        array ('href' => "./blog", 'caption' => 'blog'),
        array ('href' => "./enregistrement", 'caption' => "s'identifier / s'enregistrer"),
    );

    $template = $twig->load('home.twig');
    echo $template->render([
        'navigation' => $navigation,
        'a_variable' => 5
    ]);
}

function blog($id = null) {
    if ($id != null) {
        post($id);
    }
    listPosts();
}

function listPosts() {
    $postManager = new PostManager();
    $posts = $postManager->getPosts();

    $loader = new \Twig\Loader\FilesystemLoader('src/view');
    $twig = new \Twig\Environment($loader);

    $navigation = array(
        array ('href' => ".", 'caption' => 'accueil'),
        array ('href' => "./blog", 'caption' => 'blog'),
        array ('href' => "./enregistrement", 'caption' => "s'identifier / s'enregistrer"),
    );

    $template = $twig->load('listPostsView.twig');
    echo $template->render([
        'navigation' => $navigation,
        'posts' => $posts
    ]);
}

function post($id) {
    $postManager = new PostManager();
    $commentManager = new CommentManager();

    $loader = new \Twig\Loader\FilesystemLoader('src/view');
    $twig = new \Twig\Environment($loader);

    $post = $postManager->getPost($id);
    $comments = $commentManager->getComments($id);

    $navigation = array(
        array ('href' => ".", 'caption' => 'accueil'),
        array ('href' => "./blog", 'caption' => 'blog'),
        array ('href' => "./enregistrement", 'caption' => "s'identifier / s'enregistrer"),
    );

    $template = $twig->load('postView.twig');
    echo $template->render([
        'navigation' => $navigation,
        'post' => $post,
        'comments' => $comments
    ]);
}

function addComment($idPost, $idUser, $comment) {
    $commentManager = new CommentManager();

    $affectedLines = $commentManager->postComment($idPost, $idUser, $comment);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=post&id=' . $idPost);
    }
}

function register() {
    
}