<?php

// Chargement des classes
require_once('src/model/PostManager.php');
require_once('src/model/CommentManager.php');

function home() {
    $loader = new \Twig\Loader\FilesystemLoader('src/view');
    $twig = new \Twig\Environment($loader);

    $navigation = array(
        array ('href' => "index.php", 'caption' => 'accueil'),
        array ('href' => "index.php?p=blog", 'caption' => 'blog'),
        array ('href' => "index.php?p=register", 'caption' => "s'identifier / s'enregistrer"),
    );

    $template = $twig->load('home.twig');
    echo $template->render([
        'navigation' => $navigation,
        'a_variable' => 5
    ]);
}

function listPosts()
{
    $postManager = new PostManager();
    $posts = $postManager->getPosts();

    require('src/view/listPostsView.php');
}

function post()
{
    $postManager = new PostManager();
    $commentManager = new CommentManager();

    $post = $postManager->getPost($_GET['id']);
    $comments = $commentManager->getComments($_GET['id']);

    require('src/view/postView.php');
}

function addComment($idPost, $idUser, $comment)
{
    $commentManager = new CommentManager();

    $affectedLines = $commentManager->postComment($idPost, $idUser, $comment);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=post&id=' . $idPost);
    }
}