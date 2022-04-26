<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isManagerLogined()) {
    JsUtil::alertBack("비정상적인 접근입니다.");
    exit;
}
/*
if (empty(LoginManager::getUserLoginInfo('iam_fg_outside'))) {
    if(!preg_match("/^1.240.8.130/",$_SERVER['REMOTE_ADDR'])) {
        
        CookieUtil::removeCookieMd5("ism_adm_ck_auto");
        CookieUtil::removeCookieMd5("ism_adm_ck_userid");
        
        session_start();
        
        header("Pragma:no-cache");
        header("Cache-Control;no-cache");
        header("Cache-Control;no-store");
        
        session_destroy();
        
        JsUtil::alertReplace("외부 접속이 허용되지 않습니다.   ","/");
        exit;
    }
}
*/
?>