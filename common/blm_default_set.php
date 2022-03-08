<?php
/********* 실서버용 *********/
error_reporting(E_ALL ^ E_NOTICE);

/********** 개발용 **********/
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
/****************************/

@header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/util/CookieUtil.php";

/*
if($_SERVER['SERVER_NAME'] == "bling-market.com") {
    if(!strstr($_SERVER[REMOTE_ADDR], "127.0.0.1")) {
        header( "HTTP/1.1 301 Moved Permanently" );
        
        if(RequestUtil::isMobileAgent()) {
            header("Location: http://www.bling-market.com/m/");
        } else {
            header("Location: http://bling-market.com/");
        }
        
        exit;
    }
}
*/

if(!isset($set_time_limit)) $set_time_limit = 0;
@set_time_limit($set_time_limit);

if(!get_magic_quotes_gpc()){
    if(is_array($_GET)){
        while(list($k, $v) = each($_GET)){
            if(is_array($_GET[$k])){
                while(list($k2, $v2) = each($_GET[$k])){
                    $_GET[$k][$k2] = addslashes($v2);
                }
                @reset($_GET[$k]);
            } else{
                $_GET[$k] = addslashes($v);
            }
        }
        @reset($_GET);
    }
    
    if(is_array($_POST)){
        while(list($k, $v) = each($_POST)){
            if(is_array($_POST[$k])){
                while(list($k2, $v2) = each($_POST[$k])){
                    $_POST[$k][$k2] = addslashes($v2);
                }
                @reset($_POST[$k]);
            } else{
                $_POST[$k] = addslashes($v);
            }
        }
        @reset($_POST);
    }
    
    if(is_array($_COOKIE)){
        while(list($k, $v) = each($_COOKIE)){
            if(is_array($_COOKIE[$k])){
                while(list($k2, $v2) = each($_COOKIE[$k])){
                    $_COOKIE[$k][$k2] = addslashes($v2);
                }
                @reset($_COOKIE[$k]);
            } else{
                $_COOKIE[$k] = addslashes($v);
            }
        }
        @reset($_COOKIE);
    }
}

$ext_arr = array('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST', 'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS', 'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for($i=0; $i<$ext_cnt; $i++){
    if(isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
}
/* Renewal Comment
 @extract($_GET);
 @extract($_POST);
 @extract($_SERVER);
 */

$config = array();
$member = array();


/* Renewal Comment
 if(!$path || preg_match("/:\/\//", $path)) die("path error");
 
 $nfor[path] = $path;
 
 unset($path);
 */

/*
 if(substr($_SERVER[HTTP_HOST],0,4)=="www."){
 $_SERVER[HTTP_HOST] = substr($_SERVER[HTTP_HOST],4);
 }
 */

if(!empty($_POST["blm_transfer_ss_id"])){
    @session_id($_POST["blm_transfer_ss_id"]);
}

ini_set("session.use_trans_sid", 0);
ini_set("url_rewriter.tags","");

//session_set_save_handler("nfor_session_open", "nfor_session_close", "nfor_session_read", "nfor_session_write", "nfor_session_destroy", "nfor_session_clean");

@session_cache_limiter("no-cache, must-revalidate");

ini_set("session.cache_expire", 60 * 24 * 30);
//ini_set("session.gc_maxlifetime", 10800);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 100);
//session_set_cookie_params(0, "/");
ini_set("session.cookie_domain", $nfor["cookie_domain"]);

$duration = 24 * 60 * 60 * 30;  // 30일
ini_set('session.gc_maxlifetime', $duration);
session_set_cookie_params($duration);

header("Cache-Control;no-cache");
header("Pragma:no-cache");
header("Content-Type:text/html; charset=utf-8");
?>