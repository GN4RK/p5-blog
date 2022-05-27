<?php

class Controller 
{
    public static function getNav() {

        if (isset($_SESSION['user'])) {
            return array(
                array ('href' => BASEFOLDER, 'caption' => 'accueil'),
                array ('href' => BASEFOLDER. "blog", 'caption' => 'blog'),
                array ('href' => BASEFOLDER. "disconnection", 'caption' => "se déconnecter"),
            );
        } else {

            return array(
                array ('href' => BASEFOLDER, 'caption' => 'accueil'),
                array ('href' => BASEFOLDER. "blog", 'caption' => 'blog'),
                array ('href' => BASEFOLDER. "login", 'caption' => "se connecter / s'enregistrer"),
            );
        }

    }
}