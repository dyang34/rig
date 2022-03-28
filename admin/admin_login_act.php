<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/admin/AdmMemberMgr.php";

$rtnUrl = RequestUtil::getParam("rtnUrl", "");
$mode = RequestUtil::getParam("mode", "");
$userid = RequestUtil::getParam("userid", "");
$passwd = RequestUtil::getParam("passwd", "");
$ck_auto = RequestUtil::getParam("ck_auto", "");

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

        if (empty($row["adm_last_login"]) || $row["adm_last_login"] < date("Y-m-d h:i:s",strtotime ("-30 minutes"))) {
            $uq = new UpdateQuery();
            $uq->addNotQuot("adm_last_login", "now()");
            AdmMemberMgr::getInstance()->edit($uq, $userid);
        }
        
        if($ck_auto=="1") {
            //$key = md5($_SERVER["SERVER_ADDR"] . $_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"] . $row["passwd"]);
            
            CookieUtil::setCookieP3pMd5('rig_adm_ck_userid', $row["adm_id"], 86400 * 31);
            CookieUtil::setCookieP3pMd5('rig_adm_ck_auto', "rig_adm_auto_login", 86400 * 31);
            //CookieUtil::setCookieP3pMd5('blm_ck_auto', $key, 86400 * 31);
            
        } else{
            CookieUtil::removeCookie("rig_adm_ck_userid");
            CookieUtil::removeCookie("rig_adm_ck_auto");
        }
        
        $row["passwd"] = "";
        LoginManager::setManagerLogin($row);
        
        //$rtnUrl = "/admin/adm_mem_list.php";
        if(!empty($rtnUrl)){
            $rtnUrl = urldecode($rtnUrl);
            
            if(!(strpos($rtnUrl, "http://") !== false || strpos($rtnUrl, "https://") !== false) )
                $rtnUrl = "http://".$_SERVER[SERVER_NAME].$rtnUrl;
                
        } else {
            $rtnUrl = "./branch.php";
        }

        JsUtil::replace($rtnUrl);
        
//        header("Location: http://".$_SERVER['HTTP_HOST'].$rtnUrl);
    }
} else if ($mode=="autologin") {
    if(empty($userid)) {
        JsUtil::alertBack("비정상적인 자동로그인 입니다. (ErrCode:0x11)");
        exit;
    }
    
    $wq = new WhereQuery(true, true);
    $wq->addAndString("adm_id", "=", $userid);
    $wq->addAndString("adm_fg_del", "=", "0");
    
    $row = AdmMemberMgr::getInstance()->getFirstForLogin($wq);
    
    if ( empty($row) ) {
        JsUtil::alertBack("비정상적인 자동로그인 입니다. (ErrCode:0x12)    ");
        exit;
    } else {
        
        if (empty($row["adm_last_login"]) || $row["adm_last_login"] < date("Y-m-d h:i:s",strtotime ("-30 minutes"))) {
            $uq = new UpdateQuery();
            $uq->addNotQuot("adm_last_login", "now()");
            AdmMemberMgr::getInstance()->edit($uq, $userid);
        }
        
        $row["passwd"] = "";
        //        $row["mb_leave_date"] = "";
        LoginManager::setUserLogin($row);
        
        if(!empty($rtnUrl)){
            $rtnUrl = urldecode($rtnUrl);
            
            if(!(strpos($rtnUrl, "http://") !== false || strpos($rtnUrl, "https://") !== false) )
                $rtnUrl = "http://".$_SERVER[SERVER_NAME].$rtnUrl;
                
        } else {
            $rtnUrl = "./branch.php";
        }
        
        JsUtil::replace($rtnUrl);
    }
    
} else {
    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    exit;
}
?>