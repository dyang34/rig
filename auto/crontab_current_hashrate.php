<?php
header("Cache-Control;no-cache");
header("Pragma:no-cache");
header("Content-Type:text/html; charset=utf-8");

//date_default_timezone_set('Asia/Seoul');

include "/var/www/html/classes/cms/CmsConfig.php";

@ $db = new mysqli(CmsConfig::$mysql_host, CmsConfig::$mysql_user, CmsConfig::$mysql_password, CmsConfig::$mysql_database);

$sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date, rm_fg_avg_hashrate "
        ." ,ifnull((select paidOn from rig_payouts p where p.userid = m.userid order by paidOn desc limit 1), 0) as paidOn"    
        ." from rig_member m "
        ." where rm_fg_del = 0 and rm_fg_avg_hashrate = 1 "
;

$rs = $db->query($sql);

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
            $arrIns["reportedHashrate"] = (String)$arr["data"]["reportedHashrate"];
            $arrIns["currentHashrate"] = (String)$arr["data"]["currentHashrate"];
            $arrIns["averageHashrate"] = (String)$arr["data"]["averageHashrate"];
            $arrIns["validShares"] = $arr["data"]["validShares"];
            $arrIns["invalidShares"] = $arr["data"]["invalidShares"];
            $arrIns["staleShares"] = $arr["data"]["staleShares"];
            $arrIns["activeWorkers"] = $arr["data"]["activeWorkers"];
            @$arrIns["unconfirmed"] = (String)$arr["data"]["unconfirmed"];
            $arrIns["unpaid"] = (String)$arr["data"]["unpaid"];
            $arrIns["coinsPerMin"] = (String)$arr["data"]["coinsPerMin"];
            $arrIns["usdPerMin"] = (String)$arr["data"]["usdPerMin"];
            $arrIns["btcPerMin"] = (String)$arr["data"]["btcPerMin"];
            
            $sql =" insert into rig_miner_current_stats(userid, rm_name, rm_wallet_addr, time, time_date, lastSeen, lastSeen_date, reportedHashrate, currentHashrate, averageHashrate, validShares, invalidShares, staleShares, activeWorkers, unconfirmed, unpaid, coinsPerMin, usdPerMin, btcPerMin, reg_date) values ('".$arrIns["userid"]."', '".$arrIns["rm_name"]."', '".$arrIns["rm_wallet_addr"]."', '".$arrIns["time"]."', '".$arrIns["time_date"]."', '".$arrIns["lastSeen"]."', '".$arrIns["lastSeen_date"]."', '".$arrIns["reportedHashrate"]."', '".$arrIns["currentHashrate"]."', '".$arrIns["averageHashrate"]."', '".$arrIns["validShares"]."', '".$arrIns["invalidShares"]."', '".$arrIns["staleShares"]."', '".$arrIns["activeWorkers"]."', '".$arrIns["unconfirmed"]."', '".$arrIns["unpaid"]."', '".$arrIns["coinsPerMin"]."', '".$arrIns["usdPerMin"]."', '".$arrIns["btcPerMin"]."',now())";

            $db->query($sql);
        }

        $url = "https://api.ethermine.org/miner/".$row["rm_wallet_addr"]."/payouts";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response  = curl_exec($ch);
        
        curl_close($ch);
        
        $arr_payouts = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
        
        if($arr_payouts["status"] == "OK") {
            
            if (count($arr_payouts["data"]) > 0) {
                for($i2=0;$i2<count($arr_payouts["data"]);$i2++) {
                    if($arr_payouts["data"][$i2]["paidOn"] > $row["paidOn"]) {
                        $arrVal = array();
                        $arrVal["userid"] = $row["userid"];
                        $arrVal["rm_wallet_addr"] = $row["rm_wallet_addr"];
                        $arrVal["start"] = $arr_payouts["data"][$i2]["start"];
                        $arrVal["end"] = $arr_payouts["data"][$i2]["end"];
                        $arrVal["amount"] = $arr_payouts["data"][$i2]["amount"];
                        $arrVal["txHash"] = $arr_payouts["data"][$i2]["txHash"];
                        $arrVal["paidOn"] = $arr_payouts["data"][$i2]["paidOn"];
                        $arrVal["paidOnTxt"] = date("Y년 m월 d일\nH시 i분 s초",$arr_payouts["data"][$i2]["paidOn"]);
                        
                        $sql =" insert into rig_payouts(userid, rm_wallet_addr, start, end, amount, txHash, paidOn, paidOnTxt, reg_date)"
                        ." values ('".$arrVal["userid"]
                        ."', '".$arrVal["rm_wallet_addr"]
                        ."', '".$arrVal["start"]
                        ."', '".$arrVal["end"]
                        ."', '".$arrVal["amount"]
                        ."', '".$arrVal["txHash"]
                        ."', '".$arrVal["paidOn"]
                        ."', '".$arrVal["paidOnTxt"]
                        ."', now())"
                        ;
                        
                        $db->query($sql);

                    } else {
                        break;
                    }
                }
            }
        }
    }
}

@ $rs->free();
?>