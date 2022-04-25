<?php
header("Cache-Control;no-cache");
header("Pragma:no-cache");
header("Content-Type:text/html; charset=utf-8");

//date_default_timezone_set('Asia/Seoul');

include "/var/www/html/classes/cms/CmsConfig.php";

@ $db = new mysqli(CmsConfig::$mysql_host, CmsConfig::$mysql_user, CmsConfig::$mysql_password, CmsConfig::$mysql_database);

$sql =" select userid, time "
    ." from rig_miner_history"
    ." where time_date >= date_add(now(), interval -3 day)"
;
        
$rs = $db->query($sql);

$arrHistory = array();
if ( $rs->num_rows > 0 ) {
    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();
        
        array_push($arrHistory, $row["userid"]."||".$row["time"]);
    }
}

$sql =" select userid, rm_name, rm_wallet_addr, rm_last_login, reg_date, rm_fg_avg_hashrate "
    ." from rig_member"
    ." where rm_fg_del = 0 and rm_fg_avg_hashrate = 1 "
;

$rs = $db->query($sql);

if ( $rs->num_rows > 0 ) {

    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();

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
                        $arrIns["time"] = $arr["data"][$j]["time"];
                        $arrIns["time_date"] = date('Y-m-d H:i:s', $arr["data"][$j]["time"]);
                        $arrIns["reportedHashrate"] = (String)$arr["data"][$j]["reportedHashrate"];
                        $arrIns["currentHashrate"] = (String)$arr["data"][$j]["currentHashrate"];
                        $arrIns["averageHashrate"] = (String)$arr["data"][$j]["averageHashrate"];
                        $arrIns["validShares"] = $arr["data"][$j]["validShares"];
                        $arrIns["invalidShares"] = $arr["data"][$j]["invalidShares"];
                        $arrIns["staleShares"] = $arr["data"][$j]["staleShares"];
                        $arrIns["activeWorkers"] = $arr["data"][$j]["activeWorkers"];
                        
                        $sql =" insert into rig_miner_history(userid, rm_name, rm_wallet_addr, time, time_date, reportedHashrate, currentHashrate, averageHashrate, validShares, invalidShares, staleShares, activeWorkers, reg_date) "
                            ." values ('".$arrIns["userid"]
                            ."', '".$arrIns["rm_name"]
                            ."', '".$arrIns["rm_wallet_addr"]
                            ."', '".$arrIns["time"]
                            ."', '".$arrIns["time_date"]
                            ."', '".$arrIns["reportedHashrate"]
                            ."', '".$arrIns["currentHashrate"]
                            ."', '".$arrIns["averageHashrate"]
                            ."', '".$arrIns["validShares"]
                            ."', '".$arrIns["invalidShares"]
                            ."', '".$arrIns["staleShares"]
                            ."', '".$arrIns["activeWorkers"]
                            ."',now())"
                        ;
                        
                        $db->query($sql);
                    }
                }
            }
        }
    }
}

@ $rs->free();
?>