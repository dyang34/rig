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

if (RequestUtil::isMobileAgent()) {
    header("Location:/admin/m/".basename($_SERVER['REQUEST_URI']));;
    exit;
}

$menuCate = 1;
$menuNo = 1;

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "10");
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

include $_SERVER['DOCUMENT_ROOT']."/admin/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/admin/include/header.php";
?>

    <form name="pageForm" method="get">
        <input type="hidden" name="currentPage" value="<?=$currentPage?>">
        <input type="hidden" name="_rm_name" value="<?=$_rm_name?>">
        <input type="hidden" name="_userid" value="<?=$_userid?>">
        <input type="hidden" name="_rm_wallet_addr" value="<?=$_rm_wallet_addr?>">
        <input type="hidden" name="_orderby" value="<?=$_orderby?>">
    </form>

    <div>
        <div style="padding-left:20px;">
            <h3 class="icon-search">회원 검색</h3>
            <ul class="icon_Btn">
            	<li><a href="./adm_mem_write.php">추가</a></li>
                <li><a href="#" name="btnExcelDownload">엑셀</a></li>
            </ul>
        </div>

        <form name="searchForm" method="get" action="adm_mem_list.php">
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
                			<th>이름</th>
                			<td><input type="text" name="_rm_name" id="_rm_name" placeholder="이름으로 검색" value="<?=$_rm_name?>"></td>
                			<th>ID</th>
                			<td><input type="text" name="_userid" id="_userid" placeholder="ID로 검색" value="<?=$_userid?>"></td>
                			<th>지갑주소</th>
                			<td><input type="text" name="_rm_wallet_addr" id="_rm_wallet_addr" placeholder="지갑주소로 검색" value="<?=$_rm_wallet_addr?>" style="width:100%"></td>
        <?php /*        			
                			<th>정렬</th>
                			<td>
                				<select name="_orderby">
                					<option val="reg_date" <?=$_orderby=="reg_date"?"selected='selected'":""?>>최신등록순</option>
                					<option val="userid" <?=$_orderby=="userid"?"selected='selected'":""?>>아이디순</option>
                					<option val="rm_name" <?=$_orderby=="rm_name"?"selected='selected'":""?>>이름순</option>
                					<option val="rm_last_login" <?=$_orderby=="rm_last_login"?"selected='selected'":""?>>최근로그인순</option>
                				</select>
            				</td>
        */?>
        <?php /*
        <input type="date" id="nodate" class="date_in" style="padding:0 16px;"><label for="nodate"></label>
        */?>    				
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
		<h3 class="float-l">회원 리스트 <strong><?=number_format($pg->getTotalCount())?>건</strong></h3>
		<p class="list-adding float-r">
			<a href="#" name="btn_order_by" val="reg_date" class="<?=$_orderby=="reg_date"?"on":""?>" >최신등록순</a>
			<a href="#" name="btn_order_by" val="userid" class="<?=$_orderby=="userid"?"on":""?>" >아이디순<em>▲</em></a>
			<a href="#" name="btn_order_by" val="rm_name" class="<?=$_orderby=="rm_name"?"on":""?>" >이름순<em>▲</em></a>
			<a href="#" name="btn_order_by" val="rm_name_desc" class="<?=$_orderby=="rm_name_desc"?"on":""?>" >이름순<em>▼</em></a>
			<a href="#" name="btn_order_by" val="rm_last_login" class="<?=$_orderby=="rm_last_login"?"on":""?>" >최근로그인순</a>
			<a href="#" name="btn_order_by" val="amount_desc" class="<?=$_orderby=="amount_desc"?"on":""?>" >지급ETH<em>▼</em></a>
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
    		<col style="width:200px;">
    	</colgroup>
        <thead>
            <tr>
                <th class="tbl_first">ID</th>
                <th>이름</th>
                <th>지갑주소</th>
                <th>지급ETH<br/><font color="red">(실시간 아님. 참고용)</font></th>
                <th>최종로그인</th>
                <th>등록일</th>
                <th>작업</th>
            </tr>
        </thead>
		<tbody style="border-bottom: 2px solid #395467">
<?php
    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();
?>
            <tr>
                <td class="tbl_first" style="text-align:center;"><?=$row["userid"]?></td>
                <td style="text-align:center;"><?=$row["rm_name"]?></td>
                <td style="text-align:center;"><?="0x".$row["rm_wallet_addr"]?></td>
                <td style="text-align:right;"><?=number_format($row["amount"]/1000000000000000000, 15)?> ETH</td>
                <td style="text-align:center;"><?=$row["rm_last_login"]?></td>
                <td style="text-align:center;"><?=$row["reg_date"]?></td>
                <td style="text-align:center;"><a href="./adm_mem_write.php?userid=<?=$row["userid"]?>&mode=UPD" style=" display: inline-block; background-color: #386997; padding: 5px 14px 6px 13px; border-radius: 18px;line-height:20px; color: #fff; width:69px;">수정</a>&nbsp;<a href="https://ethermine.org/miners/<?=$row["rm_wallet_addr"]?>/dashboard" target="_blank" style=" display: inline-block; background-color: #d16a0d; padding: 5px 14px 6px 13px; border-radius: 18px;line-height:20px; color: #fff; width:100px;">이더마인</a>
                
                
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
		<div style="position: absolute; right: 17px; bottom: 3px; text-align: center; line-height: 30px; border-radius: 10px; background-color: #313A3D;" class="rig_new"><a href="./adm_mem_write.php" style="display:inline-block;padding: 5px 22px;color: #fff;">등록하기</a></div>
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
	$(document).on('click','a[name=btn_order_by]',function(e) {
		
		var f = document.pageForm;
		f.currentPage.value = 1;
		f._orderby.value=$(this).attr('val');
		f.action = "adm_mem_list.php";
		f.submit();
	
	});

	$(document).on('click','a[name=btn_order_by]',function(e) {
		
		var f = document.pageForm;
		f.currentPage.value = 1;
		f._orderby.value=$(this).attr('val');
		f.action = "adm_mem_list.php";
		f.submit();
	
	});
	
	function goPage(page) {
		var f = document.pageForm;
		f.currentPage.value = page;
		f.action = "adm_mem_list.php";
		f.submit();
	}
	
	$(document).on('click','a[name=btnExcelDownload]', function() {
    	var f = document.pageForm;
    	f.target = "_new";
    	f.action = "adm_mem_list_xls.php";
    	
    	f.submit();
    });
    
    $(document).on("click","a[name=btnSearch]",function() {
	
	var f = document.searchForm;

    f.submit();	
});
	
</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php";

@ $rs->free();
?>