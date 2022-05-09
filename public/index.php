<?php

require_once '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => '../compilation_cache',
]);

$navigation = array(
    array ('href' => "index.php", 'caption' => 'home'),
);

$template = $twig->load('home.twig');
echo $template->render([
    'navigation' => $navigation,
    'a_variable' => 5
]);