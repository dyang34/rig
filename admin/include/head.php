<?php
error_reporting(E_ALL ^ E_NOTICE);

@session_start();

header("Cache-Control;no-cache");
header("Pragma:no-cache");
header("Content-Type:text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html lang="ko">
    <head>
        <title>루시어돈 관리자</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta name="robots" content="noindex">  <!-- 검색엔진로봇 수집 차단. -->
        
        <link type="text/css" rel="stylesheet" href="/rig/admin/css/table_style.css?t=<?php echo time(); ?>" />
        
        <script type="text/javascript" src="/rig/js/jquery-3.4.1.min.js"></script>
    </head>