<?php
declare(strict_types=1);

namespace App\Model;

class View {

    public static function render(string $env, string $twigFile, array $args = array()): void {

        $loader = new \Twig\Loader\FilesystemLoader("src/view/");
        $twig = new \Twig\Environment($loader);

        $view = new View();
        $session = new Session();
        $nav = $view->getNav();        
        $name = (isset($session->get('user')['first_name'])) ? ($session->get('user')['first_name']) : "visiteur";
        $role = (isset($session->get('user')['role'])) ? ($session->get('user')['role']) : "visiteur";
        $idUser = (isset($session->get('user')['id'])) ? ((int)$session->get('user')['id']) : 0;

        $prepare = array(
            'baseFolder' => BASEFOLDER,
            'content'       => $env. "/" .$twigFile,
            'navigation' => $nav,
            'user'       => $name,
            'id_user'       => $idUser,
            'role'       => $role
        );

        foreach($args as $k => $v) {
            $prepare[$k] = $v;
        }
    
        $template = $twig->load("template.twig");
        echo $template->render($prepare);

    }

    public static function renderFront(string $twigFile, array $args = array()): void {
        $view = new View();
        $view->render("frontend", $twigFile, $args);
    }

    public static function renderBack(string $twigFile, array $args = array()): void {
        $view = new View();
        $view->render("backend", $twigFile, $args);
    }

    public static function getNav(): array {
        $session = new Session();
        if (!empty($session->get('user'))) {
            $nav = array(
                array ('href' => BASEFOLDER, 'caption' => 'accueil'),
                array ('href' => BASEFOLDER. "blog", 'caption' => 'blog'),
                array ('href' => BASEFOLDER. "profil", 'caption' => "profil"),
            );
            return $nav;
        }
        $nav = array(
            array ('href' => BASEFOLDER, 'caption' => 'accueil'),
            array ('href' => BASEFOLDER. "blog", 'caption' => 'blog'),
            array ('href' => BASEFOLDER. "login", 'caption' => "se connecter / s'enregistrer"),
        );
        return $nav;
    }

}