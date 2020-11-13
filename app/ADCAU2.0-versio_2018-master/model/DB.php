<?php
/**
 * Description of DB
 *
 * @author toni
 */
class DB {
    private $database_target = "localhost";
    private $database_name;
    private $username;
    private $password;
    
    private $dbh;  //DataBase Object
    private $isDBConnected;  // 1 if the Database is connected
    
    public function __constructor() {        
        $this->dbh           = (isset($GLOBALS['dbh']))  ?  $GLOBALS['dbh']  :  0;
        $this->isDBConnected = isset($GLOBALS['database_connected']) ? 1 : 0;        
    }
    
    function isDBConnected() {
        if ($this->isDBConnected)
            return true;
        else
            return false;
    }
    
    public function connecta() 
    {    
        $this->database_name  = MySQlDataAccess::getDatabaseName();
        $this->username       = MySQlDataAccess::getUsername();
        $this->password       = MySQlDataAccess::getPassword();

        if (!$this->isDBConnected())
        {
            $dbConnString = "mysql:host=" . $this->database_target . "; dbname=" . $this->database_name;
            try {
                $this->dbh = new PDO($dbConnString, $this->username, $this->password);
                $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } 
            catch (PDOException $e) {
                echo 'Ha fallat la connexi&oacute; a BDD: ' . $e->getMessage();
                $error = $this->dbh->errorInfo();
                if($error[0] != "") {
                    print "<p>DATABASE CONNECTION ERROR:</p>".$error;
                }
            }
            //Set the connection Object in $GLOBALS variable
            $GLOBALS['dbh'] = $this->dbh;
            $GLOBALS['database_connected']=1;
        }
    }
    
    
    function close() {
        $this->dbh = NULL;
    }

    
    /* Execute a Query in the database. */
    function executaQuery($queryString) {
        //Connect to the DataBase
        $this->connecta();
        try {
            $rows = $this->dbh->query($queryString);
        } 
        catch (PDOException $err) {
            echo "Error en executar la query:<br>".$queryString."<br> a la linia ".$err->getLine()." de ".$err->getFile(); 
            echo "<br><br>".$err->getMessage();
            die;
        }
        return $rows;
    }
}