<?php 
class A_Mgr 
{
	function startTran($db) {
		@ $db->query("set autocommit=0");
		@ $db->query("begin");
	}
	
	function commit($db) {
		@ $db->query("commit");
	}
	
	function rollback($db) {
		@ $db->query("rollback");
	}
}
?>