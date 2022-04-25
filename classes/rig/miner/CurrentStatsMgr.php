<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/miner/CurrentStatsDao.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Mgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/DbUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";

class CurrentStatsMgr extends A_Mgr
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
    
    function getByKey($key) {
        
        $row = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $row = CurrentStatsDao::getInstance()->selectByKey($db, $key);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $row;
    }
    
    function getFirst($wq) {
        
        $row = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $row = CurrentStatsDao::getInstance()->selectFirst($db, $wq);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $row;
    }

    /*
     *	$result 사용후 반드시 @ $result->free(); 해줘야 한다.
     */
    function getList($wq) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $result = CurrentStatsDao::getInstance()->select($db, $wq);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    /*
     *	$result 사용후 반드시 @ $result->free(); 해줘야 한다.
     */
    function getListAvg1($wq, $start_h=0, $interval_h=4) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $result = CurrentStatsDao::getInstance()->selectAvg1($db, $wq, $start_h, $interval_h);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    
        /*
     *	$result 사용후 반드시 @ $result->free(); 해줘야 한다.
     */
    function getListAvg2($wq, $start_ymdh="", $interval_h=4) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $result = CurrentStatsDao::getInstance()->selectAvg2($db, $wq, $start_ymdh, $interval_h);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    /*
     *	$result 사용후 반드시 @ $result->free(); 해줘야 한다.
     */
    function getListPerPage($wq, $pg) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $pg->setTotalCount(CurrentStatsDao::getInstance()->selectCount($db, $wq));
            $result = CurrentStatsDao::getInstance()->selectPerPage($db, $wq, $pg);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }

    /*
     *	$result 사용후 반드시 @ $result->free(); 해줘야 한다.
     */
    function getListAvg1PerPage($wq, $start_h=0, $interval_h=4, $pg) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            //$pg->setTotalCount(CurrentStatsDao::getInstance()->selectCount($db, $wq));
            $result = CurrentStatsDao::getInstance()->selectAvg1PerPage($db, $wq, $start_h, $interval_h, $pg);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    /*
     *	$result 사용후 반드시 @ $result->free(); 해줘야 한다.
     */
    function getListAvg2PerPage($wq, $start_ymdh="", $interval_h=4, $pg) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            //$pg->setTotalCount(CurrentStatsDao::getInstance()->selectCount($db, $wq));
            $result = CurrentStatsDao::getInstance()->selectAvg2PerPage($db, $wq, $start_ymdh, $interval_h, $pg);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    function getCount($wq) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $result = CurrentStatsDao::getInstance()->selectCount($db, $wq);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    function exists($wq) {
        
        $result = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            $result = CurrentStatsDao::getInstance()->exists($db, $wq);
            
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $result;
    }
    
    function add($arrVal) {
        
        $isOk = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
//            $this->startTran($db);
            
            $isOk = CurrentStatsDao::getInstance()->insert($db, $arrVal);
            
//            $this->commit($db);
            
        } catch(Exception $e) {
//            $this->rollback($db);
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $isOk;
    }
    
    function edit($uq, $key) {
        
        $isOk = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            //$this->startTran($db);
            
            $isOk = CurrentStatsDao::getInstance()->update($db, $uq, $key);
            
            //$this->commit($db);
            
        } catch(Exception $e) {
            //$this->rollback($db);
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $isOk;
    }
    
    function delete($key) {
        
        $isOk = null;
        $db = null;
        
        try {
            $db = DbUtil::getConnection();
            
            //$this->startTran($db);
            
            $isOk = CurrentStatsDao::getInstance()->delete($db, $key);
            
            //$this->commit($db);
            
        } catch(Exception $e) {
            //$this->rollback($db);
            echo $e->getMessage();
        }
        
        @ $db->close();
        return $isOk;
    }
}
?>