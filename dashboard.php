<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

$wallet_addr = LoginManager::getUserLoginInfo("rm_wallet_addr");

if (empty($wallet_addr) || !LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$url = "https://api.ethermine.org/miner/".$wallet_addr."/dashboard";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response  = curl_exec($ch);

curl_close($ch);

$arr = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);

if($_REQUEST['fg_mode']=="debug") {
    echo "<div>abcd|".$wallet_addr."|abcd</div>";
    echo "https://api.ethermine.org/miner/".$wallet_addr."/dashboard"."<br/>";
    print_r($arr);
}

/******** currentStats start ********/
$url = "https://api.ethermine.org/miner/".$wallet_addr."/currentStats";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response  = curl_exec($ch);

curl_close($ch);

$arrCurrentStats = json_decode($response, true, 512, JSON_NUMERIC_CHECK);

$coinsPerHour = $arrCurrentStats["data"]["coinsPerMin"]*1000000;
$coinsPerHour = $coinsPerHour*60*24;
$coinsPerHour = $coinsPerHour / 1000000;

/******** currentStats end ********/

// print_r($arr);
$curr_time = time();

$MenuPage = 1;

include $_SERVER['DOCUMENT_ROOT']."/include/head.php";
include $_SERVER['DOCUMENT_ROOT']."/include/top_menu.php";

$activeWorker = $arr["data"]["currentStatistics"]["activeWorkers"];
$unpaid = $arr["data"]["currentStatistics"]["unpaid"];

?>
 <div class="M_tab main_T_B page-wrapper" style="padding-top:67px;">
    <div class="page-content clearfix">
<?php
if($arr["status"] == "OK") {
    
    if (count($arr["data"]["workers"]) > 0) {
?>
    	<div class="bit_cal" >
            <div class="bit_calInner">
                <p>
                    <span class="bit_cal_Tit">활동기기수</span>
                    <span class="bit_subCal"><?=number_format($activeWorker)?> 대</span>
                </p>
                <p>
                    <span class="bit_cal_Tit">미지급 ETH</span>
                    <span class="bit_subCal"><?=number_format($unpaid/1000000000000000000,5)?> <span style="font-weight: normal; font-size: 12px;">ETH</span></span>
                </p>
                <p>
                    <span class="bit_cal_Tit">하루 예상 채굴량</span>
                    <span class="bit_subCal"><?=number_format($coinsPerHour,5)?><span style="font-weight: normal; font-size: 12px;">ETH</span></span>
                </p>
            </div>
        </div>

		<div style="border-top:2px solid #796061;">        
        	<table cellpadding="0" cellspacing="0" border="0" class="bit_display">
                <thead class="bit_content_Tit">
                    <tr>
                        <th>기기명</th>
                        <th>해시율</th>
                        <th>업데이트 시간</th>
                    </tr>
                </thead>
			</table>
		</div>
		<div style="overflow-x: auto;" class="bit_tblInner">
            <table cellpadding="0" cellspacing="0" border="0" class="bit_display">
                <tbody>
<?php 
        for($i=0;$i<count($arr["data"]["workers"]);$i++) {
?>
                    <tr>
                        <td><?=$arr["data"]["workers"][$i]["worker"]?></td>
                        <td><?=number_format($arr["data"]["workers"][$i]["reportedHashrate"]/1000000,1)?>&nbsp;MH/s</td>
                        <td><?=round(($curr_time-$arr["data"]["workers"][$i]["time"])/60)?>분 전</td>
                    </tr>
<?php
        }
?>
                </tbody>
            </table>
		</div>
<?php
    } else {
        echo "<div style='display:inline-block;width:100%;text-align:center;margin-top:20px;'>No Data</div>";
    }
} else {
    echo "<div style='display:inline-block;width:100%;text-align:center;margin-top:20px;'>외부 연동 시스템 오류입니다.</div>";
}
?>
    </div>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/bottom.php";
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>