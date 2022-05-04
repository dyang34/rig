<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/payouts/PayoutsMgr.php";

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
    exit;
}

if (RequestUtil::isMobileAgent()) {
    header("Location:/admin/m/".basename($_SERVER['REQUEST_URI']));;
    exit;
}

$menuCate = 3;
$menuNo = 4;

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");
$_reg_date_from = RequestUtil::getParam("_reg_date_from", date("Y-m-01"));
$_reg_date_to = RequestUtil::getParam("_reg_date_to", date("Y-m-d"));
$_userid = RequestUtil::getParam("_userid", "");
$_order_by = RequestUtil::getParam("_order_by", "paidOn");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$pg = new Page($currentPage, $pageSize);

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

$rs = PayoutsMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/admin/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php";
?>

    <form name="pageForm" method="get">
        <input type="hidden" name="currentPage" value="<?=$currentPage?>">
        <input type="hidden" name="_reg_date_from" value="<?=$_reg_date_from?>">
        <input type="hidden" name="_reg_date_to" value="<?=$_reg_date_to?>">
        <input type="hidden" name="_userid" value="<?=$_userid?>">
        <input type="hidden" name="_order_by" value="<?=$_order_by?>">
        <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
    </form>

    <div>
        <div style="padding-left:20px;">
            <h3 class="icon-search">Payouts 명세</h3>
            <ul class="icon_Btn">
                <li><a href="#" name="btnExcelDownload">엑셀</a></li>
            </ul>
        </div>

        <form name="searchForm" method="get" action="payouts_list.php">
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
                			<th>작업일</th>
                			<td><input type="date" id="_reg_date_from" name="_reg_date_from" class="date_in" value="<?=$_reg_date_from?>" style="padding:0 16px;">~<input type="date" id="_reg_date_to" name="_reg_date_to" value="<?=$_reg_date_to?>" class="date_in" style="padding:0 16px;"></td>
                			<th>회원</th>
                			<td colspan="3">
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
            </div>
            <div class="wms_searchBtn">
        		<a href="#" class="ism_btnSearch" name="btnSearch">검색</a>
        	</div>
            				
        </form>
	</div>

	<div class="float-wrap">
		<h3 class="float-l">전체 <strong><?=number_format($pg->getTotalCount())?>건</strong></h3>
		<p class="list-adding float-r">
            <a href="#none" name="_btn_sort" order_by="paidOn" order_by_asc="desc" class="<?=$_order_by=="paidOn" && $_order_by_asc=="desc"?"on":""?>">작업일순<em>▼</em></a>
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
                <th>일시</th>
                <th>지급(ETH)</th>
                <th>지갑주소</th>
                <th>Tx Hash</th>
            </tr>
        </thead>
		<tbody style="border-bottom: 2px solid #395467">
<?php
    $chart1_title = "";
    $chart1_value = "";

    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();

        if($i>0) {
            $chart1_title = ",".$chart1_title;
            $chart1_value = ",".$chart1_value;
        }
        $chart1_title = "'".date("m월 d일",$row["paidOn"])."'".$chart1_title;
        $chart1_value = strval($row["amount"]/1000000000000000000).$chart1_value;
?>
            <tr>
                <td class="tbl_first" style="text-align:center;"><?=$row["userid"]?></td>
                <td style="text-align:center;"><?=$arrUser[$row["userid"]]?></td>
                <td style="text-align:center;"><?=nl2br($row["paidOnTxt"])?></td>
                <td style="text-align:center;"><?=number_format($row["amount"]/1000000000000000000, 5)?>&nbsp;ETH</td>
                <td style="text-align:center;"><?="0x".$row["rm_wallet_addr"]?></td>
                <td style="text-align:center;"><?="0x".$row["txHash"]?></td>
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
		f.action = "payouts_list.php";
		f.submit();
	}
	
	$(document).on('click','a[name=btnExcelDownload]', function() {
    	var f = document.pageForm;
    	f.target = "_new";
    	f.action = "payouts_list_xls.php";
    	
    	f.submit();
    });
    
    $(document).on("click","a[name=btnSearch]",function() {
	
        var f = document.searchForm;

        if ( VC_inValidDate(f._reg_date_from, "시작일") ) return false;
        if ( VC_inValidDate(f._reg_date_to, "종료일") ) return false;

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

        f.action = "payouts_list.php";
        f.submit();
    }

</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php";

@ $rs->free();
?>