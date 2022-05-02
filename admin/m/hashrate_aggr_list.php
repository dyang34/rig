<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/ism_ip_check.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/miner/CurrentStatsMgr.php";

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
    exit;
}

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");

$_lastSeen_date_from = RequestUtil::getParam("_lastSeen_date_from", date("Y-m-01"));
$_lastSeen_date_H_from = RequestUtil::getParam("_lastSeen_date_H_from", '0');
$_lastSeen_date_to = RequestUtil::getParam("_lastSeen_date_to", date("Y-m-d"));
$_interval_H = RequestUtil::getParam("_interval_H", '4');
$_userid = RequestUtil::getParam("_userid", "");
//$_fg_continuous = RequestUtil::getParam("_fg_continuous", "N");

$_orderby = RequestUtil::getParam("_orderby", "lastSeen_date_min");

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

$lastSeen_date_h_from = $_lastSeen_date_from.' '.$_lastSeen_date_H_from;

$wq = new WhereQuery(true, true);
$wq->addAndString("lastSeen_date", ">=", $lastSeen_date_h_from);
$wq->addAndStringBind("lastSeen_date", "<", $_lastSeen_date_to, "date_add('?', interval 1 day)");
$wq->addAndString("userid", "=", $_userid);

switch($_orderby) {
    case "lastSeen_date_min":
        $wq->addOrderBy("lastSeen_date_min","desc");
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
$wq->addOrderBy("lastSeen_date_min", "desc");
$wq->addOrderBy("userid","asc");

$rs = CurrentStatsMgr::getInstance()->getListAvg2PerPage($wq, $lastSeen_date_h_from, $_interval_H, $pg);

$wq2 = new WhereQuery(true, true);
$wq2->addAndString("lastSeen_date", ">=", $lastSeen_date_h_from);
$wq2->addAndStringBind("lastSeen_date", "<", $_lastSeen_date_to, "date_add('?', interval 1 day)");
$wq2->addAndString("userid", "=", $_userid);
$wq2->addAndIn("userid", array('gpclub1','gpclub2'));
$wq2->addOrderBy("userid", "asc");
$wq2->addOrderBy("lastSeen_date_min", "asc");

$rs2 = CurrentStatsMgr::getInstance()->getListAvg2($wq2, $lastSeen_date_h_from, $_interval_H);

$chart_label = "";
$chart_data = "";
$fg_first_chart_data = true;
$prev_userid = "";
$fg_first_user = true;
$arrColor = array('gpclub1'=>'red', 'gpclub2'=>'blue');

if ($rs2->num_rows > 0) {
    for($i=0; $i<$rs2->num_rows; $i++) {
        $row = $rs2->fetch_assoc();

        if ($prev_userid != $row["userid"]) {

            if(!empty($prev_userid)) {
                $chart_data .= "]},";

                $fg_first_user = false;
            }

            $chart_data .= "{ 
                label: '".$arrUser[$row["userid"]]." Current Hashrate (GH/s)', 
                backgroundColor: 'transparent', 
                borderColor: '".$arrColor[$row["userid"]]."', 
                data: ["
            ;

            $prev_userid = $row["userid"];
            $fg_first_chart_data=true;
        }

        if ($fg_first_user) {
            if (!empty($chart_label)) {
                $chart_label .= ",";
            }
    
            $chart_label .= "'".substr($row["lastSeen_date_min"],0, 16)."'";
        }

        if (!$fg_first_chart_data) {
            $chart_data .= ",";
        } else {
            $fg_first_chart_data=false;
        }
        
        $chart_data .= $row["currentHashrate"]/1000/1000/1000;
        
    }
}

$chart_data .= "]}";

include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">

    <input type="hidden" name="_lastSeen_date_from" value="<?=$_lastSeen_date_from?>">
    <input type="hidden" name="_lastSeen_date_to" value="<?=$_lastSeen_date_to?>">
    <input type="hidden" name="_lastSeen_date_H_from" value="<?=$_lastSeen_date_H_from?>">
    <input type="hidden" name="_interval_H" value="<?=$_interval_H?>">
    <input type="hidden" name="_userid" value="<?=$_userid?>">
    <input type="hidden" name="_fg_continuous" value="<?=$_fg_continuous?>">
    <input type="hidden" name="_orderby" value="<?=$_orderby?>">
</form>

<span class="ism_order">Current HashRate 집계</span>
    <form name="searchForm" method="get" action="hashrate_aggr_list.php">
        <div class="ism_Inp_wrap">
            <div class="ism_total_input">
                <ul class="ism_ShipInp">
                    <li>
                        <input type="date" id="_lastSeen_date_from" name="_lastSeen_date_from" value="<?=$_lastSeen_date_from?>" style="padding: 0 16px; width: 150px !important; flex: unset;">&nbsp;<input type="text" name="_lastSeen_date_H_from" id="_lastSeen_date_H_from" placeholder="시" maxlength="2" value="<?=$_lastSeen_date_H_from?>" style="width:40px">시 부터<span></span>
                    </li>
                    <li>
                        <input type="date" id="_lastSeen_date_to" name="_lastSeen_date_to" value="<?=$_lastSeen_date_to?>" style="padding: 0 16px; width: 150px !important; flex: unset;">까지&nbsp;<input type="text" name="_interval_H" id="_interval_H" placeholder="시" maxlength="4" value="<?=$_interval_H?>" style="width:50px">시간 단위
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
                        <th>시작일시<br/>(탐색시작)</th>
                        <th>종료일시<br/>(탐색종료)</th>
                        <th>Current 평균</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();
?>
            <tr>
                <td class="tbl_first" style="text-align:center;"><?=$arrUser[$row["userid"]]?></td>
                <td class="txt_c"><?=substr($row["time_date_min"],0,13)."시<div style='margin-top:5px;color:#888;'>(".$row["lastSeen_date_min"].")</div>"?></td>
                <td class="txt_c"><?=substr($row["time_date_max"],0,13)."시<div style='margin-top:5px;color:#888;'>(".$row["lastSeen_date_max"].")</div>"?></td>
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

<canvas id="myChart" height="400px"></canvas> 


<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script> 
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> 
 
 <!-- 차트 --> 
 <script> 
 var ctx = document.getElementById('myChart').getContext('2d'); 
 var chart = new Chart(ctx,  {
  // 챠트 종류를 선택
    type: 'line',
   // 챠트를 그릴 데이타 
    data: { 
        labels: [<?=$chart_label?>], 
        datasets: [<?=$chart_data?>],
        options: {}
   }});
</script>

<script src="/cms/js/util/ValidCheck.js"></script>
<script type="text/javascript">
	
	function goPage(page) {
		var f = document.pageForm;
		f.currentPage.value = page;
		f.action = "hashrate_aggr_list.php";
		f.submit();
	}
	
    function addMonth(date, month) {
    let addMonthFirstDate = new Date(date.getFullYear(),date.getMonth() + month,1);	// month달 후의 1일
    let addMonthLastDate = new Date(addMonthFirstDate.getFullYear(),addMonthFirstDate.getMonth() + 1, 0);	// month달 후의 말일
    
    let result = addMonthFirstDate;
    if(date.getDate() > addMonthLastDate.getDate()) {
    	result.setDate(addMonthLastDate.getDate());
    } else {
    	result.setDate(date.getDate());
    }
    
    return result;
}

$(document).on("click","a[name=btnSearch]",function() {
	
	var f = document.searchForm;

    if ( VC_inValidDate(f._lastSeen_date_from, "탐색일 시작일") ) return false;
    if ( VC_inValidDate(f._lastSeen_date_to, "탐색일 종료일") ) return false;

    if ( VC_inValidText(f._lastSeen_date_H_from, "탐색일 시작 시간") ) return false;
    if ( VC_inValidText(f._interval_H, "시간 간격") ) return false;

    if ( VC_inValidNumber(f._lastSeen_date_H_from, "탐색일 시작 시간") ) return false;
    if ( VC_inValidNumber(f._interval_H, "시간 간격") ) return false;

    if (f._lastSeen_date_H_from.value > 23 || f._lastSeen_date_H_from.value < 0) {
        alert("탐색일 시작 시간은 0~23 범위 내에서 입력해 주십시오.     ");
        f._lastSeen_date_H_from.focus();
        return false;
    }

	let arrFromDate=f._lastSeen_date_from.value.split('-');
	let arrToDate=f._lastSeen_date_to.value.split('-');
	
	let fromDate = addMonth(new Date(arrFromDate[0],arrFromDate[1]-1,arrFromDate[2]), 12);
	let toDate = new Date(arrToDate[0],arrToDate[1]-1,arrToDate[2]);
		
	if (fromDate <= toDate) {
		alert("최대 1년 단위로 조회하실 수 있습니다.    ");
		f._lastSeen_date_from.focus();
	
		return false;
	}

    f.submit();	
});

$(document).on('click','a[name=btnExcelDownload]', function() {

var f = document.pageForm;

let arrFromDate=f._lastSeen_date_from.value.split('-');
let arrToDate=f._lastSeen_date_to.value.split('-');

let fromDate = addMonth(new Date(arrFromDate[0],arrFromDate[1]-1,arrFromDate[2]), 12);
let toDate = new Date(arrToDate[0],arrToDate[1]-1,arrToDate[2]);

if (fromDate <= toDate) {
    alert("엑셀 다운로드는 최대 12개월 단위로 다운로드 하실 수 있습니다.    ");
    f._lastSeen_date_from.focus();

    return false;
}

f.target = "_new";
f.action = "hashrate_aggr_list_xls.php";

f.submit();
});

</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/footer.php";

@ $rs->free();
@ $rs2->free();
?>