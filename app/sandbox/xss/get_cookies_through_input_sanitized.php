<?php

/**  Aquest codi posa al descobert les cookies de l'usuari a traves de camp input d'un formulari
 * 
 *   Inserta això en el camp input:  
 * 
 *     <script>alert(document.cookie)</script>
 * 
 */

// Establim cookies de prova
setcookie("dni", "43000444Z");
setcookie("compte_corrent", "4567-8949-5858-696050", time() + 3600); /* expire in 1 hour */


if (isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    echo "User Has submitted the form and entered this name : <b> $name </b>";
    echo "<br>You can use the following form again to enter a new name.";
}
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="text" name="name"><br>
    <input type="submit" name="submit" value="Submit Form"><br>
</form>