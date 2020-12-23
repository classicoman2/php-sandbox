<?php

/**
 * Necessito activar la CORS perquè si no, des d'un altre origin (el navegador local, el live server de vscode) no puc accedir
 * un origen diferent (aquest fitxer es troba en http://localhost:80 i per tant és un origen diferent)
 */

//header("Access-Control-Allow-Origin: *");


header("Content-Type: application/json; charset=UTF-8");




echo '{"msg": "esta funcionat"}';

