<?php

/*Twig basic usage
 *
 * http://twig.sensiolabs.org/doc/intro.html
 * http://www.sebastien-giroux.com/2010/10/twig-tutorial/
 */

require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();


$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'cache' => 'compilation_cache',
));

echo "lala";

echo $twig->render('demo.html.twig', array('name' => 'Fabien'));

?>