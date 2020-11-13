<?php
require_once 'Tables.php';

class Comments extends Tables {

    public function getCommentsByIssue($id) {
        $sql = "SELECT t1.description, t1.hour, t2.username FROM comments AS t1 INNER JOIN members AS t2";
        $sql .= " WHERE t1.fkey_issue=$id AND t1.fkey_member=t2.id";
        $sql .= " ORDER BY t1.hour DESC";
        
        return $this->base->executaQuery($sql);
    }    
}
?>