<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/db/A_Dao.php";

class AdmMemberDao extends A_Dao
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
		 
		$sql =" select adm_id, adm_name, reg_date "
			 ." from rig_adm_member "
			 ." where adm_id = ".$this->quot($db, $key)
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
	    
	    $sql =" select adm_id, adm_name, reg_date "
	        ." from rig_adm_member "
            ." where adm_id = ".$this->quot($db, $key)
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

		$sql =" select adm_id, adm_name, reg_date "
			 ." from rig_adm_member"
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
	    
	    
	    $sql =" select adm_id, adm_name, reg_date "
	        ." from rig_adm_member"
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
	    
	    $sql =" select adm_id, adm_name, reg_date "
	         ." from rig_adm_member"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

	    return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			 ."		select @rnum:=0, adm_id, adm_name, reg_date "
			 ."		from rig_adm_member"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ."		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
			 ." ) r"
			 ;

		return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from rig_adm_member a "
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
			 ." from rig_adm_member"
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
}
?>