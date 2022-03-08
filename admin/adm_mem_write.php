<?php
require_once $_SERVER['DOCUMENT_ROOT']."/rig/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/rig/member/MemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/rig/classes/cms/db/WhereQuery.php";

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/rig/admin");
    exit;
}

$mode = RequestUtil::getParam("mode", "INS");
$userid = RequestUtil::getParam("userid", "");

if ($mode=="UPD") {
    if(empty($userid)) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x01)   ");
        exit;
    }
    
    $row = MemberMgr::getInstance()->getByKey($userid);
    
    if (empty($row)) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x02)   ");
        exit;
    }
} else {
    if(!empty($userid)) {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x04)   ");
        exit;
    }
}

include $_SERVER['DOCUMENT_ROOT']."/rig/admin/include/head.php";
?>
<body style="font-family: 'Noto Sans KR', sans-serif; line-height:1; font-size:14px;">
<?php
    include $_SERVER['DOCUMENT_ROOT']."/rig/admin/include/top_menu.php";
?>
    <div id="container" style="padding-left:194px;">
<?php
        include $_SERVER['DOCUMENT_ROOT']."/rig/admin/include/left_menu.php";
?>
            <div class="gp_rig_search">
                <div style="padding-left:20px;">
                    <h3 class="wrt_icon_search"><?=$mode=="UPD"?"수정":"등록"?>하기</h3>
                    <!--<ul class="icon_Btn">
                        <li><a href="#">조회</a></li>
                        <li><a href="#">추가</a></li>
                        <li><a href="#">엑셀</a></li>
                        <li><a href="#">삭제</a></li>
                        <li><a href="#">저장</a></li>
                        <li><a href="#">인쇄</a></li>
                    </ul>-->
                </div>
				<form name="writeForm" method="post" action="./adm_mem_write_act.php">
                	<input type="hidden" name="mode" value="<?=$mode?>" />
                	<input type="hidden" name="auto_defense" />    									

                    <table class="wrt_table">
                        <caption><?=$mode=="UPD"?"수정":"등록"?>하기</caption>
                        <colgroup>
                            <col style="width:16%;"><col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>
<?php
if ($mode=="UPD") {
?>
									<div style="height:30px;vertical-align:middle;padding:0 10px;margin:3px 0px;line-height:29px;"><?=$userid?><input type="hidden" value="<?=$userid?>" name="userid" /></div>
<?php
    
} else {
?>    									
    								<input type="text" value="" name="userid" placeholder="ID를 입력하세요." style="width: 15%;">
<?php
}
?>
                                </td>
                            </tr>
                            <tr>
                                <th>비밀번호</th>
                                <td>
                                    <input type="text" name="passwd" placeholder="<?=$mode=="UPD"?"비밀번호 변경시에만 입력.":"비밀번호를 입력하세요."?>" style="width: 15%;">
<?php
if ($mode=="UPD") {
?>
									<span style="color:red;"> ※ 비밀번호 변경시에만 입력해 주십시오.</span>
<?php
}
?>    	
                                </td>
                            </tr>
                            <tr>
                                <th>이름</th>
                                <td>
                                    <input type="text" value="<?=$row["rm_name"]?>" name="rm_name" placeholder="이름을 입력하세요." style="width: 15%;">
                                </td>
                            </tr>
                            <tr>
                                <th>지갑주소</th>
                                <td>
                                    <input type="text" value="<?=!empty($row["rm_wallet_addr"])?"0x".$row["rm_wallet_addr"]:""?>" name="rm_wallet_addr"placeholder="지갑주소를 입력하세요." style="width: 350px;" >
<?php /*                                    
                                    <span style="color:red;"> ※ 지갑주소 앞 "0x" 부분은 제거 후 입력해 주십시오.</span>
*/?>
                                </td>
                            </tr>
<?php /*
                            <tr>
                                <th>기준일자</th>
                                <td>
                                    <input type="date" id="nodate" class="date_in" style="padding:0 24px;"><label for="nodate"></label>
                                </td>
                            </tr>
                            <tr>
                                <th>제품분류</th>
                                <td>
                                    <select>
                                        <option>1차 카테고리</option>
                                        <option>분류</option>
                                        <option>분류</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>선택사항</th>
                                <td class="wrt_radio_box">
                                    <input type="radio" id="same" name="q1" checked>
                                    <label for="same" class="sameR">신규</label>
                                    <input type="radio" id="new" name="q1">
                                    <label for="new" class="newR" style="margin-right:26px;">기존</label>
                                    <input type="radio" id="not" name="q1">
                                    <label for="not" class="newR">미등록</label>
                                </td>
                            </tr>
                            <tr>
                                <th>공개여부</th>
                                <td class="wrt_checks">
                                    <input type="checkbox" id="display_on"><label for="display_on">공개</label>
                                    <input type="checkbox" id="display_off"><label for="display_off">미공개</label>
                                </td>
                            </tr>
*/?>
                        </tbody>
                    </table>
                    </form>
				<!-- 취소/등록 버튼 START -->
                <div style="cursor: pointer; overflow: hidden; display: flex; display: -webkit-flex; -webkit-align-items: center; align-items: center; flex-direction: inherit; justify-content: center; margin-top: 9px;">
                    <div class="wrt_searchBtn">
                        <a href="#" name="btnCancel">취소</a>
                    </div>
                    <div class="wrt_searchBtn">
                        <a href="#" name="btnSave">저장</a>
                    </div>
<?php
if ($mode=="UPD") {
?>
                    <div class="wrt_searchBtn" style="margin-right: 0;">
                        <a href="#" name="btnDel">삭제</a>
                    </div>
<?php
}
?>
                </div>
				<!-- 취소/등록 버튼 END -->
			</div>
			<!-- 202112123 등록하기(e) -->




	</div>

<script src="/rig/cms/js/util/ValidCheck.js"></script>	
<script type="text/javascript">
var mc_consult_submitted = false;


$(document).on("click","a[name=btnSave]",function() {
	if(mc_consult_submitted == true) { return false; }
	
	var f = document.writeForm;

	if ( VC_inValidText(f.userid, "아이디") ) return false;

<?php
if ($mode=="INS") {
?>
	if ( VC_inValidText(f.passwd, "비밀번호") ) return false;
<?php
}
?>

	var reg_engnum = /^[A-Za-z0-9+]{4,20}$/;
	
	if (f.passwd.value!="") {
    	if (!reg_engnum.test(f.passwd.value)) {
            alert("비밀번호는 숫자와 영문만 가능하며, 4~20자리여야 합니다.    ");
            f.passwd.focus();
            return;
    	}
	}

    if ( VC_inValidText(f.rm_name, "이름") ) return false;
    if ( VC_inValidText(f.rm_wallet_addr, "지갑주소") ) return false;
	
	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
});

$(document).on("click","a[name=btnDel]",function() {
	if (!confirm("정말 삭제하시겠습니까?    ")) {
		return false;
	}
	
	if(mc_consult_submitted == true) { return false; }

	var f = document.writeForm;

	f.mode.value="DEL";
	
	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
});

$(document).on("click","a[name=btnCancel]",function() {

	history.back();

    return false;
});
</script>	
	
<?php
include $_SERVER['DOCUMENT_ROOT']."/rig/admin/include/footer.php";
?>