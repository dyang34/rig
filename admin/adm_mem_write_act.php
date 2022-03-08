<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/payouts/PayoutsMgr.php";

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
    exit;
}

$mode = RequestUtil::getParam("mode", "INS");
$userid = RequestUtil::getParam("userid", "");
$passwd = RequestUtil::getParam("passwd", "");
$rm_name = RequestUtil::getParam("rm_name", "");
$rm_wallet_addr = RequestUtil::getParam("rm_wallet_addr", "");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

try {
    if($mode=="INS") {
        
        if (empty($userid)) {
            JsUtil::alertBack("아이디를 입력해 주십시오.   ");
            exit;
        }
        
        if (empty($passwd)) {
            JsUtil::alertBack("비밀번호를 입력해 주십시오.   ");
            exit;
        }
        
        if (empty($rm_name)) {
            JsUtil::alertBack("이름을 입력해 주십시오.   ");
            exit;
        }
        
        if (empty($rm_wallet_addr)) {
            JsUtil::alertBack("전자지갑 주소를 입력해 주십시오.   ");
            exit;
        }
        
        if (substr($rm_wallet_addr,0,2) != "0x") {
            JsUtil::alertBack("전자지갑 주소는 16진수로 입력해 주십시오.   ");
            exit;
        }
        
        $wq = new WhereQuery(true, true);
        $wq->addAndString("userid","=",$userid);
        
        if (MemberMgr::getInstance()->exists($wq)) {
            JsUtil::alertBack("이미 존재하는 아이디입니다.   ");
            exit;
        }
        
        $arrIns = array();
        $arrIns["userid"] = $userid;
        $arrIns["passwd"] = $passwd;
        $arrIns["rm_name"] = $rm_name;
        $arrIns["rm_wallet_addr"] = substr($rm_wallet_addr,2,strlen($rm_wallet_addr)-2);
        
        MemberMgr::getInstance()->add($arrIns);
        
        JsUtil::alertReplace("등록되었습니다.    ", "./adm_mem_list.php");
        
    } else if($mode=="UPD") {
        if (empty($userid)) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
            exit;
        }
        
        if (empty($rm_name)) {
            JsUtil::alertBack("이름을 입력해 주십시오.   ");
            exit;
        }
        
        if (empty($rm_wallet_addr)) {
            JsUtil::alertBack("전자지갑 주소를 입력해 주십시오.   ");
            exit;
        }
        
        if (substr($rm_wallet_addr,0,2) != "0x") {
            JsUtil::alertBack("전자지갑 주소는 16진수로 입력해 주십시오.   ");
            exit;
        }
        
        $row_mem = MemberMgr::getInstance()->getByKey($userid);
        
        if (empty($row_mem)) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        
        /*********************** DB에 미반영된 Payouts Data 가져오기 Start ***********************/
        
        $wq = new WhereQuery(true, true);
        //$wq->addAndString("rm_wallet_addr","=",$wallet_addr);
        $wq->addAndString("userid","=",$userid);
        $wq->addOrderBy("paidOn","desc");
        
        $row = PayoutsMgr::getInstance()->getFirst($wq);
        
        if(empty($row)) {
            $legacy_paidOn = 0;
        } else {
            $legacy_paidOn = $row["paidOn"];
        }
        
        $url = "https://api.ethermine.org/miner/".$row_mem["rm_wallet_addr"]."/payouts";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response  = curl_exec($ch);
        
        curl_close($ch);
        
        $arr_payouts = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
        
        if($arr_payouts["status"] == "OK") {
            
            if (count($arr_payouts["data"]) > 0) {
                for($i=0;$i<count($arr_payouts["data"]);$i++) {
                    if($arr_payouts["data"][$i]["paidOn"] > $legacy_paidOn) {
                        $arrVal = array();
                        $arrVal["userid"] = $userid;
                        $arrVal["rm_wallet_addr"] = $row_mem["rm_wallet_addr"];
                        $arrVal["start"] = $arr_payouts["data"][$i]["start"];
                        $arrVal["end"] = $arr_payouts["data"][$i]["end"];
                        $arrVal["amount"] = $arr_payouts["data"][$i]["amount"];
                        $arrVal["txHash"] = $arr_payouts["data"][$i]["txHash"];
                        $arrVal["paidOn"] = $arr_payouts["data"][$i]["paidOn"];
                        $arrVal["paidOnTxt"] = date("Y년 m월 d일\nH시 i분 s초",$arr_payouts["data"][$i]["paidOn"]);
                        
                        PayoutsMgr::getInstance()->add($arrVal);
                    } else {
                        break;
                    }
                }
            }
        }
        
        /*********************** DB에 미반영된 Payouts Data 가져오기 End ***********************/
        
        $uq = new UpdateQuery();
        $uq->add("rm_name", $rm_name);
        $uq->add("rm_wallet_addr", substr($rm_wallet_addr,2,strlen($rm_wallet_addr)-2));
        
        if (!empty($passwd)) {
            $uq->addWithBind("passwd", $passwd, "password('?')");
        }
        
        MemberMgr::getInstance()->edit($uq, $userid);
        
        JsUtil::alertReplace("수정되었습니다.    ", "./adm_mem_list.php");
        
    } else if($mode=="DEL") {
        
        if (empty($userid)) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x03)   ");
            exit;
        }
        
        $row_mem = MemberMgr::getInstance()->getByKey($userid);
        
        if (empty($row_mem)) {
            JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
            exit;
        }
        
        /*********************** DB에 미반영된 Payouts Data 가져오기 Start ***********************/
        
        $wq = new WhereQuery(true, true);
        //$wq->addAndString("rm_wallet_addr","=",$wallet_addr);
        $wq->addAndString("userid","=",$userid);
        $wq->addOrderBy("paidOn","desc");
        
        $row = PayoutsMgr::getInstance()->getFirst($wq);
        
        if(empty($row)) {
            $legacy_paidOn = 0;
        } else {
            $legacy_paidOn = $row["paidOn"];
        }
        
        $url = "https://api.ethermine.org/miner/".$row_mem["rm_wallet_addr"]."/payouts";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response  = curl_exec($ch);
        
        curl_close($ch);
        
        $arr_payouts = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
        
        if($arr_payouts["status"] == "OK") {
            
            if (count($arr_payouts["data"]) > 0) {
                for($i=0;$i<count($arr_payouts["data"]);$i++) {
                    if($arr_payouts["data"][$i]["paidOn"] > $legacy_paidOn) {
                        $arrVal = array();
                        $arrVal["userid"] = $userid;
                        $arrVal["rm_wallet_addr"] = $row_mem["rm_wallet_addr"];
                        $arrVal["start"] = $arr_payouts["data"][$i]["start"];
                        $arrVal["end"] = $arr_payouts["data"][$i]["end"];
                        $arrVal["amount"] = $arr_payouts["data"][$i]["amount"];
                        $arrVal["txHash"] = $arr_payouts["data"][$i]["txHash"];
                        $arrVal["paidOn"] = $arr_payouts["data"][$i]["paidOn"];
                        $arrVal["paidOnTxt"] = date("Y년 m월 d일\nH시 i분 s초",$arr_payouts["data"][$i]["paidOn"]);
                        
                        PayoutsMgr::getInstance()->add($arrVal);
                    } else {
                        break;
                    }
                }
            }
        }
        
        /*********************** DB에 미반영된 Payouts Data 가져오기 End ***********************/
        
        MemberMgr::getInstance()->delete($userid);
        
        JsUtil::alertReplace("삭제되었습니다.    ", "./adm_mem_list.php");
        
    } else {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
    }
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>