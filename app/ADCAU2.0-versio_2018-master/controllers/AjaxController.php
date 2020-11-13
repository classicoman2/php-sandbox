<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AjaxController
 *
 * @author toni
 */
class AjaxController extends Controller 
{ 
    /**
     * 
     * @param type $tb
     * @param type $order
     * @param type $first
     * @param type $max
     * @param type $name_f
     * @param type $desc_f
     * @param type $state_f
     * @param type $tag_f
     */
    public function listIssuesX($tb, 
            $order, $first, $max,
            $name_f, $desc_f, $state_f, $tag_f) {        

        $issues = new Issues();
        $rowsPre =  $issues->getIssues($name_f, $desc_f, $state_f, $tag_f, $order);

        $rows = $issues->filter($rowsPre, $first, $max);
        
        //Fields to Show
        $fieldsToShowArray = explode(',',$issues->getFieldsToShow());

        $machines = $issues->getMachinesFromSeveralIssues($rows);
        
        //Obtenir el contingut formatat de totes les files
        $rows = $issues->getRowsData($rows);   
        
        //Obtenir les classes de cada columna o camp
        $fieldsClasses = $issues->getFieldsClasses($fieldsToShowArray);
         

        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('list_issues_x.html.twig', array(

            'totalFiles' => $rowsPre->rowCount(),
            'fieldsToShow' => $fieldsToShowArray,
            'rows' => $rows,
            'machines' => $machines,
            'state' => $state_f,
            'tb' => $tb, 
            'order' => $order, 
            'first' => $first, 'max' => $max,

            'name' => $name_f, 'desc' => $desc_f, 

            'fieldsClasses' => $fieldsClasses,
            ));    
    }
    
    
    /**
     * 
     * @param type $tb
     * @param type $order
     * @param type $first
     * @param type $max
     * @param type $tm_f
     * @param type $name_f
     * @param type $model_f
     * @param type $location_f
     */
    public function listMachinesX($tb, 
                                $order, $first, $max,
                                $tm_f, $name_f, $model_f, $location_f) {
        $tables = new Tables($tb);
        //Get fields to show, separated by ','
        $fieldsToShowArray = explode(",", $tables->getFieldsToShow());  
        
        // Get the Fields We are going to show
        $fieldsSQL    = "";
        $sep = ""; //comma
        $index=0;   //index usat per a numerar els alies de les taules involucrades en el LEFT JOIN.
        $sqlTables = "";  //taules involucrades en el LEFT JOIN
        foreach ($fieldsToShowArray as $field) {
            if (substr($field,0,4)=="fkey") //Fkey field
            {
                $index++;
                $fieldsSQL .= "$sep t$index.name AS $field";
                $joinTable = substr($field,5,strlen($field)-5);
                $sqlTables .= " LEFT JOIN {$joinTable}s "
                           .  "AS t$index ON ma.$field=t$index.id ";
            }
            else
                $fieldsSQL .= " $sep ma.$field AS $field";
            $sep=",";
        }

        /** SQL Query **/
        $sql = "SELECT $fieldsSQL FROM $tb AS ma $sqlTables WHERE 1=1 ";  //xtoni Per simplificar el codi

        //Filtres
        if ($tm_f!="")       $sql .= " AND ma.fkey_type_machine='$tm_f' ";
        if ($name_f!="")     $sql .= " AND ma.name LIKE '%$name_f%' ";
        if ($model_f!="")    $sql .= " AND ma.fkey_model='$model_f' ";
        if ($location_f!="") $sql .= " AND ma.fkey_location='$location_f' ";

        // Order by
        if ($order!="") $sql .= " ORDER BY $order";

        //Execute the Query
        $rowsPre = $tables->executaQuery($sql);
  
        // Get the rows to print =>$rows
        $rows = $tables->filter($rowsPre, $first, $max);

        //Fields to Show
        $fieldsToShowArray = explode(',',$tables->getFieldsToShow());
        //Obtenir el contingut formatat de totes les files
        $rows = $tables->getRowsData($rows);   
        //Obtenir les classes de cada columna o camp
        $fieldsClasses = $tables->getFieldsClasses($fieldsToShowArray);

        
        //Get PageName
        if ($tm_f!="") {
            $row = $tables->getFirstRow("SELECT name FROM type_machines WHERE id='$tm_f'");
            $pageName = $row['name'];
        } else {
            $pageName = "Hardware";
        }

        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('list_machinesX.html.twig', array(
            'pageName' => $pageName,

            'totalFiles' => $rowsPre->rowCount(),
            'fieldsToShow' => $fieldsToShowArray,
            'rows' => $rows,

            'tb' => $tb, 
            'order' => $order,  
            'first' => $first, 'max' => $max,
            'name' => $name_f, 'model' => $model_f, 'location' => $location_f, 'tm' => $tm_f,

            'fieldsClasses' => $fieldsClasses,
            )); 
    }
    
    
    /**
     * 
     * @param type $tb
     * @param type $order
     * @param type $first
     * @param type $max
     * @param type $name_f
     * @param type $desc_f
     */
    public function listGenericX($tb, 
                                $order, $first, $max,
                                $name_f, $desc_f) {
        $tables = new Tables($tb);
        //Get fields to show, separated by ','
        $fieldsToShow = $tables->getFieldsToShow();
        
        /* Get the Rows we're going to print into $row - PUT THE ID!! */
        $sql = "SELECT id,$fieldsToShow FROM $tb WHERE 1=1 ";
        if ($name_f!="")  
            $sql .= " AND name LIKE '%$name_f%'";
        if ($desc_f!="")  
            $sql .= " AND description LIKE '%$desc_f%'";
        if ($order!="") 
            $sql .= " ORDER BY $order";

        //Execute the Query
        $rowsPre = $tables->executaQuery($sql);

        // Get the rows to print =>$rows
        $rows = $tables->filter($rowsPre, $first, $max);

        //Fields to Show
        $fieldsToShowArray = explode(',',$fieldsToShow);
        
        //Obtenir el contingut formatat de totes les files
        $rows = $tables->getRowsData($rows);
        //Obtenir les classes de cada columna o camp
        $fieldsClasses = $tables->getFieldsClasses($fieldsToShowArray);


        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('list_genericX.html.twig', array(

            'totalFiles' => $rowsPre->rowCount(),
            'fieldsToShow' => $fieldsToShowArray,

            'rows' => $rows,
            'fieldsClasses' => $fieldsClasses,

            'tb' => $tb, 
            'order' => $order, 
            'first' => $first, 'max' => $max,
            'name' => $name_f, 'desc' => $desc_f, 

            ));       
    }
    
    
    /**
     * 
     * @param type $tb
     * @param type $order
     * @param type $first
     * @param type $max
     * @param type $name_f
     */
    public function listAppsX($tb, 
                                $order, $first, $max,
                                $name_f) {
        
        $tables = new Tables($tb);
        //Get fields to show, separated by ','
        $fieldsToShow = $tables->getFieldsToShow();
        
        /* Get the Rows we're going to print into $row - PUT THE ID!! */
        $sql = "SELECT id,$fieldsToShow FROM $tb WHERE 1=1";
        if ($name_f!="")  
            $sql .= " AND name LIKE '%$name_f%'";
        if ($order!="") 
            $sql .= " ORDER BY $order";

        //Execute the Query
        $rowsPre = $tables->executaQuery($sql);

        // Get the rows to print =>$rows
        $rows = $tables->filter($rowsPre, $first, $max);

        //Fields to Show
        $fieldsToShowArray = explode(',',$fieldsToShow);
        
        //Obtenir el contingut formatat de totes les files
        $rows = $tables->getRowsData($rows);
        //Obtenir les classes de cada columna o camp
        $fieldsClasses = $tables->getFieldsClasses($fieldsToShowArray);


        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('list_appsX.html.twig', array(

            'totalFiles' => $rowsPre->rowCount(),
            'fieldsToShow' => $fieldsToShowArray,

            'rows' => $rows,
            'fieldsClasses' => $fieldsClasses,

            'tb' => $tb, 
            'order' => $order, 
            'first' => $first, 'max' => $max,
            'name' => $name_f, 
            ));       
    }    
    
    
    
    /**
     * Apilica a les màquines indicades a  $pcs  la llista de apps de
     * instal·lades a la màquina $id
     * 
     * @param string $id    Màquina base de la clonació
     * @param string $pcs   Llista de màquines on vull aplicar la mateixa
     *                      llista de apps instal·lades que a $id
     */
    public function machine_apps_cloningX($id, $pcs) {
        if ($pcs!="")
        {
            $pcs_array  = array();
            $pcs_array  = explode("$", $pcs);
            foreach($pcs_array as $pc)
            {
                if ($pc!= $id) {  //xxtoni  Aixo s'ha de controlar per JavaScript
                    // Delete licenses from selected machines
                    $x = $tables->executaQuery("DELETE FROM licenses WHERE machine='$pc'");

                    // Select Licenses to copy.
                    $licenses = $tables->executaQuery("SELECT * FROM licenses where machine='$id'");

                    // Copy licenses into current $pc
                    foreach ($licenses as $lic) {
                        $sql="INSERT INTO licenses(app,machine,bool_installed,date_installation,comments)".
                             "VALUES('".$lic['app']."','$pc','".$lic['bool_installed']."','".date("Y-m-d")."','".$lic['comments']."')";
                        $x = $tables->executaQuery($sql); 
                    }
                }
            }

            echo 'Les màquines han estat clonades';
        }        
    }
    
    
    /**
     * 
     */
    public function machine_apps_sublistX() {   
        $tables = new Tables();

        $id   = $_GET['id'];

        switch($_GET['f']) {
            case 'del':
                //Esborra llicencia
                $mach = $id;
                $app  = $_GET['app']; //Aplicació a Esborrar
                $result = $tables->executaQuery("DELETE FROM licenses "
                        . "WHERE machine='$mach' and app='$app'");   
            break;

            case 'add':
                //Afegeix llicencia
                $app  = $_GET['app'];      
                //Comprovar que l'aplicació no ha estat instal·lada prèviament.
                if ( $tables->getFirstRow("SELECT app FROM licenses "
                        . "WHERE app='$app' AND machine='$id'") )
                    $errm = "machine_apps_addedit.php";
                else 
                {
                    $date = fromMyDate($_GET['date']);
                    $inst = $_GET['inst'];
                    $comm = $_GET['comm'];
                    //Instal·la app a la màquina
                    $tables->executaQuery("INSERT "
                            . "INTO licenses(app,machine,date_installation,bool_installed,comments) "
                            . "VALUES('$app','$id','$date','$inst','$comm')");
                }
            break;

            case 'mod':
                //Modifica llicencia
                $app  = $_GET['app'];
                $date = fromMyDate($_GET['date']);
                $inst = $_GET['inst'];
                $comm = $_GET['comm'];

                echo $sql;
                
                $sql = "UPDATE licenses "
                        . "SET date_installation='$date',bool_installed=$inst,comments='$comm' "
                        . "WHERE machine='$id' AND app='$app'";
                $tables->executaQuery($sql);
            break;
        }

        //Get the Fields of table LICENSES.
        $licenses = new Tables("licenses");
        $fieldNames = explode(",",$licenses->getTableCols(","));
        // Get the Rows
        $rows = $licenses->executaQuery("SELECT "
                . "t2.name AS appname, t2.id AS appid, "
                . "t1.bool_installed, t1.date_installation, t1.comments "
                . "FROM licenses "
                . "AS t1 INNER JOIN apps AS t2 ON t1.app=t2.id "
                . "AND machine='$id' ORDER BY appname");
        //Pre-processament de les Dades
        $files=array();
        foreach($rows as $k => $row) {
            $files[$k]['appid']    = $row['appid'];
            $files[$k]['appname']  = $row['appname'];
            $files[$k]['inst_val'] = $row['bool_installed'];
            $files[$k]['inst']     = ($row['bool_installed']==true)  ?  'Sí' : 'No';
            $files[$k]['date']     = toMyDate($row['date_installation']);
            $files[$k]['comm']     = $row['comments'];
        }

        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('machine_apps_sublist.html.twig', array(
            'files' => $files,
            ));     
    }
    
    
    /**
     * Updates/Inserts the row values inside the Database, depending of whether
     * it is an Add or an Update
     */
    public function saveX() {
        $tb = $_GET['tb'];
        $id = $_GET['key'];
        //Fields of the database
        $fields = $_GET['fields'];

        $tables = new Tables($tb);

        /* Get the fields of the table */
        $fieldsArray = explode("-", $fields);

        /* Must decide to Update a register or Create a New one */
        if ($id || ($tb == "issues") /* with "issues", I can only Update cos the insert was done with the Draft */ ) 
        {
            $tables->update($tb, $fieldsArray, $id);
        }
        else 
        {   //insert new register
            $sql= "INSERT INTO $tb (".str_replace("-",",",$fields).") VALUES(";
            /* Get the fields, separated by '+'*/
            foreach($fieldsArray as $i => $field)
            {
                if ($i>0)
                    $sql .= ",";
                if (isset($_GET[$field])) 
                {  //in case $tb="machines" there can be input fields missing (not PO or PC)
                    if (substr($field,0,5)=="date_")
                        $sql .=  "'".fromMyDate ($_GET[$field])."'";
                    else
                        if ( ($field == "description") || ($field == "name") )
                            //Use htmlspecialchars to store with correct format the special characters
                            $sql .=  "'".htmlspecialchars($_GET[$field], ENT_QUOTES)."'";
                        else
                            $sql .=  "'".$_GET[$field]."'";
                } else
                    $sql .=  "''";
            }

            $result = $tables->executaQuery($sql.")");
        }      
    }
    
    
    /**
     * 
     * @param type $issue
     */
    public function issue_comments_storedX($issue) {
        $tables = new Tables();
        switch($_GET['op']) 
        {
            //Create a New comment attached to $issue
            case "NEW":
                $name = htmlspecialchars($_GET['name'], ENT_QUOTES);
                $member = $_GET['member'];
                $hour = date('Y-m-d H:i:s');
                $sql =  "INSERT "
                        . "INTO comments(hour,fkey_issue,description,bool_checked,fkey_member) ";
                $sql .= " VALUES('$hour','$issue','$name',0,$member)";

                $y = $tables->executaQuery($sql);        
                break;
        }

        $comments = new Comments();

        $commentsStored = $comments->getCommentsByIssue($issue);
        
        


        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('issue_comments_storedX.html.twig', array(
            'commentsStored' => $commentsStored,
            ));
    }
    
    
    public function issue_links_storedX($issue) {
        $tables = new Tables();

        // PREPROCESSAMENT: nous links (f=new) o eliminar-los tots (f=clr)
        if (isset($_GET['f'])) {
            switch($_GET['f'])
            {
                case "del1":
                     //Delete 1 LINK.
                    //Get link
                    $link = $_GET['link'];
                    $result = $tables->executaQuery("DELETE"
                            . " FROM links WHERE issue='$issue' AND id=$link");            
                    break;
                case "del":
                    //Delete all LINKS from this ISSUE.
                    $result = $tables->executaQuery("DELETE"
                            . " FROM links WHERE issue='$issue'");
                    break;

                case "new":
                    //Create a new LINK. Add it to LINKS table.
                    $link = $_GET['link'];
                    $url  = $_GET['url'];
                    /** CREATE NEW LINK */
                    $z = $tables->executaQuery("INSERT"
                            . " INTO links(name,description,url,issue) VALUES('$link','$link','$url','$issue')");
                    break;
            }
        }

        $links = new Links();
        $storedlinks = $links->getLinksByIssue($issue); 
        $i=0;
        $coma = "";

        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('issue_links_stored.html.twig', array(
                'storedLinks' => $storedlinks,
            ));        
    }
    
    
    /**
     * 
     * @param type $issue
     * @param type $tag
     */
    public function issue_tags_selectedX($issue, $op, $tag) {
        $issues = new Issues();
        $tables = new Tables();

        switch($op) 
        {
            case "CLEAR":
                //Delete the TAGS from this ITEM
                $result = $tables->executaQuery("DELETE "
                        . "FROM issues_tags WHERE issue='$issue' AND tag='$tag'");
                //Delete athe TAG if unused.
                if (Tables::IsQueryResultNull($tables->executaQuery("SELECT * "
                        . "FROM issues_tags WHERE tag='$tag'")))
                        $j = $tables->executaQuery("DELETE "
                                . "FROM tags WHERE id='$tag'");
                break;

            case "ADD": 
                //Add TAGS to this ITEM.
                //Look if the register already exists in 'issues_tags' table
                $x = $tables->executaQuery("SELECT * "
                        . "FROM issues_tags WHERE issue='$issue' AND tag='$tag'");
                if (Tables::IsQueryResultNull($x))
                    $y = $tables->executaQuery("INSERT "
                            . "INTO issues_tags(issue,tag) VALUES('$issue','$tag')");
                break;

            case "NEW":
                //The user has written a new tag.
                //Look if the register ITEM, TAG already exists
                $x = $tables->executaQuery("SELECT * "
                        . "FROM issues_tags WHERE issue='$issue' AND tag='$tag'");
                if (Tables::IsQueryResultNull($x)) {
                    /** CREATE NEW TAG */
                    $z = $tables->executaQuery("INSERT "
                            . "INTO tags(id,name) VALUES('$tag','$tag')");

                    /** Associate TAG to ITEM */
                    $y = $tables->executaQuery("INSERT "
                            . "INTO issues_tags(issue,tag) VALUES('$issue','$tag')");
                }
                break;
        }

        //Recuperar Tags
        $selectedTags = $issues->getTagsByIssue($issue); 

        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('issue_tags_selected.html.twig', array(
            'selectedTags' => $selectedTags,
            ));   
    }
  
  
    /**
     * 
     * @param type $issue
     * @param type $op
     */
    public function issue_machines_selectedX($issue, $op) {
        $tables = new Tables();

        switch($op)
        {
            case "CLEAR":
                //Clear the MACHINES linked to this ISSUE
                $result = $tables->executaQuery("DELETE FROM issues_machines WHERE issue='$issue'");
                break;

            case "ADD":
            if ( isset($_GET['machines']) )
            {
                $machinesArray  = array();            
                $machinesArray  = explode(",", $_GET['machines']);	        	            
                foreach($machinesArray as $machine) 
                {
                    //Look if the link ISSUE - MACHINE already exists
                    $result = $tables->executaQuery("SELECT * FROM issues_machines WHERE issue='$issue' AND machine='$machine'");
                    //If the link doesn't exist, create it.
                    if (Tables::IsQueryResultNull($result))
                        $x = $tables->executaQuery("INSERT INTO issues_machines(issue,machine) VALUES('$issue','$machine')");
                }
            }
            break;
        }

        $issues = new Issues();
        $storedMachines = $issues->getMachinesByIssue($issue);

        
        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('issue_machines_selected.html.twig', array(
            'storedMachines' => $storedMachines,
            ));        
    }
    
    
    /**
     * 
     * @param type $issue
     */
    public function tags_allX($issue) {
        $tags = new Tags();
        $alltags = $tags->getAllTags();

        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('tags_allX.html.twig', array(
            'issue' => $issue,
            'alltags' => $alltags,
            ));
    }
    
   
    /**
     * 
     * @param type $machine_f
     * @param type $location_f
     */
    public function machines_selectX($machine_f, $location_f) {
        $tables = new Tables();
        $filterSql = "";

        if ( ($location_f=="") && ($machine_f=="") ) 
            exit;  //No vull que surtin totes les màquines, no hi caben

        if ($machine_f != "") 
            $filterSql .= " WHERE name like '%$machine_f%'";
        if ($location_f != "")
            if ($machine_f=="")
                $filterSql .= " WHERE fkey_location='$location_f'";
            else
                $filterSql .= " AND fkey_location='$location_f'";

        // Query 
        $machinesFound = $tables->executaQuery("SELECT id,name,fkey_location "
                . "FROM machines $filterSql");

        
        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('machines_selectX.html.twig', array(
            'machinesFound' => $machinesFound,
            'numMachines' => count($machinesFounds),
            ));
    }
    
    
}