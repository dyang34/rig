<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if(!LoginManager::isManagerLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
}

if (!LoginManager::getManagerLoginInfo("adm_grade")) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
}
?>
<body style="background: #e1d8d854;" >
        <!-- wrap -->
        <div id="wrap">
            <!-- header -->
            <div class="ism_header">
                <!-- headerInner -->
                <div class="headerInner clearfix">
                    <span style="font-size: 20px; color: #fff; font-weight: bold; line-height: 70px;">
                        <span>LucirDon Admin System</span>
                    </span>
                    <button class="btn_nav">
                        <span>메뉴닫기</span>
                        <span></span>
                        <span></span>
                    </button>
<?php 
    include $_SERVER['DOCUMENT_ROOT']."/admin/m/include/navigator.php";
?>
                </div>
                <!-- // headerInner -->
            </div>
            <!-- // header -->
            
            <!-- content -->
            <div class="page-wrapper" style="padding: 4px;">                