<?php
header("Cache-Control;no-cache");
header("Pragma:no-cache");
header("Content-Type:text/html; charset=utf-8");

$account_id = "gpclubmaster";
$account_pw = "wlvl3188!!";
$account_profile_key = "90a08c17569459479b830ac721e2eac18cfa8fb4";
$at_template = "bizp_2022022110305618311579996";

$api_url = "https://dev-api.bizppurio.com";

//if($_GET["real"]=="1") {
    $api_url = "https://api.bizppurio.com";
//}

$account_data = base64_encode($account_id.":".$account_pw);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url."/v1/token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_HTTPHEADER,array(
    "Accept: application/json"
    ,"Content-Type: application/json"
    ,"Authorization: Basic $account_data"
    
));

curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch,CURLOPT_POSTFIELDS,null);

$response  = curl_exec($ch);

curl_close($ch);

$arrToken = (Array) json_decode($response);

/* SMS
$msg = "루시어돈 테스트";
$sms=array("message"=>$msg);
$content=array("sms"=>$sms);
$data=array();
$data["account"]=$account_id;
$data["refkey"]="abcdefg20220407";
$data["type"]="sms";
$data["from"]="01041458522";
$data["to"]="01043013349";
$data["content"]=$content;

$json_data=json_encode($data,JSON_UNESCAPED_SLASHES);

$oCurl=curl_init();

curl_setopt($oCurl,CURLOPT_URL, $api_url."/v3/message");
curl_setopt($oCurl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($oCurl,CURLOPT_NOSIGNAL,1);
curl_setopt($oCurl,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($oCurl,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($oCurl,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($oCurl,CURLOPT_HTTPHEADER,array(
    "Accept:application/json"
    ,"Content-Type:application/json"
    ,"Authorization:".$arrToken["type"]." ".$arrToken["accesstoken"]
));
curl_setopt($oCurl,CURLOPT_VERBOSE,true);
curl_setopt($oCurl,CURLOPT_POSTFIELDS,$json_data);
curl_setopt($oCurl,CURLOPT_TIMEOUT,3);
*/

$msg = "루시어돈 테스트 입니다.
감사합니다.";

$msg = array("URL"=>"http://211.42.152.146/admin");

$msg="[경고] 장비 장애신호가 감지되어 알림 드립니다.

▶ 장애 세부내역 조회
http://211.42.152.146/admin 가나다


* 이 메시지는 장애 메시지 알림 수신자에게만 발송됩니다.";
$sms=array(
    "message"=>$msg
    ,"senderkey"=>$account_profile_key
    ,"templatecode"=>$at_template
/*    
    ,"button"=>array(
        "name"=>"루시어돈 관리자"
        ,"type"=>"WL"
        ,"url_pc"=>"http://211.42.152.146/admin"
        ,"url_mobile"=>"http://211.42.152.146/admin"
    )
*/    
);
$content=array("at"=>$sms);
$data=array();
$data["account"]=$account_id;
$data["refkey"]="abcdefg20220407";

$data["type"]="at";
$data["from"]="01041458522";
$data["to"]="01043013349";
$data["content"]=$content;

$json_data=json_encode($data,JSON_UNESCAPED_SLASHES);

$oCurl=curl_init();

curl_setopt($oCurl,CURLOPT_URL, $api_url."/v3/message");
curl_setopt($oCurl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($oCurl,CURLOPT_NOSIGNAL,1);
curl_setopt($oCurl,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($oCurl,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($oCurl,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($oCurl,CURLOPT_HTTPHEADER,array(
    "Accept:application/json"
    ,"Content-Type:application/json"
    ,"Authorization:".$arrToken["type"]." ".$arrToken["accesstoken"]
));
curl_setopt($oCurl,CURLOPT_VERBOSE,true);
curl_setopt($oCurl,CURLOPT_POSTFIELDS,$json_data);
curl_setopt($oCurl,CURLOPT_TIMEOUT,3);



$response=curl_exec($oCurl);





$curl_errno=curl_errno($oCurl);
$curl_error=curl_error($oCurl);
curl_close($oCurl);

echo"Response:";
echo"<pre>";
print_r(json_decode($response));
print_r($curl_error);
echo"</pre>";

/*
 
stdClass Object
(
    [code] => 1000
    [description] => success
    [refkey] => abcdefg20220407
    [messagekey] => 220406145020160sms031717gpclSjWq
)

 */
?>