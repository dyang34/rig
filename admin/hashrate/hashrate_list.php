<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

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

if (RequestUtil::isMobileAgent()) {
    header("Location:/admin/m/".basename($_SERVER['REQUEST_URI']));;
    exit;
}

$menuCate = 2;
$menuNo = 2;

$arrDayOfWeek = array("일","월","화","수","목","금","토");

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");
$_lastSeen_date_from = RequestUtil::getParam("_lastSeen_date_from", date("Y-m-01"));
$_lastSeen_date_to = RequestUtil::getParam("_lastSeen_date_to", date("Y-m-d"));
$_userid = RequestUtil::getParam("_userid", "");
$_lower_average_hashrate = RequestUtil::getParam("_lower_average_hashrate", "");
$_order_by = RequestUtil::getParam("_order_by", "lastSeen_date");
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
        
        array_push($arrUser, $row);
    }
}

$wq = new WhereQuery(true, true);
$wq->addAndString("lastSeen_date", ">=", $_lastSeen_date_from);
$wq->addAndStringBind("lastSeen_date", "<", $_lastSeen_date_to, "date_add('?', interval 1 day)");
$wq->addAndString("userid","=",$_userid);
$wq->addAndString("currentHashrate","<=",$_lower_average_hashrate*1000*1000*1000);

$wq->addOrderBy($_order_by, $_order_by_asc);
$wq->addOrderBy("lastSeen_date","desc");

$rs = CurrentStatsMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/admin/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php";
?>

    <form name="pageForm" method="get">
        <input type="hidden" name="currentPage" value="<?=$currentPage?>">
        <input type="hidden" name="_lastSeen_date_from" value="<?=$_lastSeen_date_from?>">
        <input type="hidden" name="_lastSeen_date_to" value="<?=$_lastSeen_date_to?>">
        <input type="hidden" name="_userid" value="<?=$_userid?>">
        <input type="hidden" name="_lower_average_hashrate" value="<?=$_lower_average_hashrate?>">
        <input type="hidden" name="_order_by" value="<?=$_order_by?>">
        <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
    </form>

    <div>
        <div style="padding-left:20px;">
            <h3 class="icon-search">HashRate 명세</h3>
            <ul class="icon_Btn">
                <li><a href="#" name="btnExcelDownload">엑셀</a></li>
            </ul>
        </div>

        <form name="searchForm" method="get" action="hashrate_list.php">
            <div class="search">
            	<table class="adm-table">
            		<caption>검색</caption>
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
                			<td><input type="date" id="_lastSeen_date_from" name="_lastSeen_date_from" class="date_in" value="<?=$_lastSeen_date_from?>" style="padding:0 16px;">~<input type="date" id="_lastSeen_date_to" name="_lastSeen_date_to" value="<?=$_lastSeen_date_to?>" class="date_in" style="padding:0 16px;"></td>
                			<th>회원</th>
                			<td>
                                <select name="_userid" class="sel_channel">
                						<option value="">전체</option>
                						<?php
                						foreach($arrUser as $lt){
                							?>
                							<option value="<?=$lt['userid']?>" <?=$_userid==$lt['userid']?"selected":""?>><?="[".$lt['userid']."] ".$lt['rm_name']?></option>
                							<?php
                						}
                						?>
                					</select>
                            </td>
                			<th>Current Hashrate 제한</th>
                			<td><input type="text" name="_lower_average_hashrate" id="_lower_average_hashrate" placeholder="Giga Hashrate" value="<?=$_lower_average_hashrate?>" style="width:50%">GH/s이하</td>
                		</tr>
                	</tbody>
            	</table>
            </div>
            <div class="wms_searchBtn">
        		<a href="#" class="ism_btnSearch" name="btnSearch">검색</a>
        	</div>
            				
        </form>
	</div>

	<div class="float-wrap">
		<h3 class="float-l">전체 <strong><?=number_format($pg->getTotalCount())?>건</strong></h3>
		<p class="list-adding float-r">
            <a href="#none" name="_btn_sort" order_by="lastSeen_date" order_by_asc="desc" class="<?=$_order_by=="lastSeen_date" && $_order_by_asc=="desc"?"on":""?>">탐색일<em>▼</em></a>
            <a href="#none" name="_btn_sort" order_by="userid" order_by_asc="asc" class="<?=$_order_by=="userid" && $_order_by_asc=="asc"?"on":""?>">아이디순<em>▲</em></a>
            <a href="#none" name="_btn_sort" order_by="userid" order_by_asc="desc" class="<?=$_order_by=="userid" && $_order_by_asc=="desc"?"on":""?>">아이디순<em>▼</em></a>
		</p>
	</div>
			
<?php
if ( $rs->num_rows > 0 ) {
?>	
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
            <col>    		
            <col>    		
    	</colgroup>
        <thead>
            <tr>
                <th class="tbl_first">ID</th>
                <th>이름</th>
                <th>탐색일</th>
                <th>등록일</th>
                <th>Current Hashrate</th>
<?php /*
                <th>Average Hashrate</th>
*/ ?>
                <th>Reported Hashrate</th>
                <th>valiedShares</th>
                <th>activeWorkers</th>
                <th>Coin/Min</th>
            </tr>
        </thead>
		<tbody style="border-bottom: 2px solid #395467">
<?php
    $txt_date = "";

    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();

        $idx_day_of_week = date('w', strtotime(substr($row["lastSeen_date"],0,10)));

        $txt_date = substr($row["lastSeen_date"], 0, 10);
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
                <td class="tbl_first" style="text-align:center;"><?=$row["userid"]?></td>
                <td style="text-align:center;"><?=$row["rm_name"]?></td>
                <td style="text-align:center;"><?=$txt_date?></td>
                <td style="text-align:center;"><?=$row["time_date"]?></td>
<?php
/*
                <td style="text-align:right;"><?=number_format($row["averageHashrate"]/1024/1024/1024, 1)?>GH/s</td>
                <td style="text-align:right;"><?=number_format($row["currentHashrate"]/1024/1024/1024, 1)?>GH/s</td>
                <td style="text-align:right;"><?=number_format($row["reportedHashrate"]/1024/1024/1024, 1)?>GH/s</td>
*/
?>
                <td style="text-align:right;"><?=number_format($row["currentHashrate"]/1000/1000/1000, 1)?>GH/s</td>
<?php /*
                <td style="text-align:right;"><?=number_format($row["averageHashrate"]/1000/1000/1000, 1)?>GH/s</td>
*/ ?>
                <td style="text-align:right;"><?=number_format($row["reportedHashrate"]/1000/1000/1000, 1)?>GH/s</td>
                <td style="text-align:right;"><?=number_format($row["validShares"], 0)?></td>
                <td style="text-align:right;"><?=number_format($row["activeWorkers"], 0)?></td>
                <td style="text-align:right;"><?=number_format($row["coinsPerMin"], 5)?> ETH</td>
                
                
                </td>
            </tr>
<?php
    }
?>
		</tbody>
	</table>
	<p class="hide"><strong>Pagination</strong></p>
	<div style="position: relative;">
		<?=$pg->getNaviForFuncGP("goPage", "<<", "<", ">", ">>")?>
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

    $(document).on('click', 'a[name=_btn_sort]', function() {
        goSort($(this).attr('order_by'), $(this).attr('order_by_asc'));
    });

    var goSort = function(p_order_by, p_order_by_asc) {
        var f = document.pageForm;
        f.currentPage.value = 1;
        f._order_by.value = p_order_by;
        f._order_by_asc.value = p_order_by_asc;

        f.action = "hashrate_list.php";
        f.submit();
    }

</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php";

@ $rs->free();
?>