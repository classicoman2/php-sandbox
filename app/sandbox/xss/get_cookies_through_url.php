<?php

/**  Aquest codi posa al descobert les cookies de l'usuari 
 * 
 *   http://localhost/sandbox/xss/get_cookies_through_url.php?name=%3Cscript%3Ealert(document.cookie)%3C/script%3E
 * 
*/

 // Establim cookies de prova
 setcookie("dni", "43000444Z");
 setcookie("compte_corrent", "4567-8949-5858-696050", time()+3600);  /* expire in 1 hour */

 echo $_GET['name'];
?>