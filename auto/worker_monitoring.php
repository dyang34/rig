<?php
require_once $_SERVER['DOCUMENT_ROOT']."/rig/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/rig/basic/BasicDataMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/db/WhereQuery.php";

$p_group_code = RequestUtil::getParam("p_group_code", "2");

$row_basic_data = BasicDataMgr::getInstance()->getByKey("HASHRATE_1");

$hashrate_min1 = $row_basic_data["data_val"];

$wq = new WhereQuery(true, true);
//$wq->addAndString("rm_wallet_addr","=",$wallet_addr);
$wq->addAndString2("rm_fg_del","=","0");
$wq->addAndString("rm_group_code","=",$p_group_code);

$rs = MemberMgr::getInstance()->getList($wq);

if ( $rs->num_rows > 0 ) {

    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();

        $curr_time = time();
        
        $url = "https://api.ethermine.org/miner/".$row["rm_wallet_addr"]."/dashboard";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        $response  = curl_exec($ch);
        
        curl_close($ch);
        
        $arr = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
        
        if($arr["status"] == "OK") {
            
            $activeWorker = $arr["data"]["currentStatistics"]["activeWorkers"];
            
            if (count($arr["data"]["workers"]) > 0) {

                for($j=0;$j<count($arr["data"]["workers"]);$j++) {
                    
                    if ($arr["data"]["workers"][$j]["reportedHashrate"]<=$hashrate_min1) {
                        echo $row["rm_name"]." : ".$row["rm_wallet_addr"]."<br/>";
                        echo $arr["data"]["workers"][$j]["worker"]."<br/>";
                        echo number_format($arr["data"]["workers"][$j]["reportedHashrate"]/1000000,1)." MH/s"."<br/>";
                        echo round(($curr_time-$arr["data"]["workers"][$j]["time"])/60)." 분 전"."<br/><br/>".$curr_time." ".$arr["data"]["workers"][$j]["time"];
                    }
                }
            }
            
            if($activeWorker<$j) {
                echo "활성화 대수 : ".$activeWorker."<br/>";
                echo "총 대수 : ".$j."<br/>";
            }
        }
    }
}

@ $rs->free();
?>