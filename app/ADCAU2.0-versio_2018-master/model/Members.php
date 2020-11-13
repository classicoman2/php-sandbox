<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Tables.php';

/**
 * Description of Members
 *
 * @author toni
 */
class Members extends Tables {

    function isUserBlocked($username) {
        $queryString = "SELECT blocked FROM members WHERE username='$username'";
        $row = $this->getFirstRow($queryString);
       
        return $row['blocked'];
    }
    
    function getPswd($username)  {
        $queryString = "SELECT password FROM members WHERE username='$username'";
        $row = $this->getFirstRow($queryString);
       
        return $row['password'];        
    }
    
}
