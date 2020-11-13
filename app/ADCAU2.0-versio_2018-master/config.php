<?php

/*
 * This file is part of adcau.
 *
 * (c) 2013 tas9
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/* Variables INDISCUTIBLEMENT GLOBALS */
$GLOBALS['twig_cache'] = '../compilation_cache' /*false*/ ;
/*xtoni No vull cache durant desenvolupament, no actualitza pags.!  http://twig.sensiolabs.org/doc/api.html*/

/* CONFIGURACIÓ BÀSICA */
define("_MAX_ROWS", 10);
define("_MODE", 0);   // 0: Design   1: Producció
define("_TITLE_DESIGN", "adcau2.0-dv - mode Disseny");
define("_TITLE_PRODUC", "adcau 1.0 - EASDIB");

//Llistats


define("_MAX_DESC_FIELD_LENGTH", 100);//Max length of description field in a listing

//SESSION
define("_MAX_SESSION_ATTEMPTS", 5);  //Max session attempts per user


//Function that loads a Class File needed, in case we forgot to put the require_once clause.
function autoload($class) {
    //Miro si se tracta d'alguna de les classes del Model
    try {
        $filename = "$class.php";

        //Cerca la classe a /model o a /controllers des de qualsevol directori en que ens trobem
        $directoris = array("model", "controllers");
        foreach($directoris as $dir)
        {
            if (file_exists("$dir/$filename")) {
                require_once("$dir/$filename");
                return;
            }
            if (file_exists($filename)) {
                require_once($filename);
                return;
            }
            if (file_exists("../$dir/$filename")) {
                require_once("../$dir/$filename");
                return;
            }
        }

    }
    catch (Exception $e){
        echo 'No s\'ha pogut carregar aquesta classe: '.$filename;
    }

}
class Autoloader
{
    public static function autoload($class) {
        autoload($class);
    }
}
spl_autoload_register(array('autoloader', 'autoload'));



// This function detects, using $_SERVER data, if the user machine is mobile phone or desktop PC/laptop*/
// Src: http://mobiforge.com/developing/story/lightweight-device-detection-php
function isUserAMobileDevice() {
    $mobile_browser = '0';

    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $mobile_browser++;
    }

    if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'apps/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
        $mobile_browser++;
    }

    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = array(
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
        'wapr','webc','winw','winw','xda ','xda-');

    if (in_array($mobile_ua,$mobile_agents)) {
        $mobile_browser++;
    }
     /* xtoni
    if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
        $mobile_browser++;
    }*/

    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
        $mobile_browser = 0;
    }
    return $mobile_browser;  //xtoni Mode Proves.
}


/**
 * Used for displaying dates in an easy format
 *
 * @param type $data    in the format:  'YYYY-MM-dd'
 * @return string
 */
function toEasyReadingDate($data)
{
    if ( ($data=="") || (substr($data,0,1)=='0') )
            return "";
    else {
        $thisYear = substr(date("Y-m-d"),0,4);

        $year   = substr($data,0,4);
        $month  = substr($data,5,2);
        $day    = substr($data,8,2);

        $diesSetmana = array('Dg','Dll','Dm','Dix','Dj','Dv','Ds');
        $mesos = array('Gener','Febrer','Març','Abr','Maig','Jun','Jul','Agost','Sept','Oct','Nov','Des');
        //Obtenc representacio numèrica del dia de la setmana (de l'1 al 7) + nom dia
        $cadena = $diesSetmana[ date('w', strtotime($data)) ];

        //Lleva el 0 del dia
        if (substr($day,0,1)=="0")
                $day = substr($day,1,1);

        $cadena .= " $day/".$mesos[intval($month) - 1];

        if ($year != $thisYear)
            $cadena .= "/$year";

        return $cadena;
    }
}


/**
 * Used for displaying dates from  YEAR/MH/DY  to  DY/MH/YEAR
 *
 * @param type $data
 * @return string
 */
function toMyDate($data)
{
    if ($data!="") {
        $year   = substr($data,0,4);
        $month  = substr($data,5,2);
        $day    = substr($data,8,2);
        if ( ($day != "00") && ($month != "00") && ($year != "0000"))
            return $day . "-" . $month . "-" . $year;
        else
            return "";
    } else
        return "";
}


/**
 * Used for storing dates from  dd/mm/YEAR to YEAR-mm-dd
 *
 * @param type $data
 * @return type
 */
function fromMyDate($data)  {
    $day    = substr($data,0,2);
    $month  = substr($data,3,2);
    $year   = substr($data,6,4);
    return $year . "-" . $month . "-" . $day;
}


/**
 * Is the user using a mobile device browser?
 *
 * @global type $mobile_browser
 * @return boolean
 */
function isMobileBrowser()
{
    global $mobile_browser;
    $set=false;
    if ($mobile_browser>0)
        $set=true;
    return $set;
}


/**
 *
 * @param type $field
 * @return string
 */
function printFieldClass($field)
{
    switch (substr($field,0,4)) {
        case "desc":  return "description"; break;
        case "date":  return "date";        break;
        case "hour":  return "short";       break;
        default:      return "data";        break;
    }
}


/**
 *
 * @param type $field
 * @param type $value
 * @return string
 */
function printFieldValue($field, $value)
{
    switch (substr($field,0,4)) {
        case "desc":  // Torna un màxim de _MAX_DESC_FIELD_LENGTH caracters
            return  (strlen($value)>_MAX_DESC_FIELD_LENGTH) ? substr($value,0,_MAX_DESC_FIELD_LENGTH). "[..]" : $value;
            break;
        case "link":  /** Format for LINK field */
            if (strlen($value)>8)
                return "<a href=\"\" onclick=\"window.open('$value')\">here</a>";
            else
                return "";
            break;
        case "date":  /** Format for DATE field */
            return toEasyReadingDate($value);
            break;
        default:      /** Default Text Field */
            return $value;
            break;
    }
}


/**
 *
 * @param type $field
 * @param type $value
 * @return type
 */
function printFieldValueForm($field, $value)
{
    $tables = new Tables();

    $cadena="";
    if ($field!="") {
        switch (substr($field, 0, 5))  {
            case "descr":  //descr field
                $cadena =  "<textarea class=\"desc\" id=\"$field\">$value</textarea>";
                break;
            case "bool_":
                $cadena = "<input type=\"checkbox\" id=\"$field\"".( ($value==1) ? " checked=\"checked\"" : "")."/>";
                break;
             case "fkey_":
                 //If FK, get a list of values to select.
                 $cadena = $tables->getListBoxHTML($field,  substr($field, 5,strlen($field)-5)."s" ,  $value);
             break;
             case "date_":
                 $cadena = "<input type=\"text\" class=\"date\" id=\"$field\" name=\"$field\" value=\"".toMyDate($value)."\"/>";
             break;
             default:
                 $cadena = "<input type=\"text\" class=\"text\" id=\"$field\" value=\"$value\"/>";
                 break;
            }
        }
        return $cadena;
}



/**
 * Print label for a Table Field
 *
 * @param type $field
 * @return string
 */
function printFieldLabel($field) {
    switch ($field) {
        case "app":                 return "App"; break;
        case "bool_installed":      return "Instal·lat?"; break;
        case "comments":            return "Comentaris"; break;
        case "date_end":            return "Final"; break;
        case "date_estimated":      return "Estimació"; break;
        case "date_inventory":      return "Date Inventory";break;
        case "date_start":          return "Inici"; break;
        case "date_installation":   return "Data Instal·lació"; break;
        case "descripcio":          return "Descripció"; break;
        case "fkey_graphic":        return "T.Gràfica"; break;
        case "fkey_location":       return "Ubicació"; break;
        case "fkey_processor":      return "Processador"; break;
        case "fkey_model":          return "Model"; break;
        case "fkey_member":         return "Usuari"; break;
        case "fkey_type_machine":   return "Tipus"; break;
        case "fkey_board":          return "Placa"; break;
        case "fkey_state":          return "Estat"; break;
        case "fkey_os":             return "S.Operatiu"; break;
        case "fkey_prioritat":      return "Prior."; break;
        case "filter":              return "Filtra"; break;
        case "hour_estimated":      return "Hora"; break;
        case "id":                  return "Id"; break;
        case "IP":                  return "IP"; break;
        case "link":                return "Enllaç"; break;
        case "name":                return "Nom"; break;
        case "serial_number":       return "Nº Sèrie"; break;
        case "tags":                return "Etiquetes"; break;
        case "windows_key":         return "Clau Windows";   break;
        default:                    return $field; break;
    }
}


/**
 * Funcio per a obtenir l'hora i el dia en els format indicat
 * Format de sortida:    HH:MM
 *
 * @param type $datetime
 * @return type
 */
function getMyHour($datetime) {  return substr($datetime,11,5);  }

/**
 * Funcio per a obtenir l'hora i el dia en els format indicat
 * Format de Sortida:  DD-MM-YYYY
 *
 * @param type $datetime
 * @return type
 */
function getMyDate($datetime) {  return toMyDate(substr($datetime,0,10));  }
?>
