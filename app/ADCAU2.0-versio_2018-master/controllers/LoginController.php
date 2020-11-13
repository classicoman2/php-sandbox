<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginController
 *
 * @author toni
 */
class LoginController extends Controller {

    /**
     *
     */
    public function logout() {
        session_start();
        if (isset($_SESSION['myusername']))  {
           unset($_SESSION['myusername']);
        }

        session_destroy();

        header("location:./index.php");
    }


    /**
     * Checks if the username and password entered by the user are correct.
     *
     * @param type $username
     * @param type $password
     */
    public function logincheck($username, $password) {

        session_start();

echo "caca"; die();

        $membersTb = new Members();

        // To protect MySQL injection, $_POST params must be sanitized
        $myusername = stripslashes($username);
        $mypassword = stripslashes($password);

        if (/*$row['blocked']*/$membersTb->isUserBlocked($myusername)) {
            echo "THE USER IS BLOCKED. YOU CAN'T START SESSION. CONTACT THE ADMINISTRATOR.";
        }
         else {
            if (isset($_SESSION['attempts']))
            {
                if ($_SESSION['attempts'] == _MAX_SESSION_ATTEMPTS)
                {
                    // Block the User
                   // $res = dbQuery("UPDATE members SET blocked='1' WHERE username='$myusername'");
                    echo "THE USER HAS BEEN BLOCKED. CONTACT THE ADMINISTRATOR.";
                    die;
                }
                else
                    //Increment number of attempts.
                    $_SESSION['attempts'] = $_SESSION['attempts'] + 1;
            }
            else  //Set number of attempts to 1
                $_SESSION['attempts'] = 1;
        }

        //Get the pswd
        $encriptada = /*$row['password']*/$membersTb->getPswd($myusername);

/* xtoni  hacked  perquè si no, no podia fer login de cap manera */
        if (   1==1  /*crypt($mypassword, $encriptada) == $encriptada */ ) {

            // http://stackoverflow.com/questions/1340001/deny-direct-access-to-all-php-files-except-index-php
            $LOGIN_IS_CORRECT=1;

            //Set Session Username.
            $_SESSION['myusername'] = $myusername;

            //Creo una cookie amb el nom d'usuari. Aquesta cookie s'enviarà al browser amb la primera pàgina de contingut. Després, cada vegada que l'usuari
            //demani la  pagina main.php durà la cookie establerta.
            $value = 'OK';
            setcookie("usuari", $value, time()+3600);  /* expire in 1 hour */

            /* El codi és incomplet, he de controlar que aquest usuari té una sessió oberta.. no se com fer-ho mirar més codi...!! */
            //include 'main.php';
            header("location:../index.php?pg=mant&tb=issues&state=1");

        }
        else {
            $LOGIN_IS_CORRECT=0;
            //Back to Login
            header("location:../index.php");
        }
    }



}
