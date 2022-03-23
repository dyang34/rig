<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/miner/HistoryMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/miner/CurrentStatsMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";

$wq = new WhereQuery(true, true);
$wq->addAndStringNotQuot("time_date", ">=", "date_add(now(), interval 1 day)");

$rs_history = HistoryMgr::getInstance()->getList($wq);

$arrHistory = array();
if ( $rs_history->num_rows > 0 ) {
    for ( $i=0; $i<$rs_history->num_rows; $i++ ) {
        $row = $rs_history->fetch_assoc();
        
        array_push($arrHistory, $row["userid"]."||".$row["time"]);
    }
}

$wq = new WhereQuery(true, true);
//$wq->addAndString("rm_wallet_addr","=",$wallet_addr);
$wq->addAndString2("rm_fg_del","=","0");
$wq->addAndString2("rm_fg_avg_hashrate","=","1");

$rs = MemberMgr::getInstance()->getList($wq);

if ( $rs->num_rows > 0 ) {

    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();

        $url = "https://api.ethermine.org/miner/".$row["rm_wallet_addr"]."/currentStats";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        $response  = curl_exec($ch);
        
        curl_close($ch);
        
        $arr = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
        
        if($arr["status"] == "OK") {
            
            $arrIns = array();
            
            $arrIns["userid"] = $row["userid"];
            $arrIns["rm_name"] = $row["rm_name"];
            $arrIns["rm_wallet_addr"] = $row["rm_wallet_addr"];
            $arrIns["time"] = $arr["data"]["time"];
            $arrIns["time_date"] = date('Y-m-d H:i:s', $arr["data"]["time"]);
            $arrIns["lastSeen"] = $arr["data"]["lastSeen"];
            $arrIns["lastSeen_date"] = date('Y-m-d H:i:s', $arr["data"]["lastSeen"]);
            $arrIns["reportedHashrate"] = $arr["data"]["reportedHashrate"];
            $arrIns["currentHashrate"] = $arr["data"]["currentHashrate"];
            $arrIns["averageHashrate"] = $arr["data"]["averageHashrate"];
            $arrIns["validShares"] = $arr["data"]["validShares"];
            $arrIns["invalidShares"] = $arr["data"]["invalidShares"];
            $arrIns["staleShares"] = $arr["data"]["staleShares"];
            $arrIns["activeWorkers"] = $arr["data"]["activeWorkers"];
            $arrIns["unconfirmed"] = $arr["data"]["unconfirmed"];
            $arrIns["unpaid"] = $arr["data"]["unpaid"];
            $arrIns["coinsPerMin"] = $arr["data"]["coinsPerMin"];
            $arrIns["usdPerMin"] = $arr["data"]["usdPerMin"];
            $arrIns["btcPerMin"] = $arr["data"]["btcPerMin"];
            
            CurrentStatsMgr::getInstance()->add($arrIns);
            
        }
            
        $url = "https://api.ethermine.org/miner/".$row["rm_wallet_addr"]."/history";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        $response  = curl_exec($ch);
        
        curl_close($ch);
        
        $arr = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
        
        if($arr["status"] == "OK") {
            
            if (count($arr["data"]) > 0) {
                
                for($j=0;$j<count($arr["data"]);$j++) {
                    
                    if (!in_array($row["userid"]."||".$arr["data"][$j]["time"], $arrHistory)) {
                    
                        $arrIns = array();
                        
                        $arrIns["userid"] = $row["userid"];
                        $arrIns["rm_name"] = $row["rm_name"];
                        $arrIns["rm_wallet_addr"] = $row["rm_wallet_addr"];
                        $arrIns["time"] = $arr["data"]["time"];
                        $arrIns["time_date"] = date('Y-m-d H:i:s', $arr["data"]["time"]);
                        $arrIns["reportedHashrate"] = $arr["data"]["reportedHashrate"];
                        $arrIns["currentHashrate"] = $arr["data"]["currentHashrate"];
                        $arrIns["averageHashrate"] = $arr["data"]["averageHashrate"];
                        $arrIns["validShares"] = $arr["data"]["validShares"];
                        $arrIns["invalidShares"] = $arr["data"]["invalidShares"];
                        $arrIns["staleShares"] = $arr["data"]["staleShares"];
                        $arrIns["activeWorkers"] = $arr["data"]["activeWorkers"];
                        
                        HistoryMgr::getInstance()->add($arrIns);
                    }
                }
            }
        }
    }
}

@ $rs_history->free();
@ $rs->free();
?>