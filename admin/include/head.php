<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

error_reporting(E_ALL ^ E_NOTICE);

@session_start();
?>
<!DOCTYPE html>
<html lang="ko">
    <head>
        <title>루시어돈 관리자</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
        <meta name="robots" content="noindex">  <!-- 검색엔진로봇 수집 차단. -->

        <link rel="shortcut icon" href="/images/common/favicon.ico?" />
		<link rel="apple-touch-icon-precomposed" href="/images/common/apple-icon-57x57.png?"/>
        
        <link type="text/css" rel="stylesheet" href="/admin/css/table_style.css?t=<?php echo time(); ?>" />
        <link type="text/css" rel="stylesheet" href="/admin/css/write_style.css?t=<?php echo time(); ?>" />
        <link type="text/css" rel="stylesheet" href="/admin/css/category_style.css?t=<?php echo time(); ?>" />
        
        <script type="text/javascript" src="/js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="/js/common.js"></script>
    </head>