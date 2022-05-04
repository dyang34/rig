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

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "25");
$_reg_date_from = RequestUtil::getParam("_reg_date_from", date("Y-m-01"));
$_reg_date_to = RequestUtil::getParam("_reg_date_to", date("Y-m-d"));
$_userid = RequestUtil::getParam("_userid", "");
$_orderby = RequestUtil::getParam("_orderby", "paidOn");

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

switch($_orderby) {
    case "paidOn":
        $wq->addOrderBy("paidOn","desc");
        break;
    case "userid":
        $wq->addOrderBy("userid","asc");
        break;
    case "userid_desc":
            $wq->addOrderBy("userid","desc");
            break;
}
$wq->addOrderBy("paidOn","desc");
$wq->addOrderBy("userid","asc");

$rs = PayoutsMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/header.php";
?>

    <form name="pageForm" method="get">
        <input type="hidden" name="currentPage" value="<?=$currentPage?>">
        <input type="hidden" name="_reg_date_from" value="<?=$_reg_date_from?>">
        <input type="hidden" name="_reg_date_to" value="<?=$_reg_date_to?>">
        <input type="hidden" name="_userid" value="<?=$_userid?>">
        <input type="hidden" name="_orderby" value="<?=$_orderby?>">
    </form>

    <span class="ism_order">Payouts 명세</span>
    <form name="searchForm" method="get" action="payouts_list.php">
        <div class="ism_Inp_wrap">
            <div class="ism_total_input">
                <ul class="ism_ShipInp">
                    <li>
                        <input type="date" id="_reg_date_from" name="_reg_date_from" value="<?=$_reg_date_from?>" style="padding: 0 16px; width: 50% !important; flex: unset;">&nbsp;~&nbsp;
                        <input type="date" id="_reg_date_to" name="_reg_date_to" value="<?=$_reg_date_to?>" style="padding: 0 16px; width: 50% !important; flex: unset;">
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
                            <option value="paidOn" <?=$_orderby=="paidOn"?"selected='selected'":""?>>지급일순</option>
                            <option value="userid" <?=$_orderby=="userid"?"selected='selected'":""?>>아이디순 ▲</option>
                            <option value="userid_desc" <?=$_orderby=="userid_desc"?"selected='selected'":""?>>아이디순 ▼</option>
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
                        <th>지급일</th>
                        <th>지급(ETH)</th>
                    </tr>
                </thead>
                <tbody>
<?php
    $txt_date = "";

    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();
?>
            <tr>
                <td class="tbl_first" style="text-align:center;"><?=$arrUser[$row["userid"]]?></td>
                <td class="txt_c"><?=nl2br($row["paidOnTxt"])?></td>
                <td style="text-align:center;"><?=number_format($row["amount"]/1000000000000000000, 5)?>&nbsp;ETH</td>
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

        if ( VC_inValidDate(f._reg_date_from, "지급 시작일") ) return false;
        if ( VC_inValidDate(f._reg_date_to, "지급 종료일") ) return false;

        f.submit();	
    });

</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php";

@ $rs->free();
?>