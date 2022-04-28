<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

$rtnUrl = RequestUtil::getParam("rtnUrl", "");

if($_SERVER['SERVER_NAME']=="admin.lucirdon.co.kr") {
    header("Location:/admin/");;
}

$blm_ck_auto = CookieUtil::getCookieMd5("blm_ck_auto");
$blm_ck_userid = CookieUtil::getCookieMd5("blm_ck_userid");

if(!$blm_ck_auto) $blm_ck_auto = "";

if (LoginManager::isUserLogined() && !empty(LoginManager::getUserLoginInfo("rm_wallet_addr"))) {
    if (!empty($rtnUrl)) {
        JsUtil::replace($rtnUrl);
        exit;
    } else {
        JsUtil::replace('./dashboard.php');
        exit;
    }
}

if(!empty($rtnUrl)) {
    $rtnUrl = urldecode($rtnUrl);
}

include $_SERVER['DOCUMENT_ROOT']."/include/head_no_menu.php";
?>
    <body style="background-color: #e1d8d88c;">
        <div class="bit_wrapper">
            <div id="bit_formContent">

				<h2 class="bit_active fadeIn fir">
                    <p style="border-bottom: 2px solid #fb8113; display: inherit; margin-bottom: 6px; ">LOGIN</p>
                    <p class="bit_logTit">루시어돈 투자자 모니터링 시스템</p>
                </h2>
<?php /*                
                <div style="position:relative;padding-bottom:56.25%;height:0px;margin:0 auto;">
                	<iframe frameborder="0" title="왕이라면" width="100%" height="100%" src="https://www.youtube.com/embed/nYeqvd2tU_Q?autoplay=1&mute=1" style="position: absolute; top: 0px; left: 0px;" allowfullscreen ></iframe>
                </div>
*/?>
<?php 
/*
 * allowtransparency="true" 
type="text/html" 
// ?controls=1&rel=0&modestbranding=1&autohide=1&bgcolor=db32ca&loop=1&amp;autoplay=1                
allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"

  
                */
?>
                <form name="writeForm" class="custom-form" method="post" autocomplete="off">
                	<input type="hidden" name="auto_defense" />
                	<input type="hidden" name="mode" value="login" />
                    <input type="hidden" name="rtnUrl" value="<?=urlencode($rtnUrl)?>" />
                    
                    <div class="form-group fadeIn second" style="margin-bottom: 10px;">
                        <input type="text" class="form-control" name="userid"  id="userid" ng-model="userName" value="아이디" style="font-size: 15px; color: #999;"/>
                        <label for="userid" class="animated-label"></label>
                    </div>
                    <div class="form-group fadeIn third" style="margin-bottom: 8px;">
                        <input type="password" class="form-control" name="passwd"  id="passwd" ng-model="userName" value="" style="font-size: 15px; color: #999;"/>
                        <label for="passwd" class="animated-label"></label>
                    </div>
                    
					<div class="bit_checks fadeIn third">
                        <input type="checkbox" id="nologin" name="ck_auto" value="1"><label for="nologin">자동로그인</label>
                    </div>
                    
                    <input type="button" class="bit_login bit_input fadeIn fourth" value="Login" onClick="javascript:login_submit();return false;">
                    
                </form>
                
                <div id="formFooter">
<?php /*                
                    <a class="underlineHover" href="#">Forgot Password?</a>
*/?>
                </div>
            </div>
        </div>
        
<?php
if ($blm_ck_auto=="blm_auto_login" && !empty($blm_ck_userid)) {
?>

<form name="autoLoginForm" method="post" action="./rig_login_act.php">
	<input type="hidden" name="mode" value="autologin" />
	<input type="hidden" name="auto_defense" value="identicharmc!@" />
    <input type="hidden" name="rtnUrl" value="<?=urlencode($rtnUrl)?>" />
    <input type="hidden" name="userid" value="<?=$blm_ck_userid?>" />
</form>

<script type="text/javascript">
document.autoLoginForm.submit();
</script>

<?php 
}
?>
        
<script src="/cms/js/util/ValidCheck.js"></script>
<?php /*
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
*/?>
<script language="javascript">
//<![CDATA[

function login_submit(){
	var f = document.writeForm;

    if ( VC_inValidText(f.userid, "아이디") ) return false;
    if ( f.userid.value == "아이디" ) {
    	alert("아이디를 입력해 주십시오.");
    	f.userid.focus();
		return false;
    }
    if ( VC_inValidText(f.passwd, "패스워드") ) return false;

<?php /*
    //f.action = "<?=SystemUtil::toSsl("http://".$_SERVER[SERVER_NAME]."/mcm/member/mb_login_act.php")?>";
*/?>

	f.auto_defense.value = "identicharmc!@";
	
    f.action = "./rig_login_act.php";
    f.submit();
}	

$(function(){
    $('.form-control').each(function(){
        var txt_o = this.defaultValue;
        
        $(this).focus(function(){
            var txt_n = $(this).val();
            if( txt_n == txt_o ){
                $(this).val('')
            }
        });
        $(this).blur(function(){
            var txt_n = $(this).val();
            if( txt_n == '' ){
                $(this).val(txt_o)
            }
        });
    });
});

//]]>
</script>

    </body>
<?php 
    include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>