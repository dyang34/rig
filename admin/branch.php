<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isManagerLogined()) {
    JsUtil::alertBack("비정상적인 접근입니다.");
    exit;
}

switch(LoginManager::getManagerLoginInfo('adm_grade')) {
    case "1":
        JsUtil::replace("/admin/adm_mem_list.php");
        break;
    case "10":
        JsUtil::replace("/admin/adm_mem_list.php");
        break;
    default:
        JsUtil::alertBack("비정상적인 권한입니다.");
        exit;
        break;
}
?>