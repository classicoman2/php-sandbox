<?php

/**
 * Controlador de pantalles de Llistats
 *
 * @author esd
 */
class ListController extends Controller
{
    /**
     *
     * @param type $name_f
     * @param type $desc_f
     * @param type $state
     * @param type $tb
     * @param type $order
     * @param type $max
     * @param type $first
     */
    public function issues($name_f, $desc_f,
            $state,
            $tb,
            $order, $max, $first)
    {
        $tables = new Tables($tb);
        $twig = parent::generaTwigHandler();

        //Generate template
        echo $twig->render('list_issues.html.twig', array(
            'app_header_title' => (0==_MODE) ? _TITLE_DESIGN : _TITLE_PRODUC,
            'name_f' => $name_f, 'desc_f' => $desc_f,
            'state' => $state,
            'tb' => $tb,
            'mode' => _MODE,
            'order' => $order,
            'max' => $max, 'first' => $first,

            'nameLabel' => printFieldLabel("name"), 'descLabel' => printFieldLabel("descripcio"),
            'listboxoftags' => $tables->getListBoxHTML("tag_join", "tags", ""),
            'listboxofprioritats' => $tables->getListBoxHTML("fkey_prioritat", "prioritats", ""),
            ));
    }

    /**
     *
     * @param type $name_f
     * @param type $model
     * @param type $location
     * @param type $tm
     * @param type $tb
     * @param type $order
     * @param type $max
     * @param type $first
     */
    public function machines($name_f, $model, $location, $tm,
            $tb,
            $order,  $max, $first)
    {
        $tables = new Tables($tb);
        $twig = parent::generaTwigHandler();

        //Generate template
        echo $twig->render('list_machines.html.twig', array(
            'app_header_title' => (0==_MODE) ? _TITLE_DESIGN : _TITLE_PRODUC,
            'tb' => $tb,
            'mode' => _MODE,
            'order' => $order, 'max' => $max, 'first' => $first,
            'name_f' => $name_f, 'model' => $model, 'location' => $location, 'tm' => $tm,

            'nameLabel' => printFieldLabel("name"), 'descLabel' => printFieldLabel("descripcio"),
            'listboxofmodels' => $tables->getListBoxHTML("fkey_model", "models", $model),
            'listboxoflocations' => $tables->getListBoxHTML("fkey_location", "locations", $location),
            ));
    }


    /**
     *
     * @param type $name_f
     * @param type $tb
     * @param type $order
     * @param type $max
     * @param type $first
     */
    public function apps($name_f,
            $tb,
            $order,  $max, $first)
    {
        $tables = new Tables($tb);
        $twig = parent::generaTwigHandler();

        //Generate template
        echo $twig->render('list_apps.html.twig', array(
            'app_header_title' => (0==_MODE) ? _TITLE_DESIGN : _TITLE_PRODUC,
            'tb' => $tb,
            'mode' => _MODE,
            'order' => $order, 'max' => $max, 'first' => $first,
            'name_f' => $name_f,
            'nameLabel' => printFieldLabel("name"), 'descLabel' => printFieldLabel("descripcio"),
            ));
    }


    /**
     *
     * @param type $name_f
     * @param type $desc_f
     * @param type $tb
     * @param type $order
     * @param type $max
     * @param type $first
     */
    public function generic($name_f, $desc_f,
            $tb,
            $order, $max, $first) {

        $tables = new Tables($tb);
        $twig = parent::generaTwigHandler();

        //Generate template
        echo $twig->render('list_generic.html.twig', array(
            'app_header_title' => (0==_MODE) ? _TITLE_DESIGN : _TITLE_PRODUC,
            'tb' => $tb,
            'order' => $order,
            'max' => $max, 'first' => $first,
            'nameLabel' => printFieldLabel("name"), 'descLabel' => printFieldLabel("descripcio"),
            'name_f' => $name_f, 'desc_f' => $desc_f,
            ));
    }


    /**
     * Show the applications installed in machine indicated by $id
     *
     * @param type $name_f
     * @param type $model
     * @param type $location
     * @param type $tm
     * @param type $order
     * @param type $max
     * @param type $first
     * @param type $id          Machine
     */
    public function machine_apps($name_f, $model, $location, $tm,
            $order,  $max, $first,
            $id)
    {
        $tables = new Tables();
        $twig = parent::generaTwigHandler();

        //Get Name
        $row = $tables->getFirstRow("SELECT name FROM machines WHERE id='$id'");
        $Name = $row['name'];

        //Generate template
        echo $twig->render('machine_apps.html.twig', array(
            'app_header_title' => (0==_MODE) ? _TITLE_DESIGN : _TITLE_PRODUC,
            'mode' => _MODE,
            'order' => $order, 'max' => $max, 'first' => $first,
            'name_f' => $name_f, 'model' => $model, 'location' => $location, 'tm' => $tm,
            'machineName' => $Name,

            'id' => $id,

            'listboxofapps' => $tables->getListBoxHTML("app", "apps"),
            'data_avui' => date("d-m-Y"),

            //A subformulari  Clonar Llicències de Màquina
            'listboxoflocations' => $tables->getListBoxHTML("fkey_location", "locations", "")

            ));
    }



    /**
     * Show the machines where the application by $id is currently installed
     *
     * @param type $name_f
     * @param type $model
     * @param type $location
     * @param type $tm
     * @param type $order
     * @param type $max
     * @param type $first
     * @param type $id          App
     */
    public function app_machines($name_f, $model, $location, $tm,
            $order,  $max, $first,
            $id)
    {
        $tables = new Tables();
        $twig = parent::generaTwigHandler();

        //Get Name
        $row = $tables->getFirstRow("SELECT name FROM apps WHERE id='$id'");
        $Name = $row['name'];

        //Get the Fields of table LICENSES.
        $licenses = new Tables("licenses");
        $fieldNames = explode(",",$licenses->getTableCols(","));
        // Get the Rows
        $rows = $licenses->executaQuery("SELECT "
                . "t2.name AS machinename, t2.id AS machineid, "
                . "t1.bool_installed, t1.date_installation, t1.comments "
                . "FROM licenses AS t1 INNER JOIN machines AS t2 ON t1.machine=t2.id "
                . "AND t1.app='$id' ORDER BY t2.name");
        //Pre-processament de les Dades
        $files=array();
        foreach($rows as $k => $row) {
            $files[$k]['machineid']    = $row['machineid'];
            $files[$k]['machinename']  = $row['machinename'];
            $files[$k]['inst_val'] = $row['bool_installed'];
            $files[$k]['inst']     = ($row['bool_installed']==true)  ?  'Sí' : 'No';
            $files[$k]['date']     = toMyDate($row['date_installation']);
            $files[$k]['comm']     = $row['comments'];
        }


        //Generate template
        echo $twig->render('app_machines.html.twig', array(
            'app_header_title' => (0==_MODE) ? _TITLE_DESIGN : _TITLE_PRODUC,
            'mode' => _MODE,
            'order' => $order, 'max' => $max, 'first' => $first,
            'name_f' => $name_f, 'model' => $model, 'location' => $location, 'tm' => $tm,
            'appName' => $Name,

            //Machines where the app is installed
            'files' => $files,

            'id' => $id,
/*     xtoni  De moment, només treure el llistat és suficient.
            'listboxofapps' => $tables->getListBoxHTML("app", "apps"),
            'data_avui' => date("d-m-Y"),

            //A subformulari  Clonar Llicències de Màquina
            'listboxoflocations' => $tables->getListBoxHTML("fkey_location", "locations", "")
*/
            ));
    }
}
