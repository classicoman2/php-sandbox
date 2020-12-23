<?php

/**  Aquest codi posa al descobert les cookies de l'usuari a traves de camp input d'un formulari
 * 
 *   Inserta això en el camp input:  
 * 
 *     <script>alert(document.cookie)</script>
 * 
 */

// Establim cookies de prova
/**
 *  If   $httponly = true   , the cookie will be accessible only through the HTTP protocol (the cookie will
 *  not be accessible by scripting languages). This setting can help to reduce identity theft through XSS attacks. *
 */
setcookie("dni", "43000444Z", 0, "", "", false, true);
setcookie("compte_corrent", "4567-8949-5858-696050", time() + 3600, "", "", false, true); /* expire in 1 hour */

// Miram que les cookies, per altra banda, SI se troben en les headers de la HTTP Request
$headers = getallheaders();

//echo "Les Cookies en el HTTP Header: " + $headers["Cookie"];

foreach (getallheaders() as $name => $value) {
    if ($name == "Cookie")
        echo "Si puc obtenir les cookies de la HTTP Header: <i>$name: $value</i><br><br>";
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    echo "User name: <b> $name </b><br>";
}
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="name"><br>
    <input type="submit" name="submit" value="Submit Form"><br>
</form>