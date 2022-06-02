<?php
class View {

    public static function render($env, $twigFile, $args = array()) {

        $loader = new \Twig\Loader\FilesystemLoader("src/view/$env");
        $twig = new \Twig\Environment($loader);

        $nav = View::getNav();        
        $name = (isset($_SESSION['user']['first_name'])) ? ($_SESSION['user']['first_name']) : "visiteur";
        $role = (isset($_SESSION['user']['role'])) ? ($_SESSION['user']['role']) : "visiteur";

        $prepare = array(
            'baseFolder' => BASEFOLDER,
            'navigation' => $nav,
            'user'       => $name,
            'role'       => $role
        );

        foreach($args as $k => $v) {
            $prepare[$k] = $v;
        }
    
        $template = $twig->load($twigFile);
        echo $template->render($prepare);

    }

    public static function renderFront($twigFile, $args = array()) {
       View::render("frontend", $twigFile, $args);
    }

    public static function renderBack($twigFile, $args = array()) {
        View::render("backend", $twigFile, $args);
    }

    public static function getNav() {
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