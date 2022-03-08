<?php 
class A_Dao 
{
	function quot($db, $val) {
		return "'".$this->checkMysql($db, $val)."'";
	}
	
	function checkMysql($db, $str) {
		if ( get_magic_quotes_gpc() ) {
			$str = stripslashes($str);
		}
		return mysqli_real_escape_string($db, $str);
	}
	
	function checkHtml($str) {
	    return htmlspecialchars($str, ENT_QUOTES,'UTF-8');
//		return htmlspecialchars($str, ENT_QUOTES,'ISO-8859-1');
	}
	
	function checkAll($db, $str) {
		return $this->checkHtml($this->checkMysql($db, $str));
	}
	
	function freeProcedureResult($db) {
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