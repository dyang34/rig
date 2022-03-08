<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

$userid = LoginManager::getUserLoginInfo("userid");

if (!LoginManager::isUserLogined()) {
//    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

include $_SERVER['DOCUMENT_ROOT']."/include/head_no_menu.php";
?>
<body>
    <div class="blm_psw">
        <div class="blm_Psw_input">
            <button class="bit_delbtn mobile">
                <span>메뉴열기</span>
                <span></span>
            </button>
            
            <h2 class="bit_active">
                <p style="border-bottom: 2px solid #fb8113; display: inherit; ">비밀번호 변경</p>
            </h2>
    		<form name="writeForm" class="custom-form" method="post" autocomplete="off">
    			<input type="hidden" name="auto_defense" />            
    			<input type="hidden" name="mode" value="change_pw" />
                <ul class="blm_pswInp">
                    <li>
                        <label for="Cur_pw" class="bit_pwTit">기존 비밀번호</label>
                        <input type="password" id="Cur_pw" name="passwd_old">
                    </li>
                    <li>
                        <label for="New_pw" class="bit_pwTit">새 비밀번호</label>
                        <input type="password" id="New_pw" name="passwd">
                    </li>
                    <li>
                        <label for="reNw_pw" class="bit_pwTit">새 비밀번호 확인</label>
                        <input type="password" id="reNw_pw" name="passwd_cfm">
                    </li>
                    <li class="text_guide" style="display: inherit; margin-top: 16px;">
                        <p>띄어쓰기 없는 영문/숫자만 가능하며, 4~20자 사용 가능합니다.</p>
                    </li>
                    <li style="margin-top: 20px;">
                        <a href="#" onClick="javascript:login_submit();return false;" class="blm_Pbtn">비밀번호 변경</a>
                    </li>
                </ul>
    		</form>
        </div>
    </div>

    <script src="/cms/js/util/ValidCheck.js"></script>
    <script language="javascript">
    //<![CDATA[
    
    function login_submit(){
    	var f = document.writeForm;
    
        if ( VC_inValidText(f.passwd_old, "기존 비밀번호") ) return false;
        if ( VC_inValidText(f.passwd, "새 비밀번호") ) return false;
    	if ( VC_inValidText(f.passwd_cfm, "새 비밀번호 확인") ) return false;    
    
    	var reg_engnum = /^[A-Za-z0-9+]{4,20}$/;
    
    	if (!reg_engnum.test(f.passwd_old.value)) {
            alert("기존 비밀번호는 숫자와 영문만 가능하며, 4~20자리여야 합니다.    ");
            f.passwd_old.focus();
            return;
    	}
    	
    	if(f.passwd.value.trim() != "") {
    
    		if (!reg_engnum.test(f.passwd.value)) {
    	        alert("[새 비밀번호]는 숫자와 영문만 가능하며, 4~20자리여야 합니다.    ");
    	        f.passwd.focus();
    	        return;
    		}
    		
    		if(f.passwd.value.trim() != f.passwd_cfm.value.trim()) {
    			alert("[새 비밀번호]와 [새 비밀번호 확인]이 일치하지 않습니다.    ");
    			return;
    		}
    	}
    
    	f.auto_defense.value = "identicharmc!@";
    	
        f.action = "./rig_change_pw_act.php";
        f.submit();
    }	
    	
	$(document).on('click','.bit_delbtn',function() {
		history.back();
		return false;
	});
	
    //]]>
    </script>

</body>
<?php 
    include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>