<?php 
$url = "https://api.ethermine.org/miner/470d6f35fd5fe3b50497c35d2bd73d79563736d7/payouts";


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response  = curl_exec($ch);

curl_close($ch);

$arr_payouts = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);


print_r($arr_payouts);




?>