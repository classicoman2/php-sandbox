<?php

require_once 'Tables.php';

/**
 * Description of Tags
 *
 * @author toni
 */
class Tags extends Tables {

    public function getAllTags() {
        return $this->base->executaQuery("SELECT id FROM tags");
    }
}

?>