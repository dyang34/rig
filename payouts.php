<?php
require_once $_SERVER['DOCUMENT_ROOT']."/rig/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/rig/payouts/PayoutsMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/db/Page.php";

/*
 $currentPage = RequestUtil::getParam("currentPage", "1");
 $pageSize = RequestUtil::getParam("pageSize", "180");
 */

$userid = LoginManager::getUserLoginInfo("userid");
$wallet_addr = LoginManager::getUserLoginInfo("rm_wallet_addr");

if (empty($userid) || empty($wallet_addr) || !LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/rig");
}

$wq = new WhereQuery(true, true);
//$wq->addAndString("rm_wallet_addr","=",$wallet_addr);
$wq->addAndString("userid","=",$userid);
$wq->addOrderBy("paidOn","desc");

$row = PayoutsMgr::getInstance()->getFirst($wq);

if(empty($row)) {
    $legacy_paidOn = 0;
} else {
    $legacy_paidOn = $row["paidOn"];
}

$url = "https://api.ethermine.org/miner/".$wallet_addr."/payouts";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response  = curl_exec($ch);

curl_close($ch);

$arr_payouts = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);

$curr_time = time();

$MenuPage = 2;

$errMag = "";

if($arr_payouts["status"] == "OK") {
    
    if (count($arr_payouts["data"]) > 0) {
        $chart1_title = "";
        $chart1_value = "";
        for($i=0;$i<count($arr_payouts["data"]);$i++) {
            if($arr_payouts["data"][$i]["paidOn"] > $legacy_paidOn) {
                $arrVal = array();
                $arrVal["userid"] = $userid;
                $arrVal["rm_wallet_addr"] = $wallet_addr;
                $arrVal["start"] = $arr_payouts["data"][$i]["start"];
                $arrVal["end"] = $arr_payouts["data"][$i]["end"];
                $arrVal["amount"] = $arr_payouts["data"][$i]["amount"];
                $arrVal["txHash"] = $arr_payouts["data"][$i]["txHash"];
                $arrVal["paidOn"] = $arr_payouts["data"][$i]["paidOn"];
                $arrVal["paidOnTxt"] = date("Y년 m월 d일\nH시 i분 s초",$arr_payouts["data"][$i]["paidOn"]);
                
                PayoutsMgr::getInstance()->add($arrVal);
            } else {
                break;
            }
        }
    } else {
        $errMag = "No Data";;
        //        echo "No Data    ";
    }
} else {
    $errMag = "외부 연동 시스템 오류입니다.    ";;
    //    echo "외부 연동 시스템 오류입니다.    ";
}

$pg = new Page($currentPage, $pageSize);

$wq = new WhereQuery(true, true);
//$wq->addAndString("rm_wallet_addr","=",$wallet_addr);
$wq->addAndString("userid","=",$userid);
$wq->addOrderBy("paidOn","desc");

//$rs = PayoutsMgr::getInstance()->getListPerPage($wq, $pg);
$rs = PayoutsMgr::getInstance()->getList($wq);

include $_SERVER['DOCUMENT_ROOT']."/rig/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/rig/include/top_menu.php";
?>
<div class="M_tab main_T_B page-wrapper" style="padding-top:67px;">
	<div class="page-content clearfix">
<?php
if ( $rs->num_rows > 0 ) {
?>	
		<div style="border-top:2px solid #796061;">
            <table cellpadding="0" cellspacing="0" border="0" class="bit_display" >
                <thead class="bit_content_Tit">
                    <tr>
                        <th>일시</th>
                        <th>ETH</th>
                    </tr>
                </thead>
            </table>
        </div>

		<div style="overflow-x: auto;" class="bit_tblInner">
    		<table cellpadding="0" cellspacing="0" border="0" class="bit_display">
    			<tbody>
<?php
    $chart1_title = "";
    $chart1_value = "";
    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();
        
        if($i<10) {
            if($i>0) {
                $chart1_title = ",".$chart1_title;
                $chart1_value = ",".$chart1_value;
            }
            $chart1_title = "'".date("m월 d일",$row["paidOn"])."'".$chart1_title;
            $chart1_value = strval($row["amount"]/1000000000000000000).$chart1_value;
        }
        //.date("Y년 m월 d일 H시 i분 s초",$arr["data"]["workers"][$i]["time"])."<br/>";
?>
                    <tr>
                        <td><?=nl2br($row["paidOnTxt"])?></td>
                        <td><?=number_format($row["amount"]/1000000000000000000, 5)?>&nbsp;ETH</td>
                    </tr>
<?php
    }
?>
    			</tbody>
    		</table>
<?php /*    		
    		<div class="paging mt20" style="margin-bottom:20px;"><?=$pg->getNaviForFuncMc("goPage", "<<", "<", ">", ">>")?></div>
*/?>
		</div>

        <!-- chart -->
        <div style="width: 100%; margin:0 auto; padding:0;">
        	<canvas id="myChart" width="300" height="190"></canvas>
        </div>
        
        <script type="text/javascript">
        // chart start
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar', // line
            data: {
                labels: [<?=$chart1_title?>],
                datasets: [{
                    label: '지급내역',
                    backgroundColor: '#f97c0c',
                    borderColor: '#cb6102',
                    data: [<?=$chart1_value?>]
                }]
            },
            options: {
                legend: { display: true },
                title: {
                    display: true,
                    text: '최근 10일내 ETH 지불 현황',
                }
            }
        });
        // chart end
        </script>
        <!-- // chart -->
<?php
} else {
    echo "<div style='display:inline-block;width:100%;text-align:center;margin-top:20px;'>".$errMag."</div>";
}
?>
	</div>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT']."/rig/include/bottom.php";
include $_SERVER['DOCUMENT_ROOT']."/rig/include/footer.php";

@ $rs->free();
?>