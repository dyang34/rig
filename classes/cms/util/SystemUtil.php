<?php 
class SystemUtil
{
	static function isLocalhost() {
		if ( $_SERVER[SERVER_ADDR] == "127.0.0.1" ) {
			return true;
		} else {
			return false;
		}
	}

	static function toSsl($url) {
		if ( $_SERVER[SERVER_ADDR] != "127.0.0.1" && 
				($_SERVER['HTTP_HOST'] == "www.sdentalclinic.co.kr" || $_SERVER['HTTP_HOST'] == "sdentalclinic.co.kr") ) {
			return str_replace("http://", "https://", $url);
		} else {
			return $url;
		}		
	}
	
	static function toSsl23($url) {
		if ( $_SERVER[SERVER_ADDR] != "127.0.0.1" && 
				($_SERVER['HTTP_HOST'] == "www.sdentalclinic.co.kr" || $_SERVER['HTTP_HOST'] == "sdentalclinic.co.kr") ) {
			return str_replace("http://", "https://", $url);
		} else {
			return $url;
		}
	}
	
	static function isOfficeIp() {
		
		if ( $_SERVER[REMOTE_ADDR] == "106.246.179.100" ) {
			return true;
		} else {
			return false;
		}
	}

	static function checkWwwDomain() {
		if ( $_SERVER[SERVER_ADDR] != "127.0.0.1" ) {
			if ( $_SERVER['HTTP_HOST'] != "www.bling-market.com" ) {
				echo "<script>location.replace('http://www.sdentalclinic.co.kr".$_SERVER['REQUEST_URI']."');</script>";
				exit;
			}
		}
	}
		
	static function isSecure() {
	    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}
}	
?>