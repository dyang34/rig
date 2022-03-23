<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";

$_rm_name = RequestUtil::getParam("_rm_name", "");
$_userid = RequestUtil::getParam("_userid", "");
$_rm_wallet_addr = RequestUtil::getParam("_rm_wallet_addr", "");
$_orderby = RequestUtil::getParam("_orderby", "reg_date");

$wq = new WhereQuery(true, true);
//$wq->addAndString("rm_wallet_addr","=",$wallet_addr);
$wq->addAndString2("rm_fg_del","=","0");
$wq->addAndLike("rm_name",$_rm_name);
$wq->addAndString("userid","=",$_userid);
$wq->addAndString("rm_wallet_addr","=",$_rm_wallet_addr);

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

$rs = MemberMgr::getInstance()->getList($wq);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=루시어돈_회원 리스트_".date('Ymd').".xls");
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
	<tr style="height:30px;">
		<th style="color:white;background-color:#000081;">No</th>
		<th style="color:white;background-color:#000081;">ID</th>
		<th style="color:white;background-color:#000081;">이름</th>
		<th style="color:white;background-color:#000081;">지갑주소</th>
		<th style="color:white;background-color:#000081;">지급ETH <font color="red">(실시간 아님. 참고용)</font></th>
		<th style="color:white;background-color:#000081;">최종로그인</th>
		<th style="color:white;background-color:#000081;">등록일</th>
	</tr>
<?php
if($rs->num_rows > 0) {
    for($i=0;$i<$rs->num_rows;$i++) {
        $row = $rs->fetch_assoc();
?>
                    <tr>
                        <td class="tbl_first"><?=$i+1?></td>
                    	<td><?=$row["userid"]?></td>
                        <td><?=$row["rm_name"]?></td>
                        <td><?="0x".$row["rm_wallet_addr"]?></td>
                        <td><?=number_format($row["amount"]/1000000000000000000, 15)?> ETH</td>
                        <td><?=$row["rm_last_login"]?></td>
<?php /*                        
                        <td><?=$row["stock_qty"]?></td>
                        <td><?=$row["stock_apply_date"]?></td>
*/?>
                        <td><?=$row["reg_date"]?></td>
                    </tr>
<?php
    }
}
?>
</table>
<?php
@ $rs->free();
?>