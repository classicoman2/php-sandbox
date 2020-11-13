<?php
require_once '../config.php';;
 /* xxxtoni:  Crear una llista de codis i que mostri el codi en la finestreta!!! */

$name      =  (isset($_GET['name']))  ?  $_GET['name']  :  "";
$desc      =  (isset($_GET['desc']))  ?  $_GET['desc']  :  "";

//issues
$state     =  (isset($_GET['state']))  ?  $_GET['state']  :  "";

//machines
$tm    =  (isset($_GET['tm'])) ?  $_GET['tm']  :  "";

//xtoni
$parameters = "name=$name&desc=$desc&state=$state&tm=$tm";

$order =  (isset($_GET['order'])) ?  $_GET['order']  :  "";
$max   =  (isset($_GET['max']))   ?  $_GET['max']  :  "";
$first =  (isset($_GET['first'])) ?  $_GET['first']  :  "";

$tb = $_GET['tb'];

switch ($_GET['f']) 
{    
    case "delN":    
        /* Get the ids of the checked ones */
        if(!empty($_POST['check_list'])) {
            $i=0;
            $fieldsSelected = "";
            foreach($_POST['check_list'] as $value)  /* $value is the id of the field to delete */
            {
                if ($i>0)
                    $fieldsSelected .= ", ";
                $fieldsSelected .= "'$value'";
                $i++;
            }
            
            //Execute Query
            $tables = new Tables($tb);
            $tables->deleteByIdIntoValues($fieldsSelected);
        } 

        //Carrega de nou.
        $location = "../main.php?pg=mant&tb=$tb&order=$order&first=$first&max=$max&$parameters";
        header('Location:'.$location);
        break;    
    
    case "exp":
        require_once 'impexp.php';
        exportCVS("issues",";",'"',isTableWithAutoInc($tb));
        break;

    
    case "imp":
        require_once 'impexp.php';
    	importCVS($tb,";", '"', isTableWithAutoInc($tb));
        break;   
             
    
    case "updN":        
        //Change priority of selected ISSUES ?
        if (isset($_GET['set_pr'])) {
            // Has the user selected any checkbox?
            if(!empty($_POST['check_list'])) {
                $i=0;
                $fieldsSelected = "";
                //Construeix la llista de id de les files a modificar
                foreach($_POST['check_list'] as $v) {
                    if ($i++>0)
                        $fieldsSelected .= ", ";
                    $fieldsSelected .= "'$v'";
                }
                //Execute Query
                $tables = new Tables($tb);
                $tables->updateFieldByIdIntoValues('fkey_prioritat',$_GET['set_pr'],$fieldsSelected);
            }
        } 
        //Change data_estimated of selected issues ?
        if (isset($_GET['set_dest']))
        {
            /* Get the ids of the checked ones */
            if(!empty($_POST['check_list'])) {
                $i=0;
                $fieldsSelected = "";
                //Construeix la llista de id de les files a modificar
                foreach($_POST['check_list'] as $v) {
                    if ($i++>0)
                        $fieldsSelected .= ", ";
                    $fieldsSelected .= "'$v'";
                }
                //Execute Query
                $tables = new Tables($tb);
                $tables->updateFieldByIdIntoValues('date_estimated',fromMyDate($_GET['set_dest']),$fieldsSelected);
            }
        }
        //Carrega de nou.
        $location = "../main.php?pg=mant&tb=$tb&order=$order&first=$first&max=$max&$parameters";
        header('Location:'.$location);
        break;
    
        
    case "del":
        //Execute Query
        $tables = new Tables($tb);
        $tables->deleteById($_GET['id']);
        //Carrega de nou.
        $location = "../main.php?pg=mant&tb=$tb&order=$order&first=$first&max=$max&$parameters";
        header('Location:'.$location);
        break;                
}
?>