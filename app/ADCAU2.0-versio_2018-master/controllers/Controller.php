<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Classe principal de Controlador
 *
 * @author esd
 */
class Controller {
    protected static function generaTwigHandler() 
    {
        require_once 'lib/Twig/Autoloader.php';
        
        //true ho he afegit jo per a que en fer autoload, vagi primer aqui i no al model de dades. xtoni
        Twig_Autoloader::register(true);
        
        $loader = new Twig_Loader_Filesystem('templates');
        $twig = new Twig_Environment($loader, array(
            $GLOBALS['twig_cache'],
        ));  
        
        return $twig;
    }
}
