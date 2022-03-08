<?php

define("SSN_KEY_USER_LOGIN", "SSN_KEY_USER_LOGIN");
define("SSN_KEY_MANAGER_LOGIN", "SSN_KEY_MANAGER_LOGIN");

class LoginManager
{
	static function setUserLogin($array) {
		@session_start();
		$_SESSION[SSN_KEY_USER_LOGIN] = $array;
	}
	
	static function getUserLogin() {
	    @session_start();
	    return $_SESSION[SSN_KEY_USER_LOGIN];
	}	
	
	static function isUserLogined() {
		$login = LoginManager::getUserLogin();
		if ( empty($login) ) {
			return false;
		} else {
			return true;
		}
	}
	
	static function getUserLoginInfo($key) {
		$login = LoginManager::getUserLogin();
		if ( empty($login) ) {
			return "";
		} else {
			return $login[$key];
		}
	}	

	static function setUserLoginInfo($key, $val) {
	    $login = LoginManager::getUserLogin();
	    if ( empty($login) ) {
	        return "";
	    } else {
	        //$login[$key] = $val;
	        $_SESSION[SSN_KEY_USER_LOGIN][$key] = $val;
	    }
	}
	
	static function setManagerLogin($array) {
		@session_start();
		$_SESSION[SSN_KEY_MANAGER_LOGIN] = $array;
	}
	
	static function getManagerLogin() {
		@session_start();
		return $_SESSION[SSN_KEY_MANAGER_LOGIN];
	}
	
	static function isManagerLogined() {
		$login = LoginManager::getManagerLogin();
		if ( empty($login) ) {
			return false;
		} else {
			return true;
		}
	}
	
	static function getManagerLoginInfo($key) {
		$login = LoginManager::getManagerLogin();
		if ( empty($login) ) {
			return "";
		} else {
			return $login[$key];
		}
	}
	
	static function isAdminLogined() {
	    $login = LoginManager::getUserLogin();
	    if ( empty($login) || $login[mb_id]!="mtank") {
	        return false;
	    } else {
	        return true;
	    }
	}
}	
?>