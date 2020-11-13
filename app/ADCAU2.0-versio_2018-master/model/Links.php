<?php

require_once 'Tables.php';

class Links extends Tables {

    public function getLinksByIssue($id) {        
        return $this->base->executaQuery("SELECT id,name,url FROM links WHERE issue='$id'");
    }    
}

?>