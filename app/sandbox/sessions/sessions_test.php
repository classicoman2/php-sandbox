<?php

/**
 *  session_start() :
 *   - creates a session or
 *   - resumes the current one based on a session identifier 
 *       - passed via a GET or POST request, or
 *       - passed via a cookie.
 */
//   

session_start();
  $_SESSION["foo"] = "baz";

echo $_SESSION["foo"];

echo "<br><br>";

//Cookie: PHPSESSID=sfkaq3lhfr2c71t17u59q4uk8v

// Print the header Cookie: PHPSESSID --> que s'envia amb cada Request
foreach (getallheaders() as $name => $value) {
  if ($name == "Cookie")
    echo "- <b>$name</b>: $value<br>";
}

if (session_id())
  echo "<br>Session ID retorna " . session_id();

session_destroy();

session_unset();
