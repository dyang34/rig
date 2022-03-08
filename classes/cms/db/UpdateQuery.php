<?php
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/db/A_Dao.php";

class UpdateQuery extends A_Dao
{
    private $cols = array();
    private $vals = array();
    private $binds = array();
    private $isFirst = true;
    
    function getIsFirst() {
        return $this->isFirst;
    }
    
    function setIsFirst($isFirst) {
        return $this->isFirst = $isFirst;
    }
    
    function add($col, $val) {
        array_push($this->cols, $col);
        array_push($this->vals, $val);
        array_push($this->binds, "");
    }
    
    function add2($col, $val) {
        if ( !is_null($val) && $val != '' ) {
            array_push($this->cols, $col);
            array_push($this->vals, $val);
            array_push($this->binds, "");
        }
    }
    
    function addNotQuot($col, $val) {
        array_push($this->cols, $col);
        array_push($this->vals, $val);
        array_push($this->binds, "NotQuot");
    }
    
    function addWithBind($col, $val, $bind) {
        array_push($this->cols, $col);
        array_push($this->vals, $val);
        array_push($this->binds, $bind);
    }
    
    function getQuery($db) {
        
        $query = array();
        
        for ( $i=0; $i<count($this->cols); $i++ ) {
            if ( $this->isFirst ) {
                array_push($query, " set");
                $this->isFirst = false;
            } else {
                array_push($query, ",");
            }
            
            array_push($query, " ".$this->cols[$i]);
            array_push($query, "=");
            
            if ( empty($this->binds[$i]) ) {
                array_push($query, " ".$this->quot($db, $this->vals[$i]));
            } else if ( $this->binds[$i] == "NotQuot" ) {
                array_push($query, " ".$this->vals[$i]);
            } else {
                array_push($query, " ".str_replace("?", $this->checkMysql($db, $this->vals[$i]), $this->binds[$i]));
            }
        }
        
        return implode(" ", $query);
    }
}
?>