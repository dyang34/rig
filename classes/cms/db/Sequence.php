<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class Sequence extends A_Dao
{
    private static $instance = null;
    
    private function __construct() {
        // getInstance() 이용.
    }
    
    static function getInstance() {
        if ( self::$instance == null ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    function nextVal($db, $seqName) {
        
        $sql =" select fn_nextval(".$this->quot($db, $seqName).") as seq";
        
        $seq = 0;
        $result = $db->query($sql);
        if ( $result->num_rows > 0 ) {
            $row = $result->fetch_assoc();
            if ( !empty($row) ) {
                $seq = $row["seq"];
            }
        }
        
        @ $result->free();
        return $seq;
    }
    
    function nextCode($db, $seqName, $prefix, $maxLen, $landNumLen) {
        $seq = $this->nextVal($db, $seqName);
        
        $zeroLen = $maxLen - strlen($seq) - strlen($prefix) - $landNumLen - 1;
        if ( $zeroLen < 0 ) return "";
        
        $arrZero = array();
        for ( $i=0; $i<$zeroLen; $i++ ) {
            array_push($arrZero, "0");
        }
        
        $arrRandNum = array();
        for ( $i=0; $i<$landNumLen; $i++ ) {
            array_push($arrRandNum, rand(0, 9));
        }
        
        $let = range('A', 'Z');
        
        return $prefix . implode("", $arrZero) . $seq . implode("", $arrRandNum) . $let[rand(0, count($let)-1)];
    }
}
?>