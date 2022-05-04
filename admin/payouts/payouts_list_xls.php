<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/payouts/PayoutsMgr.php";

// ini_set('memory_limit','512M');

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
    exit;
}

$_reg_date_from = RequestUtil::getParam("_reg_date_from", date("Y-m-01"));
$_reg_date_to = RequestUtil::getParam("_reg_date_to", date("Y-m-d"));
$_userid = RequestUtil::getParam("_userid", "");
$_order_by = RequestUtil::getParam("_order_by", "paidOn");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$arrUser = array();

$wq = new WhereQuery(true, true);
$wq->addAndString2("rm_fg_del","=","0");
$wq->addAndString("rm_fg_avg_hashrate","=","1");
$wq->addOrderBy("userid","asc");
$rs = MemberMgr::getInstance()->getList($wq);

$arrUserWhere = array();
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        $arrUser[$row["userid"]] = $row["rm_name"];

        array_push($arrUserWhere, $row["userid"]);
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndIn("userid", $arrUserWhere);
$wq->addAndString("paidOn", ">=", strtotime($_reg_date_from));
$wq->addAndString("paidOn", "<", strtotime($_reg_date_to)+86400);
$wq->addAndString("userid","=",$_userid);
$wq->addOrderBy($_order_by, $_order_by_asc);
$wq->addOrderBy("paidOn","desc");
$wq->addOrderBy("userid","asc");

$rs = PayoutsMgr::getInstance()->getList($wq);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=루시어돈_Payouts 명세(".$_lastSeen_date_from."_".$_lastSeen_date_to.")_".date('Ymd').".xls");
Header("Content-Description: PHP5 Generated Data");
Header("Pragma: no-cache");
Header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");
?>
<style>
td{font-size:11px;text-align:center;}
th{font-size:11px;text-align:center;color:white;background-color:#000081;}
</style>

<table cellpadding=3 cellspacing=0 border=1 bordercolor='#bdbebd' style='border-collapse: collapse'>
    <tr>
        <th style="color:white;background-color:#000081;">ID</th>
        <th style="color:white;background-color:#000081;">이름</th>
        <th style="color:white;background-color:#000081;">일시</th>
        <th style="color:white;background-color:#000081;">지급(ETH)</th>
        <th style="color:white;background-color:#000081;">지갑주소</th>
        <th style="color:white;background-color:#000081;">Tx Hash</th>
    </tr>
<?php
if ( $rs->num_rows > 0 ) {
    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();

?>
    <tr>
        <td style="text-align:center;"><?=$row["userid"]?></td>
        <td style="text-align:center;"><?=$arrUser[$row["userid"]]?></td>
        <td style="text-align:center;"><?=str_replace("\r\n"," ",$row["paidOnTxt"])?></td>
        <td style="text-align:center;"><?=number_format($row["amount"]/1000000000000000000, 5)?>&nbsp;ETH</td>
        <td style="text-align:center;"><?="0x".$row["rm_wallet_addr"]?></td>
        <td style="text-align:center;"><?="0x".$row["txHash"]?></td>
    </tr>
<?php
    }
}
?>
	</table>
<?php
@ $rs->free();
?>