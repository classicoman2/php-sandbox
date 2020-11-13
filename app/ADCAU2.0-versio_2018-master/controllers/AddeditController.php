<?php
/*
Controlador del formulari ADDEDIT_ISSUE.HTML.TWIG i subforms associats:
 */

require_once 'config.php';

/**
 * Controladors de pantalles d'Edició
 *
 * @author toni
 */
class AddeditController extends Controller {
    
    /**
     * 
     * @param type $id
     * @param type $order
     * @param type $max
     * @param type $first
     * @param type $name_f
     * @param type $desc_f
     * @param type $state
     * @param type $tag
     */
    public function issue($id, $order, $max, $first, $name_f, $desc_f, $state, $tag) {
        
        $issues = new Issues();
        $tables = new Tables();
        $tb = "issues";
        
        if (!$id)  {
            $new = 1;
            $row = 0;
            //Create a DRAFT.
            $id = $issues->insertDraft();

            $fkey_state = 0; //Draft
        } 
        else  {      //It's an EDIT
            $new = 0;
            $row = $issues->getRow($id);
            //Set the issue as Checked.
            if ($username=="admin")
                if ($row['bool_checked']==0)   $issues->setIssueChecked($id);

            $fkey_state = $row['fkey_state'];
        }        
          
        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('addedit_issues.html.twig', array(
            'id' => $id,
            'new' => $new, 
            'state' => $state,
            'tb' => $tb, 'order' => $order, 'first' => $first, 'max' => $max,
            'name_f' => $name_f, 'desc_f' => $desc_f,
            'listboxdeprioritats' =>  $tables->getListBoxHTML("fkey_prioritat", "prioritats", ($new) ? "B" : $row['fkey_prioritat']),
            'listboxdeubicacions' =>  $tables->getListBoxHTML("fkey_location", "locations", ($new) ? "B" : $row['fkey_location']),

            'tableCols' => $issues->getTableCols("-"),
            'username' => ($username == "") ? "admin" : $username,
            
            //camps
            'row' => $row,
            'date_estimated' => ($new) ? date("d-m-Y") :toMyDate($row['date_estimated']), 
            'hour_estimated' => (!$new) ? $row['hour_estimated'] : "",
            'name'          => (!$new) ? htmlspecialchars_decode($row['name'], ENT_QUOTES) : "",
            'descripcio'    => (!$new) ? htmlspecialchars_decode($row['descripcio'], ENT_QUOTES) : "",
            'date_start'    => ($new) ? date("d-m-Y") : toMyDate($row['date_start']),
            'date_end'      => toMyDate($row['date_end']),
            'checked_html'  => (!$new && (($fkey_state==2) || ($fkey_state==3))) ? " checked=\"checked\" " : "",

            'listboxoflocations' => $tables->getListBoxHTML("fkey_location", "locations", "")
        ));       
    }
    
    
    /**
     * 
     * @param type $id
     * @param type $tb
     * @param type $order
     * @param type $max
     * @param type $first
     * @param type $name_f
     * @param type $desc_f
     */
    public function generic($id, $tb, $order, $max, $first, $name_f, $desc_f) {
        $tables = new Tables($tb);

        if (!$id) {
            $pageName= "Afegir ";
            $new = 1;
            $row = 0;
        }
        else {   
            $pageName= "Editar ";
            $new = 0;
            // Get the row
            $row = $tables->getRow($id);
        }

        /* Don't need the id field for an update (it's the reason for the 0 argument value */
        $tableCols = $tables->getTableCols("-");
        $fieldsArray = explode("-", $tableCols);

        //Get the values formatted for the Template to show them
        foreach($fieldsArray as $field) {
            //Dono el valor que toca. Si no té valor, inicialitzo (cadena o fkey_ a '', bool_ a 0)
            $valor = ($row!=0) ? $row[$field] : ( (substr($field, 0, 4) == "bool") ? 0 : '');
            $fieldsOfForm[$field]->label =  printFieldLabel($field);
            $fieldsOfForm[$field]->field =  printFieldValueForm($field, $row[$field]);   
        }

        //Titol
        $pageName .= ucfirst(substr($tb, 0,strlen($tb)-1));        
        
        //Genera Template
        $twig = parent::generaTwigHandler();
        echo $twig->render('addedit_generic.html.twig', array(
            'id' => $id,
            'tableCols' => $tableCols,
            'pageName' => $pageName,
            'fields_of_tb_array' => $fieldsArray,
            'tb' => $tb, 
            'order' => $order, 
            'max' => $max, 'first' => $first,
            'name_f' => $name_f,  'desc_f' => $desc_f,

            'fieldsOfForm' => $fieldsOfForm,
                ));    
    }
    
    /**
     * 
     * @param type $id
     * @param type $order
     * @param type $max
     * @param type $first
     * @param type $name_f
     * @param type $tm_f
     * @param type $model_f
     * @param type $location_f
     */
    public function machine($id, $order, $max, $first, $name_f, $tm_f, $model_f, $location_f) {
        $tb = "machines";
        $tables = new Tables($tb);

        //$id comes from main.php
        if (!$id) {
            $row = array();
        } 
        else {
            //It's an Edit...
            $row = $tables->getFirstRow("SELECT * FROM $tb WHERE id='$id'");
        }
        
        $isTableWithAutoInc = $tables->isTableWithAutoInc();
        $tableCols = $tables->getTableCols("-");
        $tableColsArray = explode("-",$tableCols);
        
        //Si és alta, posar valor a $row['fkey_type_machine']
        if (!$id && ($tm_f!="") )
            $row['fkey_type_machine'] = $tm_f;
        
        
        //Posar els valors que toca dins  row
        foreach($tableColsArray as $field) {
            //htmlspecialchars
            if ( (substr($field, 0, 4)=="name") || (substr($field, 0, 4)=="desc") ) {
                $row[$field] = htmlspecialchars_decode($row[$field], ENT_QUOTES);
            }
            else {
                if (substr($field, 0, 5)=="fkey_") {
                    //Si és FKEY, he de generar el Listbox corresponent
                    $valor = $row[$field];
                    $row[$field] = $tables->getListBoxHTML($field, substr($field, 5)."s", $valor);
                }
                elseif (substr($field, 0, 5)=="date_") {
                    //Si és una data, he d'efectuar la conversió al format 'ibèric' xtoni
                    $row[$field] = ($id) ? toMyDate($row[$field]) : "";
                }
            }
        }            
        
        //Get the page name
        $tipusPage = ($id) ? "Editar " : "Afegir ";
        if ($tm_f!="") {
            $tipusMaquina = $tables->getFirstRow("SELECT name FROM type_machines WHERE id='$tm_f'");
            $pageName = $tipusPage.$tipusMaquina['name'];
        } else {
            $pageName = $tipusPage."Hardware";
        }
        
        
        //Generate template
        $twig = parent::generaTwigHandler();
        echo $twig->render('addedit_machines.html.twig', array(
            'id' => $id,
            'tb' => $tb, 
            'order' => $order, 'first' => $first, 'max' => $max,
            'name_f' => $name_f, 'model_f' => $model, 'location_f' => $location_f, 'tm_f' => $tm_f,  
            //dades
            'row' => $row,
            
            'pageName' => $pageName,
            'AUTOINC' => $isTableWithAutoInc,

            'tableCols' => $tableCols,
        ));
    }
}