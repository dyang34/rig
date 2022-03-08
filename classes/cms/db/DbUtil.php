<?php
include $_SERVER['DOCUMENT_ROOT']."/classes/cms/CmsConfig.php";

class DbUtil
{
    static function getConnection() {
        @ $db = new mysqli(CmsConfig::$mysql_host, CmsConfig::$mysql_user, CmsConfig::$mysql_password, CmsConfig::$mysql_database);
        
        if ( $db->connect_errno ) {
            throw new Exception("DbUtil getConnection Error!");
        } else {
            // 한글처리.
//            mysqli_query($db, 'set names euckr');
            return $db;
        }
    }
/*    
    static function getPowerConnection() {
        @ $db = new mysqli(CmsConfig::$power_host, CmsConfig::$power_user, CmsConfig::$power_password, CmsConfig::$power_database);
        
        if ( $db->connect_errno ) {
            throw new Exception("DbUtil getConnection Error!");
        } else {
            // 한글처리.
            mysqli_query($db, 'set names euckr');
            return $db;
        }
    }
    
    static function getSmsConnection() {
        @ $db = new mysqli(CmsConfig::$sms_host, CmsConfig::$sms_user, CmsConfig::$sms_password, CmsConfig::$sms_database);
        
        if ( $db->connect_errno ) {
            throw new Exception("DbUtil getConnection Error!");
        } else {
            // 한글처리.
            mysqli_query($db, 'set names euckr');
            return $db;
        }
    }
    
    static function getDbdb2Connection() {
        @ $db = new mysqli(CmsConfig::$dbdb2_host, CmsConfig::$dbdb2_user, CmsConfig::$dbdb2_password, CmsConfig::$dbdb2_database);
        
        if ( $db->connect_errno ) {
            throw new Exception("DbUtil getDbdb2Connection Error!");
        } else {
            // 한글처리.
            mysqli_query($db, 'set names euckr');
            return $db;
        }
    }
    
    static function getGgultvConnection() {
        @ $db = new mysqli(CmsConfig::$ggultv_host, CmsConfig::$ggultv_user, CmsConfig::$ggultv_password, CmsConfig::$ggultv_database) ;
        
        if($db->connect_errno) {
            throw new Exception('DbUtil getGgultvConnection Error!') ;
        } else {
            // 한글처리.
            mysqli_query($db, 'set names euckr');
            return $db;
        }
    }
*/    
    static function freeProcedureResult($db) {
        while ( $db->more_results() ) {
            if ( $db->next_result() ) {
                if ( $use_result = $db->use_result() ) {
                    $use_result->close();
                }
            }
        }
    }
}
?>