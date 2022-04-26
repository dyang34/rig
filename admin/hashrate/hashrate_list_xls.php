<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/miner/CurrentStatsMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";

// ini_set('memory_limit','512M');

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
    exit;
}

$arrDayOfWeek = array("일","월","화","수","목","금","토");

$_time_date_from = RequestUtil::getParam("_time_date_from", date("Y-m-01"));
$_time_date_to = RequestUtil::getParam("_time_date_to", date("Y-m-d"));
$_userid = RequestUtil::getParam("_userid", "");
$_lower_average_hashrate = RequestUtil::getParam("_lower_average_hashrate", "");
$_orderby = RequestUtil::getParam("_orderby", "time_date");

$wq = new WhereQuery(true, true);
$wq->addAndString("time_date", ">=", $_time_date_from);
$wq->addAndStringBind("time_date", "<", $_time_date_to, "date_add('?', interval 1 day)");
$wq->addAndString("userid","=",$_userid);
$wq->addAndString("currentHashrate","<=",$_lower_average_hashrate*1000*1000*1000);

switch($_orderby) {
    case "time_date":
        $wq->addOrderBy("time_date","desc");
        break;
    case "userid":
        $wq->addOrderBy("userid","asc");
        break;
}
$wq->addOrderBy("time_date","desc");

$rs = CurrentStatsMgr::getInstance()->getList($wq);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=루시어돈_Hashrate 명세(".$_time_date_from."_".$_time_date_to.")_".date('Ymd').".xls");
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
        <th style="color:white;background-color:#000081;">등록일</th>
        <th style="color:white;background-color:#000081;">요일</th>
        <th style="color:white;background-color:#000081;">탐색일</th>
        <th style="color:white;background-color:#000081;">Current Hashrate(H/s)</th>
        <th style="color:white;background-color:#000081;">Average Hashrate(H/s)</th>
        <th style="color:white;background-color:#000081;">Reported Hashrate(H/s)</th>
        <th style="color:white;background-color:#000081;">valiedShares</th>
        <th style="color:white;background-color:#000081;">activeWorkers</th>
        <th style="color:white;background-color:#000081;">Coin/Min(ETH)</th>
    </tr>
<?php
if ( $rs->num_rows > 0 ) {
    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();

        $idx_day_of_week = date('w', strtotime(substr($row['time_date'],0,10)));
?>
    <tr>
        <td class="tbl_first" style="text-align:center;"><?=$row["userid"]?></td>
        <td style="text-align:center;"><?=$row["rm_name"]?></td>
        <td style="text-align:center;"><?=$row["time_date"]?></td>
        <td style="text-align:center;<?=$idx_day_of_week=="6"?"color:blue;":($idx_day_of_week=="0"?"color:red;":"")?>"><?=$arrDayOfWeek[$idx_day_of_week]?></td>
        <td style="text-align:center;"><?=$row["lastSeen_date"]?></td>
        <td style="text-align:right;"><?=number_format($row["currentHashrate"], 1)?></td>
        <td style="text-align:right;"><?=number_format($row["averageHashrate"], 1)?></td>
        <td style="text-align:right;"><?=number_format($row["reportedHashrate"], 1)?></td>
        <td style="text-align:right;"><?=number_format($row["validShares"], 0)?></td>
        <td style="text-align:right;"><?=number_format($row["activeWorkers"], 0)?></td>
        <td style="text-align:right;"><?=number_format($row["coinsPerMin"], 5)?></td>
        
        
        </td>
    </tr>
<?php
    }
}
?>
	</table>
<?php
@ $rs->free();
?>