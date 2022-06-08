<?php
declare(strict_types=1);

class View {

    public static function render(string $env, string $twigFile, array $args = array()): void {

        $loader = new \Twig\Loader\FilesystemLoader("src/view/");
        $twig = new \Twig\Environment($loader);

        $nav = View::getNav();        
        $name = (isset($_SESSION['user']['first_name'])) ? ($_SESSION['user']['first_name']) : "visiteur";
        $role = (isset($_SESSION['user']['role'])) ? ($_SESSION['user']['role']) : "visiteur";
        $idUser = (isset($_SESSION['user']['id'])) ? ((int)$_SESSION['user']['id']) : 0;

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
       View::render("frontend", $twigFile, $args);
    }

    public static function renderBack(string $twigFile, array $args = array()): void {
        View::render("backend", $twigFile, $args);
    }

    public static function getNav(): array {
        if (isset($_SESSION['user'])) {
            $nav = array(
                array ('href' => BASEFOLDER, 'caption' => 'accueil'),
                array ('href' => BASEFOLDER. "blog", 'caption' => 'blog'),
                array ('href' => BASEFOLDER. "profil", 'caption' => "profil"),
            );
        } else {
            $nav = array(
                array ('href' => BASEFOLDER, 'caption' => 'accueil'),
                array ('href' => BASEFOLDER. "blog", 'caption' => 'blog'),
                array ('href' => BASEFOLDER. "login", 'caption' => "se connecter / s'enregistrer"),
            );
        }
        return $nav;
    }

}