<?php
//Si mantenc aquesta linia de codi, se veu que "colisiona" amb la funció
//autoload i me mostra un error.. interessant postejar-lo a stackoverflow sometime... xtoni
//require_once 'DB.php';

class Tables {
    //La base de dades
    protected $base;
    protected $tb;

    function __construct($tb=null) {
        $this->base = new DB();
        $this->base->connecta();
        $this->tb = $tb;
    }


    /**
     * Returns true if the $result of a query is null
     *
     * @param type $result
     * @return boolean
     */
    static function isQueryResultNull($result) {
        foreach($result as $r)
            //If there's a valid row, returns FALSE.
            return false;
        //There's no valid row -> returns TRUE
        return true;
    }

    /**
     *
     * @param type $id
     */
    function deleteById($id) {
        $this->base->executaQuery("DELETE FROM ".$this->tb." where id='$id'");
    }


    /**
     *
     * @param type $values
     */
    function deleteByIdIntoValues($values) {
        $this->base->executaQuery( "DELETE FROM ".$this->tb." WHERE id IN ($values)");

    }


    /**
     *
     * @param type $queryString
     * @return type
     */
    function executaQuery($queryString) {
        return $this->base->executaQuery($queryString);
    }


    /**
     * Gets an associative array, where the index is the 'id' field and the
     * contents the 'name' field
     *
     * @return type
     */
    function getArrayOfValues()
    {
            $result = $this->executaQuery("SELECT id,name FROM ".$this->tb);
            $arrValues = array();
            $i=0;
            foreach ($result as $row) {
                    $arrValues[$row['id']] = $row['name'];
                    $i++;
            }
            return $arrValues;
    }


    /**
     * xtoni Codi a Optimitzar per a que només tregui una fila
     *
     * @param type $queryString
     * @return type
     */
    function getFirstRow($queryString)
    {
        try {
            $rows = $this->base->executaQuery($queryString);
        }
        catch (PDOException $err) {
            echo "Error en executar la query:<br>".$queryString."<br> a la linia ".$err->getLine()." de ".$err->getFile();
            exit;
            }

        return $rows->fetch(PDO::FETCH_ASSOC);  //xxxtoni
    }


    /**
     * Print a LISTBOX of a table derived from a FK field, with a certain value
     *
     * @param type $f   The name of the field
     * @param type $tb  The name of the table
     * @param type $v   The name of the value, default value = ""
     * @return string
     */
    function getListBoxHTML($f, $tb, $v="")
    {
        $result =  $this->executaQuery("SELECT id,name FROM $tb ORDER BY name");
        $selectTag = "<select class=\"selectLB\" id=\"$f\" name=\"$f\">
                         <option value=\"\" class=\"optionField\">Selecciona ".printFieldLabel($tb)."</option>";
        foreach ($result as $query2)
        {
            $row2 = $query2;
            $selectTag.="<option class=\"optionField\" value=\"".$row2['id']."\"   ";
            if ($v == $row2['id'])  //If this is the value selected
                $selectTag.=    " selected=\"selected\" ";
            $selectTag.= ">".$row2['name']."</option>";
        }
        $selectTag.="</select>";
        return $selectTag;
    }


    /**
     * Gets a string with the fields of the table separated by $sep. If table
     * has an AUTOINC id, then doesn't output the id in the list of fields
     *
     * @param String $sep     A Separator
     * @return String
     */
    public function getTableCols($sep) {
        //Connect to DB
        $this->base->connecta();
        $cols = $this->base->executaQuery("SHOW COLUMNS FROM ".$this->tb."");
        $fields = "";
        //Create a string with the names of the fields separated by $sep
        while ($row = $cols->fetch(PDO::FETCH_ASSOC))
                $fields .= $row['Field'].$sep;

        //Elimina l'id si és autoIncrement (típicament, $fields començarà amb "id,"
        if ($this->isTableWithAutoInc())
            $fields = substr($fields, strpos($fields, $sep));

        //Remove the last "," and return
        return trim($fields, $sep);
    }



    /**
     * Retorna només les files que toca i decodifica htmlspecialchars() amb
     * la funció htmlspecialchars_decode()
     *
     * @param array $rowsPre     Files
     * @param int   $first       Primera fila
     * @param int   $max         Nº total de files a tornar
     *
     * @return array             La selecció de les files
     */
    function filter($rowsPre, $first, $max) {
        $rows = array();
        $count = 0;
        foreach ($rowsPre as $n => $row)
        {
            //Break the loop if all rows have been printed.
            if ($n==($first + $max))
                break;

            if($n>=$first) {
                //Add the row to the result
                foreach ($row as $j => $field) {
                    $rows[$count][$j] = htmlspecialchars_decode($field, ENT_QUOTES);
                }
                $count++;
            }
        }

        return $rows;
    }



    /*
     * Function:   Executa una query genèrica damunt la taula $tb
     */
    function get() {

    }


    /*
     * Get a row from $this->tb identified by $id
     */
    function getRow($id)
    {
        return $this->getFirstRow("SELECT * FROM ".$this->tb." WHERE id='$id'");
    }


    /*
     * Get from table _tables_fields how to filter the fields of $this table
     */
    function getFieldsFilter()
    {
        $this->base->connecta();
        $row = $this->getFirstRow("SELECT fields FROM _tables_fields WHERE table_name='$this->tb'");
        if (!$row['fields'])
            $chbvalues = "1111111111111111111111111"; //xxtoni  Mostrar tots els camps si la taula no existeix
        else
            $chbvalues = $row['fields'];

        return $chbvalues;
    }


    /**
     * Returns fields to show separated by ','
     *
     * @return String
     */
    public function getFieldsToShow() {
        //Getting the Fields of the Table according the values
        //stored in the table `table_fields`
        $fieldNames = explode(",",$this->getTableCols(","));
        //String with 0 and 1's, 1 means field must be shown
        $show = str_split( $this->getFieldsFilter() );

        // Get the Fields to show
        $sep="";
        $fieldsToShow = "";
        foreach ($fieldNames as $i => $field) {
            if ($show[$i]) {
                $fieldsToShow .= $sep.$field;
                $sep = ",";
            }
        }

        return $fieldsToShow;
    }




    /**
     * If there is an AUTOINCREMENT id field, then on Import-Export-Add the
     * treatment is different
     *
     * @return int
     */
    function isTableWithAutoInc()
    {
        return ($this->tb=="issues") ? true : false;
    }


    /**
     *
     */
    function showColumns() {
        $this->base->executaQuery("SHOW COLUMNS FROM ".$this->tb);
    }


    /**
     * Updates the $fiels of a Table ($tb) setting the values
     *
     * @param type $tb
     * @param type $fields
     * @param type $id
     * @return type        Should return a value depending of the result of the Query
     */
    function update($tb,$fields,$id) {
            $sql = "UPDATE $tb SET ";
            $i=0;
            foreach($fields as $field)
            {
                if ( isset($_GET[$field]) && ($field != 'id')) {
                    if ($i++>0)
                        $sql .= ",";
                    //field date_
                    switch(substr($field,0,5)) {
                        case "date_":
                            $sql .= "$field='".fromMyDate ($_GET[$field])."'";
                            break;
                        case "fkey_":
                            //Set NULL in case of an empty value
                            if ($_GET[$field]=="")
                                    $sql .=  "$field=NULL";
                            else
                                $sql .=  "$field='".$_GET[$field]."'";
                            break;
                        case "name":
                        case "descr": /* The input field is a <textarea>, need to escape quotes, etc.. */
                            $sql .= "$field='".htmlspecialchars($_GET[$field], ENT_QUOTES)."'";
                            break;
                        default:
                            $sql .= "$field='".$_GET[$field]."'";
                            break;
                    }
                }
            }

            $sql .= " WHERE id='$id'";

            return $this->base->executaQuery($sql);
    }


    /**
     *
     * @param type $field
     * @param type $value
     * @param type $values
     */
    function updateFieldByIdIntoValues($field, $value, $values) {
        $this->base->executaQuery("UPDATE ".$this->tb." SET $field='$value' WHERE id IN ($values)");
    }



    /**
     * Obtenir el contingut formatat de totes les files
     *
     * @param type $rows
     * @return type
     */
    public function getRowsData($rows) {
        foreach($rows as $i=>$row) {
            foreach($row as $j=>$value) {
                $rows_formatted[$i][$j] = printFieldValue($j, $value);
            }
        }
        return $rows_formatted;
    }



    /**
     * Obtenir les classes de cada columna o camp. La classe conté l'estil
     * CSS amb que s'han de visualitzar les cel·les d'aquest camp.
     *
     * @param  array  $fieldsToShowArray
     * @return array
     */
    public function getFieldsClasses($fieldsToShowArray) {
        foreach($fieldsToShowArray as $field) {
            $fieldsClasses[$field] = printFieldClass($field);
        }
        return $fieldsClasses;
    }

}
?>
