<?php
/**
 * Description of Members
 * @author toni
 */
require_once 'Tables.php';

class Issues extends Tables
{
    /*
     * Override parent method
     */
    function __construct() {
        parent::__construct();
    /*    $this->base = new DB();
        $this->base->connecta();*/
        
        $this->tb = "issues";
    }
       
    
    function insertDraft()
    {
        //Crea nova Issue (Draft: fkey_state <= 0 )
        $sql = "INSERT INTO issues(fkey_prioritat,date_estimated,hour_estimated,name,descripcio,date_start,".
                "date_end,image,fkey_member,fkey_state) VALUES('B','','','','','','','','3','0')";
        $this->base->executaQuery($sql);
        
        //Get id and return
        $row = $this->getFirstRow("SELECT MAX(id) AS max FROM ".$this->tb);
        
        return $row['max'];
    }
    
    /*
     * 
     */
    function getIssues($name,$desc,$state,$tag,$order) 
    {
        //Get fields to show
        $fieldsToShowArray = explode(",", $this->getFieldsToShow());
        // Get the Query
        $fieldsSelect = "";
        $sep = ""; //comma
        $index = 0;   //index usat per a numerar els alies de les taules involucrades en el LEFT JOIN.
        $sqlTables = "";  //taules involucrades en el LEFT JOIN

        foreach ($fieldsToShowArray as $fi)
        {
            //He de montar el JOIN en el cas de que el camp a mostrar sigui una FK
            if ( (substr($fi,0,4)=="fkey") /*&& ($fi!="fkey_member")*/ ) //Fkey field
            {
                $index++;
                //el text 'name' corresponent a la taula de la FK com el nom del camp fkey_loquesigui
                //('username' en cas de fkey_member)
                if ($fi!="fkey_member") {
                    $fieldsSelect .= "$sep t$index.name AS $fi, t$index.id AS $fi"."_id ";
                }
                else {
                    $fieldsSelect .= "$sep t$index.username AS $fi, t$index.id AS $fi"."_id ";
                }
                $sqlTables .= " LEFT JOIN ".substr($fi,5,strlen($fi)-5)."s "."AS t$index ON iss.$fi=t$index.id ";
            }
            else {       // Normal Field
                $fieldsSelect .= " $sep iss.$fi AS $fi";
            } 

            $sep = ","; //the comma to separe the fields.
        }


        /** SQL Query **/
        $sql = "SELECT iss.id,iss.bool_checked,$fieldsSelect FROM ";
        // WHERE/ON clause - ON Clause used if a tag has been selected
        if ($tag=="") {
            $sql .= "issues AS iss ";
            $sqlWh = " WHERE ";
        }
        else {
            $sql .= "(issues AS iss INNER JOIN issues_tags AS tg ON iss.id=tg.issue) ";
            $sqlWh = " WHERE tg.tag='$tag' AND ";
        } 

        //Afegeix les taules (dels LEFT JOINs)
        $sql .= " $sqlTables ";

        //state=5 vol dir q s'han seleccionat les Issues Acabades (state=2 o state=3)
        $sqlWh .= ($state!='5') ? " iss.fkey_state=$state" 
                                : " (iss.fkey_state=2 OR iss.fkey_state=3)";                
        if ($name!="")  $sqlWh .= " AND iss.name LIKE '%$name%'";
        if ($desc!="")  $sqlWh .= " AND iss.descripcio LIKE '%$desc%'";
        $sql .= $sqlWh;

        // ORDER clause.
        // Cobrim el cas especial de que haguem d'ordenar per PRIORITAT. En aquest cas, no ordenam per textos
        //sino per id. Exemple: A-Urgent, B-Normal, C-Baixa, D-Molt Baixa, E-Anotacio
        if ($order!="") {
            //En el cas d'aquest camp, ordenar per l'id de la FK, no pel text
            if (substr($order,0,14)=="fkey_prioritat") {
                if (strlen($order)<=strlen("fkey_prioritat"))
                    $sql .= " ORDER BY fkey_prioritat_id";
                else
                    $sql .= ' ORDER BY fkey_prioritat_id,'.substr($order,15,strlen($order)-14);
            }
            else {
                if ($order=="date_start") {
                    $sql .= " ORDER BY $order DESC";
                }
                else
                    $sql .= " ORDER BY $order"; 
            }
        }
        
        return $this->base->executaQuery($sql);
    }
    
    

    
    
    /*
     * Function: Get Machines linked to an Issue
     * Return:   A string of id's of the machines, separated by ","
     */
    function getMachines($issue)
    {
        $files = $this->base->executaQuery("SELECT machine FROM issues_machines WHERE issue='$issue'");
        $cadena="";
        foreach($files as $fila) {
            $cadena .= $fila['machine'].", ";
        }
        return substr($cadena,0,strlen($cadena)-2);
    }
    
    /*
     * Function: Given a handful of Issues, get the Machines associated to each Issue
     * Return:   An array
     */
    function getMachinesFromSeveralIssues($rows) 
    {
        $machines = array();
        $i=0;
        foreach($rows as $row) {
             $machines[$i++] = $this->getMachines($row['id']);
        }
        return $machines;
    }
    
    function getTagsByIssue($id) {
        return $this->base->executaQuery("SELECT * FROM issues_tags WHERE issue='$id'");
    }
    
    
    
    
    
    
    /* 
     * Function:   Sets the Issue indicated by $id as "Checked" 
     * Return:     -
     */
    function setIssueChecked($id) {
        $this->base->executaQuery("UPDATE issues SET bool_checked=1 WHERE id='$id'");
    }
    
    /*
     * Get the machines of an issue
     * Return: a PDO Object containing several rows.
     */
    public function getMachinesByIssue($id) {        
        return $this->base->executaQuery("SELECT machine FROM issues_machines WHERE issue='$id'");
    }    
    
}

