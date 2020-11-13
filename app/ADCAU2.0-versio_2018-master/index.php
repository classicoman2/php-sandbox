<?php
session_start();

require_once "config.php";

if ( isset($_SESSION['myusername']) )  
{
    header("Location:./main.php?pg=mant&tb=issues&order=fkey_prioritat,date_estimated,hour_estimated");
} 
else
{
    //Template: login.html.twig
    require_once 'lib/Twig/Autoloader.php';
    //true ho he afegit jo per a que en fer autoload, vagi primer aqui i no al model de dades. xtoni
    Twig_Autoloader::register(true);
    $loader = new Twig_Loader_Filesystem('templates');
    $twig = new Twig_Environment($loader, array(
        'cache' => $GLOBALS['twig_cache'],
    ));
    
    
    //Generate template
    echo $twig->render('login.html.twig', array(
        'selectedTags' => $selectedTags,
        ));
}
?>