<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/admin/AdmMemberMgr.php";

$rtnUrl = RequestUtil::getParam("rtnUrl", "");
$mode = RequestUtil::getParam("mode", "");
$userid = RequestUtil::getParam("userid", "");
$passwd = RequestUtil::getParam("passwd", "");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    echo "자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ";
    exit;
}

/*
if (LoginManager::isUserLogined()) {
    JsUtil::alertBack("비정상적인 접근입니다.");
    exit;
}
*/

if($mode=="login"){
    
    if(empty($userid) || empty($passwd)) {
        JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x04)");
        exit;
    }
    
    $wq = new WhereQuery(true, true);
    $wq->addAndString("adm_id", "=", $userid);
    $wq->addAndStringBind("passwd", "=", $passwd, "password('?')");
    
    $row = AdmMemberMgr::getInstance()->getFirst($wq);

    if ( empty($row) ) {
        JsUtil::alertBack("아이디 또는 비밀번호가 잘못 입력 되었습니다.\n\n아이디와 비밀번호를 정확히 입력해 주세요.    ");
        exit;
    } else {

        $row["passwd"] = "";
        LoginManager::setManagerLogin($row);
        
        //$rtnUrl = "/admin/adm_mem_list.php";
        $rtnUrl = "./branch.php";

        JsUtil::replace($rtnUrl);
        
//        header("Location: http://".$_SERVER['HTTP_HOST'].$rtnUrl);
    }
} else {
    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    exit;
}
?>