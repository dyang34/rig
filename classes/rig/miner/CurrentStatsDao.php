<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class CurrentStatsDao extends A_Dao
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

	function selectByKey($db, $key) {
		 
		$sql =" select rmcs_idx, userid, rm_name, rm_wallet_addr, time, time_date, lastSeen, lastSeen_date, reportedHashrate, currentHashrate, averageHashrate, validShares, invalidShares, staleShares, activeWorkers, unconfirmed, unpaid, coinsPerMin, usdPerMin, btcPerMin, reg_date "
			 ." from rig_miner_current_stats "
			 ." where rmcs_idx = ".$this->quot($db, $key)
		 	 ;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
			$row = $result->fetch_assoc();
		}
		
		@ $result->free();
		return $row;
	}

	function selectFirst($db, $wq) {

		$sql =" select rmcs_idx, userid, rm_name, rm_wallet_addr, time, time_date, lastSeen, lastSeen_date, reportedHashrate, currentHashrate, averageHashrate, validShares, invalidShares, staleShares, activeWorkers, unconfirmed, unpaid, coinsPerMin, usdPerMin, btcPerMin, reg_date "
			 ." from rig_miner_current_stats"
			 .$wq->getWhereQuery()
			 .$wq->getOrderByQuery()
			 ;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
			$row = $result->fetch_assoc();
		}
		
		@ $result->free();
		return $row;
	}

	function select($db, $wq) {
	    
	    $sql =" select rmcs_idx, userid, rm_name, rm_wallet_addr, time, time_date, lastSeen, lastSeen_date, reportedHashrate, currentHashrate, averageHashrate, validShares, invalidShares, staleShares, activeWorkers, unconfirmed, unpaid, coinsPerMin, usdPerMin, btcPerMin, reg_date "
	         ." from rig_miner_current_stats"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

	    return $db->query($sql);
	}
	
	function selectAvg1($db, $wq, $start_h=0, $interval_h=4) {
	
	   $sql =" SELECT userid "
	        ." ,DATE_FORMAT(time_date,'%Y-%m-%d') date_ymd "
	        ." ,FLOOR((DATE_FORMAT(time_date,'%H')-".$start_h.")/".$interval_h.") date_h "
	        ." ,MIN(time_date) time_date_min "
	        ." ,MAX(time_date) time_date_max "
	        ." ,floor(avg(currentHashrate)) currentHashrate "
	        ." ,floor(avg(averageHashrate)) averageHashrate "
	        ." ,floor(avg(reportedHashrate)) reportedHashrate "
            ." ,COUNT(*) cnt "
            ." from rig_miner_current_stats"
            .$wq->getWhereQuery()
            ." group by userid, DATE_FORMAT(time_date,'%Y-%m-%d'), FLOOR((DATE_FORMAT(time_date,'%H')-".$start_h.")/".$interval_h.") "
            .$wq->getOrderByQuery()
        ;
		                
        return $db->query($sql);
	}
	
	function selectAvg2($db, $wq, $start_ymdh="", $interval_h=4) {
	    
        $sql =" SELECT userid "
			." ,FLOOR((lastSeen-UNIX_TIMESTAMP('".$start_ymdh."'))/".($interval_h*3600).") date_h"
			." ,MIN(lastSeen_date) lastSeen_date_min "
			." ,MAX(lastSeen_date) lastSeen_date_max "
			." ,floor(avg(currentHashrate)) currentHashrate "
			." ,floor(avg(averageHashrate)) averageHashrate "
			." ,floor(avg(reportedHashrate)) reportedHashrate "
			." ,avg(validShares) validShares "
			." ,avg(activeWorkers) activeWorkers "
			." ,avg(coinsPerMin) coinsPerMin "
			." FROM rig_miner_current_stats a "
            .$wq->getWhereQuery()
            ." GROUP BY userid, FLOOR((lastSeen-UNIX_TIMESTAMP('".$start_ymdh."'))/".($interval_h*3600).")"
			.$wq->getOrderByQuery()
        ;
        
        return $db->query($sql);
        
	}

	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			 ."		select @rnum:=0, rmcs_idx, userid, rm_name, rm_wallet_addr, time, time_date, lastSeen, lastSeen_date, reportedHashrate, currentHashrate, averageHashrate, validShares, invalidShares, staleShares, activeWorkers, unconfirmed, unpaid, coinsPerMin, usdPerMin, btcPerMin, reg_date "
			 ."		from rig_miner_current_stats"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." ) r"
			 ;

		return $db->query($sql);
	}
	
	function selectAvg1PerPage($db, $wq, $start_h=0, $interval_h=4, $pg) {
	    
	    $sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
	       ." SELECT @rnum:=0, userid "
	        ." ,DATE_FORMAT(lastSeen_date,'%Y-%m-%d') date_ymd "
	            ." ,FLOOR((DATE_FORMAT(lastSeen_date,'%H')-".$start_h.")/".$interval_h.") date_h "
	                ." ,MIN(lastSeen_date) lastSeen_date_min "
	                    ." ,MAX(lastSeen_date) lastSeen_date_max "
	                        ." ,floor(avg(currentHashrate)) currentHashrate "
	                            ." ,floor(avg(averageHashrate)) averageHashrate "
	                                ." ,floor(avg(reportedHashrate)) reportedHashrate "
	                                    ." ,COUNT(*) cnt "
	                                        ." from rig_miner_current_stats"
	                                            .$wq->getWhereQuery()
	                                            ." group by userid, DATE_FORMAT(lastSeen_date,'%Y-%m-%d'), FLOOR((DATE_FORMAT(lastSeen_date,'%H')-".$start_h.")/".$interval_h.") "
	                                                .$wq->getOrderByQuery()
	                                                ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	                                                ." ) r"
	                                                    ;
	                                                
	                                                return $db->query($sql);
	}
	
	function selectAvg2PerPage($db, $wq, $start_ymdh="", $interval_h=4, $pg) {
	    
	    $sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
	        ." SELECT @rnum:=0, userid "
	            ." ,FLOOR((lastSeen-UNIX_TIMESTAMP('".$start_ymdh."'))/".($interval_h*3600).") date_h"
	            ." ,MIN(lastSeen_date) lastSeen_date_min "
	                ." ,MAX(lastSeen_date) lastSeen_date_max "
	                    ." ,floor(avg(currentHashrate)) currentHashrate "
	                        ." ,floor(avg(averageHashrate)) averageHashrate "
	                            ." ,floor(avg(reportedHashrate)) reportedHashrate "
								." ,avg(validShares) validShares "
								." ,avg(activeWorkers) activeWorkers "
								." ,avg(coinsPerMin) coinsPerMin "
	                                ." ,COUNT(*) cnt "
	                                    ." FROM rig_miner_current_stats a "
	                                        .$wq->getWhereQuery()
	                                        ." GROUP BY userid, FLOOR((lastSeen-UNIX_TIMESTAMP('".$start_ymdh."'))/".($interval_h*3600).")"
	                                            .$wq->getOrderByQuery()
	                                            ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	                                            ." ) r"
	                                                ;
	                                            
	                                            return $db->query($sql);
	                                            
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from rig_miner_current_stats a "
			 .$wq->getWhereQuery()
			 ;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
			$row = $result->fetch_assoc();
		}
		
		@ $result->free();
		return $row["cnt"];
	}
	
	function selectAvg2Count($db, $wq, $start_ymdh="", $interval_h=4) {

		$sql =" select count(*) cnt from (select count(*) "
			 ." from rig_miner_current_stats a "
			 .$wq->getWhereQuery()
			 ." GROUP BY userid, FLOOR((lastSeen-UNIX_TIMESTAMP('".$start_ymdh."'))/".($interval_h*3600).")"
			 .") as t "
			 ;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
			$row = $result->fetch_assoc();
		}
		
		@ $result->free();
		return $row["cnt"];
	}

	function exists($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from rig_miner_current_stats"
			 .$wq->getWhereQuery()
			 ;

		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
			$row = $result->fetch_assoc();
		}

		@ $result->free();
		if ( $row["cnt"] > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	function insert($db, $arrVal) {
	    
		$sql =" insert into rig_miner_current_stats(userid, rm_name, rm_wallet_addr, time, time_date, lastSeen, lastSeen_date, reportedHashrate, currentHashrate, averageHashrate, validShares, invalidShares, staleShares, activeWorkers, unconfirmed, unpaid, coinsPerMin, usdPerMin, btcPerMin, reg_date)"
			 ." values ('".$this->checkMysql($db, $arrVal["userid"])
			 ."', '".$this->checkMysql($db, $arrVal["rm_name"])
			 ."', '".$this->checkMysql($db, $arrVal["rm_wallet_addr"])
			 ."', '".$this->checkMysql($db, $arrVal["time"])
			 ."', '".$this->checkMysql($db, $arrVal["time_date"])
			 ."', '".$this->checkMysql($db, $arrVal["lastSeen"])
			 ."', '".$this->checkMysql($db, $arrVal["lastSeen_date"])
			 ."', '".$this->checkMysql($db, $arrVal["reportedHashrate"])
             ."', '".$this->checkMysql($db, $arrVal["currentHashrate"])
             ."', '".$this->checkMysql($db, $arrVal["averageHashrate"])
             ."', '".$this->checkMysql($db, $arrVal["validShares"])
             ."', '".$this->checkMysql($db, $arrVal["invalidShares"])
             ."', '".$this->checkMysql($db, $arrVal["staleShares"])
             ."', '".$this->checkMysql($db, $arrVal["activeWorkers"])
             ."', '".$this->checkMysql($db, $arrVal["unconfirmed"])
             ."', '".$this->checkMysql($db, $arrVal["unpaid"])
             ."', '".$this->checkMysql($db, $arrVal["coinsPerMin"])
             ."', '".$this->checkMysql($db, $arrVal["usdPerMin"])
             ."', '".$this->checkMysql($db, $arrVal["btcPerMin"])
             ."',now())"
			 ;
			 
		return $db->query($sql);

	}
}
?>