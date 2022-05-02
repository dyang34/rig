<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/miner/CurrentStatsMgr.php";

// ini_set('memory_limit','512M');

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
    exit;
}

$_lastSeen_date_from = RequestUtil::getParam("_lastSeen_date_from", date("Y-m-01"));
$_lastSeen_date_H_from = RequestUtil::getParam("_lastSeen_date_H_from", '0');
$_lastSeen_date_to = RequestUtil::getParam("_lastSeen_date_to", date("Y-m-d"));
$_interval_H = RequestUtil::getParam("_interval_H", '4');
$_userid = RequestUtil::getParam("_userid", "");
//$_fg_continuous = RequestUtil::getParam("_fg_continuous", "N");

$_order_by = RequestUtil::getParam("_order_by", "lastSeen_date_min");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$arrUser = array();

$wq = new WhereQuery(true, true);
$wq->addAndString2("rm_fg_del","=","0");
$wq->addAndString("rm_fg_avg_hashrate","=","1");
$wq->addOrderBy("userid","asc");
$rs = MemberMgr::getInstance()->getList($wq);
if ($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
        
        $arrUser[$row["userid"]] = $row["rm_name"];
    }
}

$lastSeen_date_h_from = $_lastSeen_date_from.' '.$_lastSeen_date_H_from;

$wq = new WhereQuery(true, true);
$wq->addAndString("lastSeen_date", ">=", $lastSeen_date_h_from);
$wq->addAndStringBind("lastSeen_date", "<", $_lastSeen_date_to, "date_add('?', interval 1 day)");
$wq->addAndString("userid", "=", $_userid);

$wq->addOrderBy($_order_by, $_order_by_asc);
$wq->addOrderBy("lastSeen_date_min", "desc");
$wq->addOrderBy("userid","asc");

$rs = CurrentStatsMgr::getInstance()->getListAvg2($wq, $lastSeen_date_h_from, $_interval_H);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=루시어돈_Hashrate 집계(".$lastSeen_date_h_from."_".$_lastSeen_date_to."_".$_interval_H.")_".date('Ymd').".xls");
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
        <th style="color:white;background-color:#000081;">시작등록일</th>
        <th style="color:white;background-color:#000081;">종료등록일</th>
        <th style="color:white;background-color:#000081;">시작탐색일</th>
        <th style="color:white;background-color:#000081;">종료탐색일</th>
        <th style="color:white;background-color:#000081;">Current 평균(H/s)</th>
        <th style="color:white;background-color:#000081;">효율(%)</th>
<?php /*        
        <th style="color:white;background-color:#000081;">Average Hashrate 평균(H/s)</th>
*/?>        
        <th style="color:white;background-color:#000081;">Reported 평균(H/s)</th>
        <th style="color:white;background-color:#000081;">validShares 평균</th>
        <th style="color:white;background-color:#000081;">activeWorkers 평균</th>
        <th style="color:white;background-color:#000081;">Coin/Min 평균(ETH)</th>
    </tr>
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
?>
    <tr>
        <td style="text-align:center;"><?=$row['userid']?></td>
        <td style="text-align:center;"><?=$arrUser[$row["userid"]]?></td>
        <td style="text-align:center;"><?=substr($row["time_date_min"],0,13)."시"?></td>
        <td style="text-align:center;"><?=substr($row["time_date_max"],0,13)."시"?></td>
        <td style="text-align:center;"><?=$row["lastSeen_date_min"]?></td>
        <td style="text-align:center;"><?=$row["lastSeen_date_max"]?></td>
        <td style="text-align:right;"><?=number_format($row["currentHashrate"],0)?></td>
        <td style="text-align:right;"><?=number_format($row["currentHashrate"]/$row["reportedHashrate"]*100, 1)?>%</td>
<?php /*        
        <td style="text-align:right;"><?=number_format($row["averageHashrate"],0)?></td>
*/?>        
        <td style="text-align:right;"><?=number_format($row["reportedHashrate"],0)?></td>
        <td style="text-align:right;"><?=number_format($row["validShares"],0)?></td>
        <td style="text-align:right;"><?=number_format($row["activeWorkers"],0)?></td>
        <td style="text-align:right;"><?=number_format($row["coinsPerMin"],5)?></td>

    </tr>
<?php
    }
}
?>
</table>
<?php
@ $rs->free();
?>