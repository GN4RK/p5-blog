<?php

require_once '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../templates');
// $twig = new \Twig\Environment($loader, [
//     'cache' => '../compilation_cache',
// ]);
$twig = new \Twig\Environment($loader);

$navigation = array(
    array ('href' => "index.php", 'caption' => 'home'),
    array ('href' => "index.php", 'caption' => 'blog'),
    array ('href' => "index.php", 'caption' => 'register'),
);

$template = $twig->load('home.twig');
echo $template->render([
    'navigation' => $navigation,
    'a_variable' => 5
]);