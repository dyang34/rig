<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

include $_SERVER['DOCUMENT_ROOT']."/admin/include/head.php";
?>

	<body class="login_wrap">
		<div class="wrapper fadeInDown">
			<div id="formContent">
				<h2 class="active">ADMIN LOGIN</h2>
				<form name="writeForm" class="custom-form" method="post" autocomplete="off">
                	<input type="hidden" name="auto_defense" />
                	<input type="hidden" name="mode" value="login" />
                
                	<input type="text" name="userid" id="userid" class="fadeIn second" placeholder="login" />
					<input type="password" name="passwd"  id="passwd" class="fadeIn third" />
					<input type="button" class="fadeIn fourth" value="LogIn" onClick="javascript:login_submit();return false;">
				</form>
				<div id="formFooter">
					<a class="underlineHover">Copyright ⓒ 2021 LucirDon. All rights reserved.</a>
				</div>
			</div>
		</div>
        
<script src="/cms/js/util/ValidCheck.js"></script>
<?php /*
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
*/?>
<script language="javascript">
//<![CDATA[

$(document).on('keypress','#userid, #passwd',function(e) {
	if (e.keyCode === 13) {
		login_submit();
		return false;
	}
});

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
	
    f.action = "./admin_login_act.php";
    f.submit();
}	

//]]>
</script>

    </body>
<?php 
    include $_SERVER['DOCUMENT_ROOT']."/admin/include/footer.php";
?>