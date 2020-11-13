<?php
/******************************************************************
CREDITS:  http://legend.ws/blog/tips-tricks/csv-php-mysql-import/
 
   MOLT IMPORTANT!!! El fitxer que importam ha de tenir format CVS, amb separacio de registre per ';' i text
 *  delimitat per "" .  A més, el camp 'id' ha de figurar però ha de ser buid. 
*/
	/********************************
	Function importCVS(addauto)
	/* $autoIncrement = Would you like to add an empty field at the beginning of these records?
	This is useful if you have a table with the first field being an auto_increment integer
	and the csv file does not have such as empty field before the records. Set 1 for yes and 0 for no. 
	********************************/

	/*$field_sep = ";";
	$text_lim = '"';
	$autoIncrement=1;*/
	/* HOW TO CALL IT:  importCVS("workTasks",";", '"', 1);  */

require_once '../model/Tables.php';


function importCVS($tb, $field_sep, $text_lim, $autoIncrement) 
{
    /* Would you like to save the mysql queries in a file? If yes set $save to 1.
    Permission on the file should be set to 777. Either upload a sample file through ftp and
    change the permissions, or execute at the prompt: touch output.sql && chmod 777 output.sql  */

    //MODE DE PROVES   $save=1 *********  
    $save = 0;
    $outputfile = "output.sql";

    //Check if the file name was passed through the $_FILES variable.
    if ( !isset($_FILES['fitxer']['name']) ) {
        echo "No filename.\n";
        exit;
    }

    //Get file name.
    $csvfile = $_FILES['fitxer']['name'];

    if($_FILES['fitxer']['error'] == 0){
        $name = $_FILES['fitxer']['name'];
        $size = $_FILES['fitxer']['size'];
        $ext = strtolower(end(explode('.', $_FILES['fitxer']['name'])));
        $type = $_FILES['fitxer']['type'];
        $tmpName = $_FILES['fitxer']['tmp_name'];

        // check the file is a csv
        if($ext === 'csv'){
            if(($handle = fopen($tmpName, 'r')) == FALSE) {
                echo "The file is not a CVS, impexp.php";
                exit();
            }
        } else {
            echo "Couldn't open file, impexp.php";
            exit();    	
        }
    } else {
        echo "There was an error in the file name, impexp.php";
        exit();	
    }

    //Get the file handle.
    $file = $handle;
    if ( !$file) {
        echo "Error opening data file.\n";
        exit;
    }
    if(!$size) {
        echo "File is empty.\n";
        exit;
    }

    /* With fread got the entire size of the file */
    $csvcontent = fread($file,$size);
    //Close file handle.
    fclose($file);

    $lines = 0;  //num. of lines.
    $queries = "";	
    $linearray = array();
    $line_sep = "\n";

    //Get Columns
    $tables = new Tables($tb);
    $columns = $tables->showColumns();	

    $camps_dbb= "";
    $nColumns = 0;

    //Get the name of the columns in this way =  col1,col2,col3,coln
    //Add all the field names.
    while ($column = $columns->fetch(PDO::FETCH_ASSOC)/*mysql_fetch_assoc($queryReturn)*/) {		
            if ($nColumns > 0)
                  $camps_dbb .= ",";
            $camps_dbb .= $column['Field'];
            $nColumns ++;
    }

    // For each line..
    $nRegs=1;
    $nRegsMod=1;
    //Prepare first Query
    $sql = "insert into $tb ($camps_dbb) values(";
    if ($autoIncrement)
        $sql .= "'',";

    /* For each line, get the values, compose a ADD QUERY in $sql and execute the query.  */
    $filearray = array();
    $filearray = explode($field_sep ,$csvcontent);
    //Get the number of registers I got from the import. I need it for the treatment of the last register of every row.
    $filearraysize = sizeof($filearray);
    foreach ($filearray as $register) {
            //trim — Strip whitespace (or other characters) from the beginning and end of a string
            //$register= trim($register," \t");

            //No se que fa   xxxtoni
            //$line = str_replace("\r","",$line);

            $register = str_replace("\\'","'",$register);		
            $register = str_replace("\'","'",$register);
            //This line escapes the special character. remove it if entries are already escaped in the csv file
            $register = str_replace("'","\'",$register);
            //Sembla contraproduent pero estic evitant la duplicacio de \ davant '

            //Lleva les quotes simples que hi pugui haver
            //$register = str_replace("'","",$register);

            //LLevar les "" del començament i del final. Per això, la mida del registre ha de ser mínim 2.
            if ( strlen($register)>1 )
                    if ( !strcmp(substr($register,0,1),'"')) {
                    // If there is the character " at the beginning of the text, remove it before add.
                        $register = substr($register,1,strlen($register)-1);
                    }
            if ( strlen($register)>1 )
                    if ( strpos($register,'"',strlen($register)-2)==strlen($register)-1 )
                    //If there is a " at the ending of the text, remove it
                        $register = substr($register,0,strlen($register)-1);

            //Returns a string of values glued by $glue string = "','"	
            //$linemysql = implode("','",$linearray);   //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

            /* Elimino els caracters que sobren al començament */
            /////$pos = strpos($linemysql , ",");
            //$linemysql = substr($linemysql ,$pos+1,strlen($linemysql));

            //trim — Strip whitespace (or other characters) from the beginning and end of a string
            //$register= trim($register," \t");		

            //Add the value of the register to the query
            $sql .= "'" . $register . "'";


            /* look if it is the last register of the last register. If addauto =1, the number of register is minus 1 */
            if ( fmod($nRegsMod, ($nColumns - $autoIncrement)) == 0 ) {

                //Get the $sql for current execution
                $sqltoexecute=$sql;
                //Prepare next Query
                $sql = "insert into $tb ($camps_dbb) values(";
                if ($autoIncrement)   $sql .= "'',";

                //must advance one more register in $nRegsMod to keep the pace of cols and rows.
                $nRegsMod = $nRegsMod + 2;
                $nRegs++;

                //Search for the first field of next row in the ending of $register.
                $spacepos = 0;
                if (strlen($register)>1)
                    $spacepos = strrpos($register,"\n",-2);

                if ($spacepos >0) {
                       $nextfirstregister = substr( $register, $spacepos+1, strlen($register)-($spacepos+1));  //XXXTONI
                       $sqltoexecute = substr( $sqltoexecute,0,strlen($sqltoexecute)-(strlen($nextfirstregister)+0) );
                       //Prepare next query
                       $sql .= "'" . $nextfirstregister . "'" . ",";
                }


                /*Last query of the table: remove last characters */
                if ($nRegsMod >=  $filearraysize) 
                    $sqltoexecute = substr($sqltoexecute, 0, strlen($sqltoexecute) - 3);
                else 
                    $sqltoexecute = substr($sqltoexecute, 0, strlen($sqltoexecute) - 1);
                $sqltoexecute .= "');";	


                /* EXECUTE THE QUERY */	
                if ( ( strlen($sqltoexecute) > 20 /*avoid last line error*/ ) && ($lines > 0) ) {   /*Jump row with field names */  
                    if (!$save)
                        $tables->executaQuery($sqltoexecute);
                    else
                        echo $sqltoexecute; print "<br>";
                }

                $lines++;		    
             }
             else {
              //To avoid adding a , at the end of the query text.
                $nRegs++;
                $nRegsMod++;
                $sql .= ",";
             }			
    }

    /*
    if($save)
            if(!is_writable($outputfile))
                    echo "File is not writable, check permissions.\n";	
            else {
                    $file2 = fopen($outputfile,"w");			
                    if(!$file2)
                            echo "Error writing to the output file.\n";		
                    else {
                            fwrite($file2,$queries);
                            fclose($file2);
                    }
            }
    */	
}


/*
 * function < exportCVS($tb, $field_sep, $text_lim, $autoIncrement) >
 *  $tb             - Taula
 *  $field_sep      - Character used to separate the fields in the CSV File ( Usually: ' ; ' )  
 *  $text_lim       - Character used to wrap a field value.   ( Usually:  ' " ' )
 *                  Those two characters must be selected according to the values
 *                  put when calling the function
 *  $autoIncrement  - Depending if the table has an autoincrement primary key, usually the field 'id'    
 */

function exportCVS($tb, $field_sep, $text_lim, $autoIncrement)
{
    //http://www.tech-recipes.com/rx/2345/import_csv_file_directly_into_mysql/

    $file = 'copia'; // csv name.

    //Get Fields of the Table
    $fieldNames = explode(",", getTableCols($tb, $autoIncrement));	

    $nCols = 0;
    $csv_output = "";
    $fields = array();
    
    foreach ($fieldNames as $field) 
    {
        //Get the fields and store them
        $fields[$nCols]=$field;
        //Creating the HEADER of the Export File
        if ( ! ($autoIncrement==1 && $nCols==0) ) {  //Si és taula amb autoincrement de clau primaria, no imprimim el primer camp.
            $csv_output .= $text_lim . $field . $text_lim . $field_sep;
        }
        $nCols++;
    }
    $csv_output .= "\n";

    //Select the full contents of the table (falta incloure els valors de les fkeys, ara només surt identificador!  xxxtoni)
    $tables = new Tables();
    $result = $tables->executaQuery("SELECT * FROM ".$tb." ORDER BY id");

    //For each row..
    foreach ($result as $row) {
        //For each register
        for ($j=0;$j<$nCols;$j++)
        //Si és taula amb autoincrement de clau primaria, no imprimim el primer camp.
            if ( !(($autoIncrement==1) && ($j==0)) )  	
                $csv_output .= $text_lim . $row[$fields[$j]]. $text_lim . $field_sep;

        $csv_output .= "\n";
    }

    $filename = $file."_".date("d-m-Y_H-i",time());

    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
    header( "Content-disposition: filename=".$filename.".csv");

    print $csv_output;

    //redirecting to the display page (index.php in our case)
    //header("Location:../index.php"); 	
}

?>