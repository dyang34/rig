<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/miner/CurrentStatsMgr.php";

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
    exit;
}

$arrDayOfWeek = array("일","월","화","수","목","금","토");

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");
$_lastSeen_date_from = RequestUtil::getParam("_lastSeen_date_from", date("Y-m-01"));
$_lastSeen_date_to = RequestUtil::getParam("_lastSeen_date_to", date("Y-m-d"));
$_userid = RequestUtil::getParam("_userid", "");
$_lower_average_hashrate = RequestUtil::getParam("_lower_average_hashrate", "");
$_orderby = RequestUtil::getParam("_orderby", "lastSeen_date");

$pg = new Page($currentPage, $pageSize);

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

$wq = new WhereQuery(true, true);
$wq->addAndString("lastSeen_date", ">=", $_lastSeen_date_from);
$wq->addAndStringBind("lastSeen_date", "<", $_lastSeen_date_to, "date_add('?', interval 1 day)");
$wq->addAndString("userid","=",$_userid);
$wq->addAndString("currentHashrate","<=",$_lower_average_hashrate*1000*1000*1000);

switch($_orderby) {
    case "lastSeen_date":
        $wq->addOrderBy("lastSeen_date","desc");
        break;
    case "userid":
        $wq->addOrderBy("userid","asc");
        break;
    case "userid_desc":
            $wq->addOrderBy("userid","desc");
            break;
    case "current":
        $wq->addOrderBy("currentHashrate","asc");
        break;
    case "current_desc":
        $wq->addOrderBy("currentHashrate","desc");
        break;
}
$wq->addOrderBy("lastSeen_date", "desc");
$wq->addOrderBy("userid","asc");

$rs = CurrentStatsMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">
    <input type="hidden" name="_lastSeen_date_from" value="<?=$_lastSeen_date_from?>">
    <input type="hidden" name="_lastSeen_date_to" value="<?=$_lastSeen_date_to?>">
    <input type="hidden" name="_userid" value="<?=$_userid?>">
    <input type="hidden" name="_lower_average_hashrate" value="<?=$_lower_average_hashrate?>">
    <input type="hidden" name="_orderby" value="<?=$_orderby?>">
</form>

<span class="ism_order">Current HashRate 명세</span>
    <form name="searchForm" method="get" action="hashrate_list.php">
        <div class="ism_Inp_wrap">
            <div class="ism_total_input">
                <ul class="ism_ShipInp">
                    <li>
                        <input type="date" id="_lastSeen_date_from" name="_lastSeen_date_from" value="<?=$_lastSeen_date_from?>" style="padding: 0 16px; width: 50% !important; flex: unset;">&nbsp;~&nbsp;
                        <input type="date" id="_lastSeen_date_to" name="_lastSeen_date_to" value="<?=$_lastSeen_date_to?>" style="padding: 0 16px; width: 50% !important; flex: unset;">
                    </li>
                    <li>
                        <select class="blm_select" name="_userid">
                            <option value="">전체 회원</option>
<?php                                     	
foreach($arrUser as $key => $value) {
?>
                                    	<option value="<?=$key?>" <?=$_userid==$key?"selected":""?>><?="[".$key."] ".$value?></option>
<?php
}
?>
                                        </select>
                    </li>
                    <li>
                        <input type="text" name="_lower_average_hashrate" id="_lower_average_hashrate" placeholder="Giga Hashrate" value="<?=$_lower_average_hashrate?>" style="width:50%">GH/s 이하
                    </li>
                    <li>
                        <select class="blm_select" name="_orderby">
                            <option value="lastSeen_date_min" <?=$_orderby=="lastSeen_date_min"?"selected='selected'":""?>>탐색일순</option>
                            <option value="userid" <?=$_orderby=="userid"?"selected='selected'":""?>>아이디순 ▲</option>
                            <option value="userid_desc" <?=$_orderby=="userid_desc"?"selected='selected'":""?>>아이디순 ▼</option>
                            <option value="current" <?=$_orderby=="current"?"selected='selected'":""?>>Current Hashrate순 ▲</option>
                            <option value="current_desc" <?=$_orderby=="current_desc"?"selected='selected'":""?>>Current Hashrate순 ▼</option>
                        </select>
                    </li>
                </ul>
                <div class="wms_searchBtn">
                    <a href="#" class="ism_btnSearch" name="btnSearch">검색</a>
                    <a href="#" class="ism_btnSearch"  name="btnExcelDownload">엑셀</a>
                </div>
            </div>
        </div>
    </form>

    
<?php
if ( $rs->num_rows > 0 ) {
?>	
    <div class="page-content clearfix">
        <div style="border-top:2px solid #796061;">
            <table cellpadding="0" cellspacing="0" border="0" class="ism_display" >
                <thead>
                    <tr>
                        <th>이름</th>
                        <th>탐색일</th>
                        <th>등록일</th>
                        <th>Current</th>
                    </tr>
                </thead>
                <tbody>
<?php
    $txt_date = "";

    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();

        $idx_day_of_week = date('w', strtotime(substr($row["lastSeen_date"],0,10)));

        $txt_date = substr($row["lastSeen_date"], 5, 5);
        if ($idx_day_of_week=="6") {
            $txt_date .= "<font color='blue'>"."(".$arrDayOfWeek[$idx_day_of_week].")"."</font>";
        } else if ($idx_day_of_week=="0") {
            $txt_date .= "<font color='red'>"."(".$arrDayOfWeek[$idx_day_of_week].")"."</font>";
        } else {
            $txt_date .= "(".$arrDayOfWeek[$idx_day_of_week].")";
        }
        $txt_date .= substr($row["lastSeen_date"], 10);
?>
            <tr>
                <td class="tbl_first" style="text-align:center;"><?=$row["rm_name"]?></td>
                <td class="txt_c"><?=$txt_date?></td>
                <td style="text-align:center;"><?=substr($row["time_date"],5)?></td>
                <td class="txt_r"><?=number_format($row["currentHashrate"]/1000/1000/1000, 1)?>GH/s</td>
                </td>
            </tr>
<?php
    }
?>                    
                </tbody>
            </table>
        </div>
    </div>

    <p class="hide"><strong>Pagination</strong></p>
	<div style="position: relative;">
		<?=$pg->getNaviForFuncGP_m("goPage", "<<", "<", ">", ">>")?>
	</div>

<?php /*
	<a href="#none" onclick="javascript:goPageTop();"  style="position: fixed; right: 31px; bottom: 31px; width: 67px; height: 67px; line-height: 70px; background-color: #313A3D; border: none; border-radius: 50%; z-index: 999; box-sizing: border-box; color: #fff; letter-spacing: .3px; text-align: center;">TOP<img src="/ism/images/common/top.png" alt="" style=" margin: -2px 0 0 2px;"/></a>
*/?>

<?php
} else {
    echo "<div style='text-align:center;padding:20px;'>No Data</div>";
}
?>

<script src="/cms/js/util/ValidCheck.js"></script>
<script type="text/javascript">
	
    function goPage(page) {
        var f = document.pageForm;
		f.currentPage.value = page;
		f.action = "hashrate_list.php";
		f.submit();
	}
	
	$(document).on('click','a[name=btnExcelDownload]', function() {
    	var f = document.pageForm;
    	f.target = "_new";
    	f.action = "hashrate_list_xls.php";
    	
    	f.submit();
    });
    
    $(document).on("click","a[name=btnSearch]",function() {
	
        var f = document.searchForm;

        if ( VC_inValidDate(f._lastSeen_date_from, "탐색일 시작일") ) return false;
        if ( VC_inValidDate(f._lastSeen_date_to, "탐색일 종료일") ) return false;

        f.submit();	
    });

</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php";

@ $rs->free();
?>