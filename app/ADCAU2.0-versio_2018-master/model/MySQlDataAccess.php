<?php

/**
 * Description of MySQlDataAccess
 *
 * @author tsalas
 * Conté les dades de connexió a la Base de Dades.
 */
class MySQlDataAccess {

    static protected $username      = "root";
    static protected $password      = "";
    static protected $databaseName  = "inventory";


    static function getUsername() {
        return self::$username;
    }

    static function getPassword() {
        return self::$password;
    }

    static function getDatabaseName() {
        return self::$databaseName;
    }

}

?>
