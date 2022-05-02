<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
    exit;
}

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "20");
$_rm_name = RequestUtil::getParam("_rm_name", "");
$_userid = RequestUtil::getParam("_userid", "");
$_rm_wallet_addr = RequestUtil::getParam("_rm_wallet_addr", "");
$_orderby = RequestUtil::getParam("_orderby", "reg_date");

$pg = new Page($currentPage, $pageSize);

$wq = new WhereQuery(true, true);
//$wq->addAndString("rm_wallet_addr","=",$wallet_addr);
$wq->addAndString2("rm_fg_del","=","0");
$wq->addAndLike("rm_name",$_rm_name);
$wq->addAndString("userid","=",$_userid);
$wq->addAndLike("rm_wallet_addr",$_rm_wallet_addr);

switch($_orderby) {
    case "reg_date":
        $wq->addOrderBy("reg_date","desc");
        break;
    case "userid":
        $wq->addOrderBy("userid","asc");
        break;
        case "userid_desc":
            $wq->addOrderBy("userid","desc");
            break;
        case "rm_name":
        $wq->addOrderBy("rm_name","asc");
        break;
    case "rm_name_desc":
        $wq->addOrderBy("rm_name","desc");
        break;
    case "rm_last_login":
        $wq->addOrderBy("rm_last_login","desc");
        break;
    case "amount_desc":
        $wq->addOrderBy("amount","desc");
        break;
}
$wq->addOrderBy("reg_date","desc");
$wq->addOrderBy("userid","asc");

$rs = MemberMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/header.php";
?>

    <form name="pageForm" method="get">
        <input type="hidden" name="currentPage" value="<?=$currentPage?>">
        <input type="hidden" name="_rm_name" value="<?=$_rm_name?>">
        <input type="hidden" name="_userid" value="<?=$_userid?>">
        <input type="hidden" name="_rm_wallet_addr" value="<?=$_rm_wallet_addr?>">
        <input type="hidden" name="_orderby" value="<?=$_orderby?>">
    </form>


    <span class="ism_order">회원 검색</span>
    <form name="searchForm" method="get" action="adm_mem_list.php">
        <div class="ism_Inp_wrap">
            <div class="ism_total_input">
                <ul class="ism_ShipInp">
                    <li>
                        <label for="_rm_name" class="ism_hide">이름으로 검색</label>
                        <input type="text" name="_rm_name" id="_rm_name" value="<?=$_rm_name?>" placeholder="이름으로 검색">
                    </li>
                    <li>
                        <label for="_userid" class="ism_hide">ID로 검색</label>
                        <input type="text" name="_userid" id="_userid" value="<?=$_userid?>" placeholder="ID로 검색">
                    </li>
                    <li>
                        <label for="_rm_wallet_addr" class="ism_hide">지갑주소로 검색</label>
                        <input type="text" name="_rm_wallet_addr" id="_rm_wallet_addr" value="<?=$_rm_wallet_addr?>" placeholder="지갑주소로 검색">
                    </li>
                    <li>
                        <select class="blm_select" name="_orderby">
                            <option value="reg_date" <?=$_orderby=="reg_date"?"selected='selected'":""?>>최신등록순</option>
                            <option value="userid" <?=$_orderby=="userid"?"selected='selected'":""?>>아이디순 ▲</option>
                            <option value="userid_desc" <?=$_orderby=="userid_desc"?"selected='selected'":""?>>아이디순 ▼</option>
                            <option value="rm_name" <?=$_orderby=="rm_name"?"selected='selected'":""?>>이름순 ▲</option>
                            <option value="rm_name_desc" <?=$_orderby=="rm_name_desc"?"selected='selected'":""?>>이름순 ▼</option>
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
                        <th>ID</th>
                        <th>이름</th>
                        <th>지갑주소</th>
                        <th>등록일</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();
?>
            <tr>
                <td class="tbl_first" style="text-align:center;"><?=$row["userid"]?></td>
                <td style="text-align:center;"><?=$row["rm_name"]?></td>
                <td style="text-align:center;"><?="0x".$row["rm_wallet_addr"]?></td>
                <td style="text-align:center;"><?=$row["reg_date"]?></td>
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


<script type="text/javascript">
	
	function goPage(page) {
		var f = document.pageForm;
		f.currentPage.value = page;
		f.action = "adm_mem_list.php";
		f.submit();
	}
	
    $(document).on("click","a[name=btnSearch]",function() {
	
	var f = document.searchForm;

    f.submit();	
});

$(document).on('click','a[name=btnExcelDownload]', function() {
    	var f = document.pageForm;
    	f.target = "_new";
    	f.action = "adm_mem_list_xls.php";
    	
    	f.submit();
    });

</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/footer.php";

@ $rs->free();
?>