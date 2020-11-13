<?php
/* PRINT the name of the LISTING */
function printPageTitle($tb)
{
    switch($tb) {
        case "machines":
            if (!isset($_GET['tm']))
                return "Hardware";
            else
                switch ($_GET['tm']) {
                    case "IM":  return "Printers"; break;
                    case "PC":  return "PCs"; break;
                    case "PJ":  return "Projectors"; break;
                    case "PO":  return "Laptops"; break;
                    case "RO":  return "Routers"; break;
                    case "SC":  return "Scanners"; break;
                    case "SW":  return "Switches"; break;
                }
            break;
        default:
            //Capitalize first letter.
            return ucfirst($tb);
            break;
    }
}


// Si la cookie no ha estat establerta o ha caducat, torna a LOGIN.
session_start();
if ( !isset ($_SESSION['myusername']) ) {
    session_destroy();
    header("location:index.php");
    die();
}
else {
    $username = $_SESSION['myusername'];
}

require_once 'config.php';

//Get list parameters
$order  = (isset($_GET['order']))   ? $_GET['order'] : "";  //xtoni
$first  = (isset($_GET['first']))   ? $_GET['first'] : 0; 
$max    = (isset($_GET['max']))     ? $_GET['max']   : _MAX_ROWS;


$id = (isset($_GET['id'])) ? htmlentities($_GET['id'])  : 0;
$pg = (isset($_GET['pg'])) ? $_GET['pg']                : "mant";
$tb = (isset($_GET['tb'])) ? $_GET['tb']                : "issues";

//Dispatch to the suitable Controller
switch ($pg)  {
    
    case "logout": 
        $controller = new LoginController();
        $controller->logout();
        break;
        
    case "mant":                                               
        switch ($tb) {
            case "issues":
                //GET
                $name_f = (isset($_GET['name']))  ? htmlspecialchars($_GET['name']) : "";
                $desc_f = (isset($_GET['desc']))  ? htmlspecialchars($_GET['desc']) : "";
                $state  = (isset($_GET['state'])) ? $_GET['state'] : 1;

                $controller = new ListController();
                $controller->issues($name_f, $desc_f, $state, $tb, $order, $max, $first);
                break;
            
            case "machines":    
                //GET
                $name_f    = (isset($_GET['name'])) ? htmlspecialchars($_GET['name']) : "";
                $model    = (isset($_GET['model']))    ? $_GET['model'] : "";
                $location = (isset($_GET['location'])) ? $_GET['location'] : "";
                $tm       = (isset($_GET['tm']))       ?  $_GET['tm']  :  "";

                $controller = new ListController();
                $controller->machines($name_f, $model, $location, $tm, $tb, $order, $max, $first);
                break;
            
            case "apps":    
                //GET
                $name_f    = (isset($_GET['name'])) ? htmlspecialchars($_GET['name']) : "";

                $controller = new ListController();
                $controller->apps($name_f,
                        $tb, 
                        $order, $max, $first);
                break;
            
            case "boards":
            case "graphics":
            case "locations":
            case "models":
            case "oss":
            case "processors":
            case "tags":
            case "type_machines":
            case "type_members":
                //GET
                $name_f = (isset($_GET['name'])) ? htmlspecialchars($_GET['name']) : "";
                $desc_f = (isset($_GET['desc'])) ? htmlspecialchars($_GET['desc']) : "";
                //Name of the page
                $pageName = printPageTitle($tb);
                
                $controller = new ListController();
                $controller->generic($name_f, $desc_f, 
                        $tb, 
                        $order, $max, $first);
                break;
            
            default:
                echo "Error 404, pdt implementar pagina"; //xtoni
                break;
        }
        break;
    
    case "addedit":
        switch ($tb) {
            case "issues":      
                //Filter Parameters
                $name_f  = (isset($_GET['name']))    ?  $_GET['name'] :  "";
                $desc_f  = (isset($_GET['desc']))    ?  $_GET['desc'] :  "";
                $state = (isset($_GET['state']))    ?  $_GET['state'] :  1;
                $tag   = (isset($_GET['tag']))      ?  $_GET['tag'] :  "";               
                
                $controller = new AddeditController();
                $controller->issue($id, $order, $max, $first, $name_f, $desc_f, $state, $tag);
                break;
            case "machines":    
                //Filter Parameters 
                $name_f     = (isset($_GET['name']))     ?  $_GET['name'] :  "";
                $tm_f       = (isset($_GET['tm']))       ?  $_GET['tm'] :  "";
                $model_f    = (isset($_GET['model']))    ?  $_GET['model'] :  "";
                $location_f = (isset($_GET['location'])) ?  $_GET['location'] :  "";

                $controller = new AddeditController();
                $controller->machine($id, $order, $max, $first, $name_f, $tm_f, $model_f, $location_f);
                break;
            
            // Basic CRUDs
            case "apps":
            case "boards":
            case "graphics":
            case "locations":
            case "models":
            case "oss":
            case "processors":
            case "tags":
            case "type_machines":
            case "type_members":
                //Filter Parameters
                $name_f  = (isset($_GET['name']))    ?  $_GET['name'] :  "";
                $desc_f  = (isset($_GET['desc']))    ?  $_GET['desc'] :  "";
                
                $controller = new AddeditController();
                $controller->generic($id, $tb, $order, $max, $first, $name_f, $desc_f);
                break;
            default:
                echo "Error 404, pdt implementar pagina"; //xtoni
                break;
        }        
        break;

    case "machine_apps":
    case "app_machines":
            //GET
            $name_f     = (isset($_GET['name'])) ? htmlspecialchars($_GET['name']) : "";
            $model_f    = (isset($_GET['model']))    ? $_GET['model'] : "";
            $location_f = (isset($_GET['location'])) ? $_GET['location'] : "";
            $tm_f       = (isset($_GET['tm']))       ?  $_GET['tm']  :  "";
            
            $controller = new ListController();
            
            if ("machine_apps"==$pg) {
                $controller->machine_apps($name_f, $model_f, $location_f, $tm_f,  
                $order,  $max, $first,
                        $id);
            }
            else /*("app_machines"==$pg)*/ {
                $controller->app_machines($name_f, $model_f, $location_f, $tm_f,  
                $order,  $max, $first,
                        $id);
            }
        break;

    
    case "ajax":   
        switch($_GET['file'])
        {
            case "list_genericX":
                //Get
                $name_f  = (isset($_GET['name']))     ?  $_GET['name']  :  "";
                $desc_f  = (isset($_GET['desc']))     ?  $_GET['desc']  :  "";
                
                $controller = new AjaxController();
                //List the Rows of this table
                $controller->listGenericX($tb, 
                                $order, $first, $max,
                                $name_f, $desc_f);
                break;
            
            case "list_issuesX":
                //Get
                $name_f  = (isset($_GET['name']))     ?  $_GET['name']  :  "";
                $desc_f  = (isset($_GET['desc']))     ?  $_GET['desc']  :  "";
                
                $state_f = 1; if (isset($_GET['state'])) if ($_GET['state']!='') $state_f = $_GET['state']; //Podria ser que $_GET[state] estigues establert pero tengues valor buid -> me fotria la query.
             
                $tag_f   = (isset($_GET['tag']))      ?  $_GET['tag'] :     "";
                
                $controller = new AjaxController();
                //List the Rows of this table
                $controller->listIssuesX($tb, 
                                $order, $first, $max,
                                $name_f, $desc_f, $state_f, $tag_f);
                break;

            case "list_machinesX":
                //Get
                $tm_f       = (isset($_GET['tm']))       ?  $_GET['tm']  :  "";
                $name_f     = (isset($_GET['name']))     ?  $_GET['name']  :  "";
                $model_f    = (isset($_GET['model']))    ?  $_GET['model']  :  "";
                $location_f = (isset($_GET['location'])) ?  $_GET['location']  :  "";
                                
                $controller = new AjaxController();
                //List the Rows of this table
                $controller->listMachinesX($tb, 
                                $order, $first, $max,
                                $tm_f, $name_f, $model_f, $location_f);
                break;

            case "list_appsX":
                //Get
                $name_f     = (isset($_GET['name']))     ?  $_GET['name']  :  "";
                                
                $controller = new AjaxController();
                //List the Rows of this table
                $controller->listAppsX($tb, 
                                $order, $first, $max,
                                $name_f);
                break;            
            
            case "saveX":         
                $controller = new AjaxController();
                //List the Rows of this table
                $controller->saveX();
                break;
            
            case "machine_apps_sublistX":         
                $controller = new AjaxController();
                //List the Rows of this table
                $controller->machine_apps_sublistX();
                break;

            case "issue_comments_storedX":
                $controller = new AjaxController();
                //GET
                $issue = $_GET['issue'];
                $controller->issue_comments_storedX($issue);
                break;

            case "issue_links_storedX":
                $controller = new AjaxController();
                //GET
                $issue = $_GET['issue'];
                $controller->issue_links_storedX($issue);
                break;            

            case "issue_tags_selectedX":
                $controller = new AjaxController();
                //GET
                $issue = $_GET['issue'];
                $op    = $_GET['op'];
                $tag   = $_GET['tag'];
                $controller->issue_tags_selectedX($issue, $op, $tag);
                break;            
            
            case "issue_machines_selectedX":
                $controller = new AjaxController();
                //GET
                $issue = $_GET['issue'];
                $op    = $_GET['op'];
                $controller->issue_machines_selectedX($issue, $op);
                break;            
            
            case "tags_allX":
                $controller = new AjaxController();
                //GET
                $issue = $_GET['issue'];
                $controller->tags_allX($issue);
                break;            
            
            case "machines_selectX":
                $controller = new AjaxController();
                //GET
                $machine_f  = (isset($_GET['mach'])) ?  $_GET['mach'] :  "";
                $location_f = (isset($_GET['loc']))  ?  $_GET['loc']  :  "";
                $controller->machines_selectX($machine_f, $location_f);
                break;            
                      
            
            case "machine_apps_cloningX":
                $controller = new AjaxController();
                //GET
                $id  = $_GET['id'];
                $pcs = $_GET['pcs'];
                $controller->machine_apps_cloningX($id, $pcs);
                break;  
            

            case "optionsWindowX":
                $controller = new OptionsController();
                //GET
                $tb  = $_GET['tb'];
                $controller->optionsWindowX($tb);
                break;

            case "optionsWindowSaveX":
                $controller = new OptionsController();
                //GET
                    $tb         = $_GET['tb'];
                    $chbvalues  = $_GET['chbvalues'];
                $controller->optionsWindowSaveX($tb, $chbvalues);
                break;
        } 
        break;
    
    default:
        echo "Error 404, pag ".$pg." no implementada"; //xtoni
        break;
}
?>