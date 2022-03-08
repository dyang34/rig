<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class PayoutsDao extends A_Dao
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
		 
		$sql =" select rp_idx, userid, rm_wallet_addr, start, end, amount, txHash, paidOn, paidOnTxt, reg_date "
			 ." from rig_payouts "
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

		$sql =" select rp_idx, userid, rm_wallet_addr, start, end, amount, txHash, paidOn, paidOnTxt, reg_date "
			 ." from rig_payouts"
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
	    
	    $sql =" select rp_idx, userid, rm_wallet_addr, start, end, amount, txHash, paidOn, paidOnTxt, reg_date "
	         ." from rig_payouts"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

	    return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			 ."		select @rnum:=0, rp_idx, userid, rm_wallet_addr, start, end, amount, txHash, paidOn, paidOnTxt, reg_date "
			 ."		from rig_payouts"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." ) r"
			 ;

		return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from rig_payouts a "
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
			 ." from rig_payouts"
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
	    
		$sql =" insert into rig_payouts(userid, rm_wallet_addr, start, end, amount, txHash, paidOn, paidOnTxt, reg_date)"
			 ." values ('".$this->checkMysql($db, $arrVal["userid"])
             ."', '".$this->checkMysql($db, $arrVal["rm_wallet_addr"])
             ."', '".$this->checkMysql($db, $arrVal["start"])
             ."', '".$this->checkMysql($db, $arrVal["end"])
             ."', '".$this->checkMysql($db, $arrVal["amount"])
             ."', '".$this->checkMysql($db, $arrVal["txHash"])
             ."', '".$this->checkMysql($db, $arrVal["paidOn"])
             ."', '".$this->checkMysql($db, $arrVal["paidOnTxt"])
             ."', now())"
			 ;

		return $db->query($sql);

	}

	function update($db, $uq, $key) {
	
		$sql =" update rig_payouts"
			 .$uq->getQuery($db)
			 ." where rp_idx = ".$this->quot($db, $key);
	
		return $db->query($sql);
	}

	function delete($db, $key) {

		$sql = "delete from rig_payouts where rp_idx = ".$this->quot($db, $key);

		return $db->query($sql);
	}	
}
?>