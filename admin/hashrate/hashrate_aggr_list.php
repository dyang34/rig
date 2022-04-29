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

if (RequestUtil::isMobileAgent()) {
    header("Location:/admin/m/".basename($_SERVER['REQUEST_URI']));;
    exit;
}

$menuCate = 2;
$menuNo = 3;

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");

$_lastSeen_date_from = RequestUtil::getParam("_lastSeen_date_from", date("Y-m-01"));
$_lastSeen_date_H_from = RequestUtil::getParam("_lastSeen_date_H_from", '0');
$_lastSeen_date_to = RequestUtil::getParam("_lastSeen_date_to", date("Y-m-d"));
$_interval_H = RequestUtil::getParam("_interval_H", '4');
$_userid = RequestUtil::getParam("_userid", "");
//$_fg_continuous = RequestUtil::getParam("_fg_continuous", "N");

$_order_by = RequestUtil::getParam("_order_by", "lastSeen_date_min");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

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

$wq->addOrderBy($_order_by, $_order_by_asc);
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

include $_SERVER['DOCUMENT_ROOT']."/admin/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">

    <input type="hidden" name="_lastSeen_date_from" value="<?=$_lastSeen_date_from?>">
    <input type="hidden" name="_lastSeen_date_to" value="<?=$_lastSeen_date_to?>">
    <input type="hidden" name="_lastSeen_date_H_from" value="<?=$_lastSeen_date_H_from?>">
    <input type="hidden" name="_interval_H" value="<?=$_interval_H?>">
    <input type="hidden" name="_userid" value="<?=$_userid?>">
    <input type="hidden" name="_fg_continuous" value="<?=$_fg_continuous?>">
    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
</form>

            <!-- 상품검색(s) -->
            <div>
                <div style="padding-left:20px;">
                    <h3 class="icon-search">Current HashRate 집계</h3>
                    <ul class="icon_Btn">
                        <li><a href="#" name="btnExcelDownload">엑셀</a></li>
                    </ul>
                </div>
				<form name="searchForm" method="get" action="hashrate_aggr_list.php">
				
                    <table class="adm-table">
                        <caption>상품 검색</caption>
                        <colgroup>
                            <col style="width:8%;">
                            <col style="width:25%;">
                            <col style="width:9%;">
                            <col style="width:25%;">
                            <col style="width:8%;">
                            <col style="width:25%;">
                        </colgroup>
                        <tbody>
							<tr>
                                <th>탐색일</th>
                                <td colspan="3"><input type="date" id="_lastSeen_date_from" name="_lastSeen_date_from" class="date_in" value="<?=$_lastSeen_date_from?>" style="padding:0 16px;">&nbsp;<input type="text" name="_lastSeen_date_H_from" id="_lastSeen_date_H_from" placeholder="시" maxlength="2" value="<?=$_lastSeen_date_H_from?>" style="width:40px">시~<input type="date" id="_lastSeen_date_to" name="_lastSeen_date_to" value="<?=$_lastSeen_date_to?>" class="date_in" style="padding:0 16px;">까지&nbsp;<input type="text" name="_interval_H" id="_interval_H" placeholder="시" maxlength="4" value="<?=$_interval_H?>" style="width:60px">시간 단위 집계</td>
                                <th>회원</th>
                                <td>
                                    <select name="_userid" class="sel_channel">
                                            <option value="">전체</option>
<?php                                     	
foreach($arrUser as $key => $value) {
?>
                                    	<option value="<?=$key?>" <?=$_userid==$key?"selected":""?>><?="[".$key."] ".$value?></option>
<?php
}
?>
                                        </select>
                                </td>
							</tr>
                        </tbody>
                    </table>
    				<!-- 검색버튼 START -->
    				<div class="wms_searchBtn">
    					<a href="#" class="ism_btnSearch" name="btnSearch">검색</a>
    				</div>
    				<!-- 검색버튼 END -->
				</form>
			</div>
			<!-- 상품검색(e) -->
                
<div class="ism_menu_tab">
    <ul>
        <li class="menu_entire active" ism_tab="A">
            <a href="#">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor" style="width: 16px;">
                    <path d="M10 13a2 2 0 110-4 2 2 0 010 4zm0-2.5a.5.5 0 100 1 .5.5 0 000-1z"></path>
                    <path d="M20.3 11.8h-8.8a.8.8 0 010-1.6h8.8a.8.8 0 010 1.6zM8.5 11.8H3.7a.8.8 0 010-1.6h4.8a.8.8 0 010 1.6zM15 19a2 2 0 110-4 2 2 0 010 4zm0-2.5a.5.5 0 100 1 .5.5 0 000-1z"></path>
                    <path d="M20.3 17.8h-3.8a.8.8 0 010-1.6h3.8a.8.8 0 010 1.6zM13.5 17.8H3.7a.8.8 0 010-1.6h9.8a.8.8 0 010 1.6z"></path>
                    <path d="M21.3 23H2.6A2.8 2.8 0 010 20.2V3.9C0 2.1 1.2 1 2.8 1h18.4C22.9 1 24 2.2 24 3.8v16.4c0 1.6-1.2 2.8-2.8 2.8zM2.6 2.5c-.6 0-1.2.6-1.2 1.3v16.4c0 .7.6 1.3 1.3 1.3h18.4c.7 0 1.3-.6 1.3-1.3V3.9c0-.7-.6-1.3-1.3-1.3z"></path>
                    <path d="M23.3 6H.6a.8.8 0 010-1.5h22.6a.8.8 0 010 1.5z"></path>
                </svg>
	     Table
            </a>
        </li>
        <li class="menu_entire" ism_tab="B">
            <a href="#">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="currentColor" style="width: 16px;">
                    <path d="M76 240c12.1 0 23.1-4.8 31.2-12.6l44.2 22A44.9 44.9 0 00196 300a45 45 0 0040.6-64.4l60-60a45 45 0 0062.3-54l52.2-39.2a45 45 0 10-18-24l-52.2 39.2a45 45 0 00-65.5 56.8l-60 60a44.7 44.7 0 00-50.6 8.2l-44.2-22A44.9 44.9 0 0076 150a45 45 0 000 90zM436 30a15 15 0 110 30 15 15 0 010-30zm-120 90a15 15 0 110 30 15 15 0 010-30zM196 240a15 15 0 110 30 15 15 0 010-30zM76 180a15 15 0 110 30 15 15 0 010-30zm0 0"></path>
                    <path d="M497 482h-16V165a15 15 0 00-15-15h-60a15 15 0 00-15 15v317h-30V255a15 15 0 00-15-15h-60a15 15 0 00-15 15v227h-30V375a15 15 0 00-15-15h-60a15 15 0 00-15 15v107h-30V315a15 15 0 00-15-15H46a15 15 0 00-15 15v167H15a15 15 0 100 30h482a15 15 0 100-30zm-76-302h30v302h-30zm-120 90h30v212h-30zM181 390h30v92h-30zM61 330h30v152H61zm0 0"></path>
                </svg>
	     Chart
            </a>
        </li>
       
    </ul>
</div>

                
<script type="text/javascript">
    $(document).on('click','.menu_entire',function(){
        $('.menu_entire').removeClass('active');
        $(this).addClass('active');
								
        $('.M_tab').hide();
        $('.main_T_'+$(this).attr('ism_tab')).show();
								
        return false;
    });
</script>    
                
	<div class="display_wrap M_tab main_T_A">                
			<div class="float-wrap">
				<h3 class="float-l">전체 <strong><?=number_format($pg->getTotalCount())?>건</strong></h3>
				<p class="list-adding float-r">
                    <a href="#none" name="_btn_sort" order_by="lastSeen_date_min" order_by_asc="desc" class="<?=$_order_by=="lastSeen_date" && $_order_by_asc=="desc"?"on":""?>">탐색일<em>▼</em></a>
                    <a href="#none" name="_btn_sort" order_by="userid" order_by_asc="asc" class="<?=$_order_by=="userid" && $_order_by_asc=="asc"?"on":""?>">아이디순<em>▲</em></a>
                    <a href="#none" name="_btn_sort" order_by="userid" order_by_asc="desc" class="<?=$_order_by=="userid" && $_order_by_asc=="desc"?"on":""?>">아이디순<em>▼</em></a>
				</p>
			</div>
           
            <!-- 메인TABLE(s) -->
            <table class="display odd_color" cellpadding="0" cellspacing="0">
            	<colgroup>
                <col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
                    <col>
            		<col>
            	</colgroup>
                <thead>
                    <tr>
                        <th class="tbl_first">ID</th>
                        <th>이름</th>
                        <th>시작일시</th>
                        <th>종료일시</th>
                        <th>Current Hashrate 평균</th>
<?php /*
                        <th>Average Hashrate 평균</th>
*/?>        
                        <th>Reported Hashrate 평균</th>
                        <th>validShares 평균</th>
                        <th>activeWorkers 평균</th>
                        <th>Coin/Min 평균</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 2px solid #395467">
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
?>
                    
                    <tr>
<?php /*                    
                    	<td class="tbl_first" style="text-align:center;"><?=number_format($pg->getMaxNumOfPage() - $i)?></td>
*/?>
                        <td class="tbl_first txt_c"><?=$row['userid']?></td>
                        <td class="txt_c"><?=$arrUser[$row["userid"]]?></td>
                        <td class="txt_c"><?=$row["lastSeen_date_min"]?></td>
                        <td class="txt_c"><?=$row["lastSeen_date_max"]?></td>
                        <td class="txt_r"><?=number_format($row["currentHashrate"]/1000/1000/1000, 1)?>GH/s</td>
<?php /*
                        <td class="txt_r"><?=number_format($row["averageHashrate"]/1000/1000/1000, 1)?>GH/s</td>
*/?>        
                        <td class="txt_r"><?=number_format($row["reportedHashrate"]/1000/1000/1000, 1)?>GH/s</td>
                        <td class="txt_r"><?=number_format($row["validShares"], 0)?></td>
                        <td class="txt_r"><?=number_format($row["activeWorkers"], 0)?></td>
                        <td class="txt_r"><?=number_format($row["coinsPerMin"], 5)?> ETH</td>

                    </tr>
<?php
    }
} else {
?>
					<tr><td colspan="6" class="txt_c">No Data.</td></tr>
<?php
}
?>
                </tbody>
            </table>
            <!-- 메인TABLE(e) -->
			<p class="hide"><strong>Pagination</strong></p>
			<div style="position: relative;">
    			<?=$pg->getNaviForFuncGP("goPage", "<<", "<", ">", ">>")?>
<?php /*    			
    			<div style="position: absolute; right: 17px; bottom: 3px; text-align: center; line-height: 30px; border-radius: 10px; background-color: #313A3D;" class="rig_new"><a href="./goods_write.php" style="display:inline-block;padding: 5px 22px;color: #fff;">등록하기</a></div>
*/?>
    		</div>
</div>
    	
<div class="display_wrap M_tab main_T_B" style="display: none; ">
    <div class="float-wrap">
    <div class="container">
	<canvas id="myChart"></canvas> 
</div>
    
    </div>
</div>
    	
<?php
/*
		<a href="#none" onclick="javascript:goPageTop();"  style="position: fixed; right: 31px; bottom: 31px; width: 67px; height: 67px; line-height: 70px; background-color: #313A3D; border: none; border-radius: 50%; z-index: 999; box-sizing: border-box; color: #fff; letter-spacing: .3px; text-align: center;">TOP<img src="/images/common/top.png" alt="" style=" margin: -2px 0 0 2px;"/></a>
*/
?>        
    		


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

$(document).ready(function() {

//	getSelChannel("");
	
});

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

var goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "hashrate_aggr_list.php";
	f.submit();
}

$(document).on('click', 'a[name=_btn_sort]', function() {
	goSort($(this).attr('order_by'), $(this).attr('order_by_asc'));
});

var goSort = function(p_order_by, p_order_by_asc) {
	var f = document.pageForm;
	f.currentPage.value = 1;
	f._order_by.value = p_order_by;
	f._order_by_asc.value = p_order_by_asc;
	f.action = "hashrate_aggr_list.php";
	f.submit();
}

</script>            
<?php
include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php";

@ $rs->free();
@ $rs2->free();
?>