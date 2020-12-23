<?php

/**
 * Si no empro session_start, no se passa el valor Cookie: hastdelasessio  i session_id() retorna false
 */

 session_start();

// Imprimir headers
foreach (getallheaders() as $name => $value) {
    echo "<b>$name</b>: $value<br>";
}

// si no faig session_start, retorna ''
var_dump(session_id());