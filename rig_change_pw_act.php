<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/member/MemberMgr.php";

$rtnUrl = RequestUtil::getParam("rtnUrl", "");
$mode = RequestUtil::getParam("mode", "");
$passwd_old = RequestUtil::getParam("passwd_old");
$passwd = RequestUtil::getParam("passwd");
$auto_defense = RequestUtil::getParam("auto_defense", "");

$userid = LoginManager::getUserLoginInfo("userid");

if($auto_defense != "identicharmc!@") {
    echo "자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ";
    exit;
}

if (empty($userid) || !LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

if($mode=="change_pw"){
    
    if(empty($userid) || empty($passwd) || empty($passwd_old)) {
        JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x04)");
        exit;
    }
    
    $wq = new WhereQuery(true, true);
    $wq->addAndString("userid", "=", $userid);
    $wq->addAndStringBind("passwd", "=", $passwd_old, "password('?')");
    
    $row = MemberMgr::getInstance()->getFirst($wq);
    
    if ( empty($row) ) {
        JsUtil::alertBack("기존 비밀번호가 잘못 입력 되었습니다.\n\n기존 비밀번호를 정확히 입력해 주세요.    ");
        exit;
    } else {
        
        $uq = new UpdateQuery();
        $uq->addWithBind("passwd", $passwd, "password('?')");
        MemberMgr::getInstance()->edit($uq, $userid);
/*        
        CookieUtil::removeCookieMd5("blm_ck_auto");
        CookieUtil::removeCookieMd5("blm_ck_userid");
        
        session_start();
        
        header("Cache-Control;no-cache");
        header("Pragma:no-cache");
        
        session_destroy();
*/        
        JsUtil::alertReplace("비밀번호가 변경되었습니다. 다시 로그인 하십시오.    ", "./rig_logout.php");
        
        //header("Location: http://".$_SERVER[SERVER_NAME]."/rig");
    }
} else {
    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    exit;
}
?>