<?php

/**
 * Description of OptionsController
 *
 * @author toni
 */
class OptionsController extends Controller {

    // Fills the contents of the Options Window with the files of the table
    public function optionsWindowX($tb) {
       
        $tables = new Tables($tb);

        // The Checkboxes will appear check/unchecked according to values in $show[]
        $show = array();
        $show = str_split($tables->getFieldsFilter()."00000000");  /*xxxtoni falten 0 a la fila corresponent a la taula `_table_filters`*/
        // Get the Fields names.
        $tableCols = $tables->getTableCols("-");
        $tableColsArray = explode("-", $tableCols);



        /* TEMPLATE */
        echo"
              <table class=\"filtering\">  
        ";
        foreach ($tableColsArray as $i => $field) {
          echo"
                  <tr>
                      <td class=\"filter_chb\">
                          <input type='checkbox' id='chb_$field' ".( ($show[$i]) ? " checked=\"checked\" " : "" )."/>
                          ".printFieldLabel($field)."
                      </td>
                </tr>
                ";
        }
        
        echo"      </table>
               <button id='optionssave' onclick=\"saveOptions('$tb','$tableCols', 'divMessage')\">Save Options</button>
               <div id='divMessage'></div>"; 
    } 
   
   
   
    /**
     * 
     * @param String $tb          Table
     * @param String $chbvalues   String with 0's and 1's indicating with fields
     *                            should be shown in the listing.
     */   
    public function optionsWindowSaveX($tb, $chbvalues) {
      
        // Saves the filter field values inside table  _tables_fields
        $tables = new Tables();
        //Update the value
        $result = $tables->executaQuery("UPDATE _tables_fields "
                                        ."SET fields='$chbvalues' "
                                        ."WHERE table_name='$tb'");

        echo "Valors guardats!"; 
    }
   
   
}
