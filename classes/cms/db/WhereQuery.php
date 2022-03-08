<?php
class WhereQuery
{
    private $whereQuery = array();
    private $orderByQuery = array();
    private $isFirstWhere = true;
    private $isFirstOrderBy = true;
    
    function __construct($isFirstWhere, $isFirstOrderBy) {
        $this->isFirstWhere = $isFirstWhere;
        $this->isFirstOrderBy = $isFirstOrderBy;
    }
    
    function getIsFirstWhere() {
        return $this->isFirstWhere;
    }
    
    function getIsFirstOrderBy() {
        return $this->isFirstOrderBy;
    }
    
    function addAnd($str) {
        array_push($this->whereQuery, " ".$str);
    }
    
    function addAnd2($str) {
        if ( !empty($str) ) {
            if ( $this->isFirstWhere ) {
                array_push($this->whereQuery, " where");
                $this->isFirstWhere = false;
            } else {
                array_push($this->whereQuery, " and");
            }
            
            array_push($this->whereQuery, " ".$str);
        }
    }
    
    function addAndString($col, $oper, $val) {
        if ( !empty($val) ) {
            if ( $this->isFirstWhere ) {
                array_push($this->whereQuery, " where");
                $this->isFirstWhere = false;
            } else {
                array_push($this->whereQuery, " and");
            }
            
            array_push($this->whereQuery, " ".$col);
            array_push($this->whereQuery, " ".$oper);
            array_push($this->whereQuery, " '".$this->checkVal($val)."'");
        }
    }
    
    function addAndString2($col, $oper, $val) {
        
        if ( $this->isFirstWhere ) {
            array_push($this->whereQuery, " where");
            $this->isFirstWhere = false;
        } else {
            array_push($this->whereQuery, " and");
        }
        
        array_push($this->whereQuery, " ".$col);
        array_push($this->whereQuery, " ".$oper);
        array_push($this->whereQuery, " '".$this->checkVal($val)."'");
        
    }

    function addAndStringNotQuot($col, $oper, $val) {
        if ( !empty($val) ) {
            if ( $this->isFirstWhere ) {
                array_push($this->whereQuery, " where");
                $this->isFirstWhere = false;
            } else {
                array_push($this->whereQuery, " and");
            }
            
            array_push($this->whereQuery, " ".$col);
            array_push($this->whereQuery, " ".$oper);
            array_push($this->whereQuery, " ".$this->checkVal($val));
        }
    }
    
    function addAndStringBind($col, $oper, $val, $bind) {
        if ( !empty($val) ) {
            if ( $this->isFirstWhere ) {
                array_push($this->whereQuery, " where");
                $this->isFirstWhere = false;
            } else {
                array_push($this->whereQuery, " and");
            }
            
            array_push($this->whereQuery, " ".$col);
            array_push($this->whereQuery, " ".$oper);
            array_push($this->whereQuery, " ".str_replace("?", $this->checkVal($val), $bind));
        }
    }
    
    function addAndStringBind2($col, $oper, $val, $bind) {
        
        if ( $this->isFirstWhere ) {
            array_push($this->whereQuery, " where");
            $this->isFirstWhere = false;
        } else {
            array_push($this->whereQuery, " and");
        }
        
        array_push($this->whereQuery, " ".$col);
        array_push($this->whereQuery, " ".$oper);
        array_push($this->whereQuery, " ".str_replace("?", $this->checkVal($val), $bind));
        
    }
    
    function addAndLike($col, $val) {
        if ( !empty($val) ) {
            if ( $this->isFirstWhere ) {
                array_push($this->whereQuery, " where");
                $this->isFirstWhere = false;
            } else {
                array_push($this->whereQuery, " and");
            }
            
            array_push($this->whereQuery, " ".$col);
            array_push($this->whereQuery, " like");
            array_push($this->whereQuery, " '%".$this->checkVal($val)."%'");
        }
    }
    
    function addAndRightLike($col, $val) {
        if ( !empty($val) ) {
            if ( $this->isFirstWhere ) {
                array_push($this->whereQuery, " where");
                $this->isFirstWhere = false;
            } else {
                array_push($this->whereQuery, " and");
            }
            
            array_push($this->whereQuery, " ".$col);
            array_push($this->whereQuery, " like");
            array_push($this->whereQuery, " '".$this->checkVal($val)."%'");
        }
    }
    
    function addAndIn($col, $arrVal) {
        if ( !empty($arrVal) && count($arrVal) > 0 && !empty($arrVal[0]) ) {
            if ( $this->isFirstWhere ) {
                array_push($this->whereQuery, " where");
                $this->isFirstWhere = false;
            } else {
                array_push($this->whereQuery, " and");
            }
            
            $vals = array();
            for ( $i=0; $i<count($arrVal); $i++ ) {
                if ( $i > 0 ) array_push($vals, ",");
                array_push($vals, " '".$this->checkVal($arrVal[$i])."'");
            }
            
            array_push($this->whereQuery, " ".$col);
            array_push($this->whereQuery, " in");
            array_push($this->whereQuery, " (".implode("", $vals).")");
        }
    }
    
    function addAndNotIn($col, $arrVal) {
        if ( !empty($arrVal) && count($arrVal) > 0 && !empty($arrVal[0]) ) {
            if ( $this->isFirstWhere ) {
                array_push($this->whereQuery, " where");
                $this->isFirstWhere = false;
            } else {
                array_push($this->whereQuery, " and");
            }
            
            $vals = array();
            for ( $i=0; $i<count($arrVal); $i++ ) {
                if ( $i > 0 ) array_push($vals, ",");
                array_push($vals, " '".$this->checkVal($arrVal[$i])."'");
            }
            
            array_push($this->whereQuery, " ".$col);
            array_push($this->whereQuery, " not in");
            array_push($this->whereQuery, " (".implode("", $vals).")");
        }
    }
    
    function addOrString($col, $oper, $val) {
        if ( !empty($val) ) {
            if ( $this->isFirstWhere ) {
                array_push($this->whereQuery, " where");
                $this->isFirstWhere = false;
            } else {
                array_push($this->whereQuery, " or");
            }
            
            array_push($this->whereQuery, " ".$col);
            array_push($this->whereQuery, " ".$oper);
            array_push($this->whereQuery, " '".$this->checkVal($val)."'");
        }
    }
    
    function checkVal($val) {
        return str_replace("#", "", str_replace("/", "", str_replace("\\", "", str_replace("\"", "", str_replace("'", "", $val)))));
    }
    
    function addQuery($query) {
        array_push($this->whereQuery, " ".$query);
    }
    
    function getWhereQuery() {
        return implode(" ", $this->whereQuery);
    }
    
    function addOrderBy($col, $asc) {
        if (!empty($col)) {
            if ( $this->isFirstOrderBy ) {
                array_push($this->orderByQuery, " order by");
                $this->isFirstOrderBy = false;
            } else {
                array_push($this->orderByQuery, " ,");
            }
            
            array_push($this->orderByQuery, " ".$col);
            array_push($this->orderByQuery, " ".$asc);
        }
    }
    
    function getOrderByQuery() {
        return implode(" ", $this->orderByQuery);
    }
}
?>