<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class MemberDao extends A_Dao
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
		 
		$sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date "
			 ." from rig_member "
			 ." where userid = ".$this->quot($db, $key)
		 	 ;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
			$row = $result->fetch_assoc();
		}
		
		@ $result->free();
		return $row;
	}

	function selectByKeyDetail($db, $key) {
	    
	    $sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date "
	       ." from rig_member a "
	       ." where userid = ".$this->quot($db, $key)
        ;
	            
        $row = null;
        $result = $db->query($sql);
        if ( $result->num_rows > 0 ) {
            $row = $result->fetch_assoc();
        }
        
        @ $result->free();
        return $row;
	}
	
	function selectByKeyDetail2($db, $key) {
	    
	    $sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date "
            ." from rig_member a "
            ." where userid = ".$this->quot($db, $key)
        ;
	                
        $row = null;
        $result = $db->query($sql);
        if ( $result->num_rows > 0 ) {
            $row = $result->fetch_assoc();
        }
        
        @ $result->free();
        return $row;
	}
	
	function selectByKeyForLogin($db, $key) {
	    
	    $sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date "
	        ." from rig_member "
            ." where userid = ".$this->quot($db, $key)
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

		$sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date "
			 ." from rig_member"
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

	function selectFirstForLogin($db, $wq) {
	    
	    
	    $sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date "
	        ." from rig_member"
            .$wq->getWhereQuery()
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
	    
	    $sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date "
	         ." from rig_member"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

	    return $db->query($sql);
	}
	
	function selectForSupply($db, $wq) {
	    
	    $sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date "
	        ." from rig_member a "
            .$wq->getWhereQuery()
            .$wq->getOrderByQuery()
            ;
            
            return $db->query($sql);
	}
	
	function selectForSupplyDetail($db, $wq) {
	    
	    $sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date "
            ." from rig_member a "
            .$wq->getWhereQuery()
            .$wq->getOrderByQuery()
            ;
            
            return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			 ."		select @rnum:=0, userid, rm_name, rm_wallet_addr, rm_last_login, reg_date, (select sum(cast(amount as signed)) from rig_payouts rp where rp.userid = m.userid) as amount "
			 ."		from rig_member m "
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." ) r"
			 ;

		return $db->query($sql);
	}
	
	function selectForSupplyDetailPerPage($db, $wq, $pg) {
	    
	    // 리뷰의 경우 작성자 체크(자신의 것은 비노출이라도 무조건 보인다)를 적용하지 않음.
	    $sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
	        ."		select @rnum:=0, userid, rm_name, rm_wallet_addr, rm_last_login, reg_date "
            ."		from rig_member a "
            .$wq->getWhereQuery()
            .$wq->getOrderByQuery()
            ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
            ." ) r"
        ;
        
        return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from rig_member a "
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
			 ." from rig_member"
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
	    
		$sql =" insert into rig_member(userid, passwd, rm_name, rm_wallet_addr, reg_date)"
			 ." values ('".$this->checkMysql($db, $arrVal["userid"])
             ."', password('".$this->checkMysql($db, $arrVal["passwd"])."')"
             .", '".$this->checkMysql($db, $arrVal["rm_name"])
             ."', '".$this->checkMysql($db, $arrVal["rm_wallet_addr"])
             ."', now())"
			 ;

		return $db->query($sql);

	}

	function update($db, $uq, $key) {
	
		$sql =" update rig_member"
			 .$uq->getQuery($db)
			 ." where userid = ".$this->quot($db, $key);
	
		return $db->query($sql);
	}

	function delete($db, $key) {

	    $sql =" update rig_member set rm_fg_del=1 where userid = ".$this->quot($db, $key);
	        
		return $db->query($sql);
	}	
}
?>