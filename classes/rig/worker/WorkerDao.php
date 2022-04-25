<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class WorkerDao extends A_Dao
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
		 
		$sql =" select userid, rm_name, rm_wallet_addr, worker, time, lastSeen, reportedHashrate, currentHashrate, validShares, invalidShares, staleShares, reg_date, upd_date "
			 ." from rig_worker "
			 ." where rp_idx = ".$this->quot($db, $key)
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

		$sql =" select userid, rm_name, rm_wallet_addr, worker, time, lastSeen, reportedHashrate, currentHashrate, validShares, invalidShares, staleShares, reg_date, upd_date "
			 ." from rig_worker"
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
	    
	    $sql =" select userid, rm_name, rm_wallet_addr, worker, time, lastSeen, reportedHashrate, currentHashrate, validShares, invalidShares, staleShares, reg_date, upd_date "
	         ." from rig_worker"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

	    return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			 ."		select @rnum:=0, userid, rm_name, rm_wallet_addr, worker, time, lastSeen, reportedHashrate, currentHashrate, validShares, invalidShares, staleShares, reg_date, upd_date "
			 ."		from rig_worker"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." ) r"
			 ;

		return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from rig_worker a "
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
	
	function exists($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from rig_worker"
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
	    
		$sql =" insert into rig_worker(userid, rm_name, rm_wallet_addr, worker, time, lastSeen, reportedHashrate, currentHashrate, validShares, invalidShares, staleShares, reg_date)"
			 ." values ('".$this->checkMysql($db, $arrVal["userid"])
			 ."', '".$this->checkMysql($db, $arrVal["rm_name"])
			 ."', '".$this->checkMysql($db, $arrVal["rm_wallet_addr"])
			 ."', '".$this->checkMysql($db, $arrVal["worker"])
			 ."', '".$this->checkMysql($db, $arrVal["time"])
             ."', '".$this->checkMysql($db, $arrVal["lastSeen"])
             ."', '".$this->checkMysql($db, $arrVal["reportedHashrate"])
             ."', '".$this->checkMysql($db, $arrVal["currentHashrate"])
             ."', '".$this->checkMysql($db, $arrVal["validShares"])
             ."', '".$this->checkMysql($db, $arrVal["invalidShares"])
             ."', '".$this->checkMysql($db, $arrVal["staleShares"])
             ."',now())"
			 ;

		return $db->query($sql);

	}

}
?>