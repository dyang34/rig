/**
	텍스트 폼의 값이 무효한 값인가?
	사용법 : if ( VC_inValidText(f.M_ID, "아이디") ) { return; }
 */
var VC_inValidText = function(obj, objName) 
{
	if ( obj.value.trim() == "" ) {
		alert(objName + "을(를) 입력해 주세요.   ");
		obj.focus();
		return true;
	} else {
		return false;
	}
}

/**
	텍스트의 길이가 주어진 최소 길이보다 작은가?
	사용법 : if ( VC_inValidLength(f.M_ID, 6, "아이디") ) { return; }
 */
var VC_inValidLength = function(obj, minLength, objName) 
{
	if ( obj.value.length < minLength ) {
		alert(objName + "은(는) " + minLength + "자 이상 이어야 합니다.   ");
		obj.focus();
		return true;
	} else {
		return false;
	}
}

/**
	텍스트의 길이가 주어진 길이와 같지 않은가?
	사용법 : if ( VC_notSameLength(f.a2, 13, "주민번호") ) { return false; }
 */
var VC_notSameLength = function(obj, vLength, objName) 
{
	if ( obj.value.length != vLength ) {
		alert(objName + "은(는) " + vLength + "자 이어야 합니다.   ");
		obj.focus();
		return true;
	} else {
		return false;
	}
}

/**
	두 텍스트 폼의 값이 같지 않은가?
	사용법 : if ( VC_notSameText(f.M_PWD, f.M_PWD2, "비밀번호") ) { return; }
 */
var VC_notSameText = function(obj1, obj2, textName) 
{
	if ( obj1.value != obj2.value ) {
		alert(textName + "가(이) 동일하지 않습니다.   ");
		return true;
	} else {
		return false;
	}
}

/**
	주어진 문자열이 숫자형식이 아닌가?
	사용법 : if ( VC_inValidNumber(f.MH_MEGA, "거래금액") ) { return; }
 */
var VC_inValidNumber = function(obj, textName)
{
	var target = obj.value;
	for ( var i=0; i<target.length; i++ ) {
		var aChar = target.substring(i, i+1);
		if ( aChar < "0" || aChar > "9" ) {
			alert(textName + "은(는) 숫자 형식이어야 합니다.   ");
			obj.focus();
			return true;
		}
	}
	return false;
}

/**
	주어진 문자열이 숫자형식인가?
	사용법 : VC_inValidNumber(f.MH_MEGA.value)
 */
var VC_isNumber = function(target)
{
	for ( var i=0; i<target.length; i++ ) {
		var aChar = target.substring(i, i+1);
		if ( aChar < "0" || aChar > "9" ) {
			return false;
		}
	}
	return true;
}

/**
주어진 문자열이 숫자를 포함하는가?
사용법 : if ( VC_isIncludeNumber(f.name, "이름") ) { return; }
*/
var VC_isIncludeNumber = function(obj, textName)
{
var target = obj.value;
for ( var i=0; i<target.length; i++ ) {
	var aChar = target.substring(i, i+1);
	if ( aChar >= "0" && aChar <= "9" ) {
		alert(textName + "에는 숫자를 입력하실 수 없습니다.   ");
		obj.focus();
		return true;
	}
}
return false;
}

/**
	주어진 문자열이 영문이 아닌가?
	사용법 : if ( VC_inValidAlpha(f._ID, 6, "아이디") ) { return; }
 */
var VC_inValidAlpha = function(obj, textName)
{
	var target = obj.value;
	for ( var i=0; i<target.length; i++ ) {
		var aChar = target.substring(i, i+1);
		if ( (aChar < "a" || aChar > "z") && (aChar < "A" || aChar > "Z") ) {
			alert(textName + "은(는) 영문 형식이어야 합니다.   ");
			obj.focus();
			return true;
		}
	}
	return false;
}

/**
	주어진 문자열이 영문인가?
	사용법 : if ( VC_isAlpha(f.MH_MEGA.value) ) { return; }
 */
var VC_isAlpha = function(target)
{
	for ( var i=0; i<target.length; i++ ) {
		var aChar = target.substring(i, i+1);
		if ( (aChar < "a" || aChar > "z") && (aChar < "A" || aChar > "Z") ) {
			return false;
		}
	}
	return true;
}

/**
	주어진 문자열이 영문과 숫자형식이 아닌가?
	사용법 : if ( VC_inValidAlpha(f.MH_MEGA, "거래금액") ) { return; }
 */
var VC_inValidAlphaNumber = function(obj, textName)
{
	var target = obj.value;
	for ( var i=0; i<target.length; i++ ) {
		var aChar = target.substring(i, i+1);
		if ( (aChar < "0" || aChar > "9") && (aChar < "a" || aChar > "z") && (aChar < "A" || aChar > "Z") ) {
			alert(textName + "은(는) 영문과 숫자 형식이어야 합니다.   ");
			obj.focus();
			return true;
		}
	}
	return false;
}

/**
주어진 문자열이 한글형식이 아닌가?
사용법 : if ( VC_inValidHangul(f.MH_MEGA, "이름") ) { return; }
*/
var VC_inValidHangul = function(obj, textName)
{
	var target = obj.value;
/*	
	var pattern = "/[^가-힝ㄱ-ㅎㅏ-ㅣ]/gi";
	
	if (pattern.match(target)) {
		alert(textName + "은(는) 한글이어야 합니다.   ");
		obj.focus();
		return true;
	}
*/
	
	for ( var i=0; i<target.length; i++ ) {
		var aChar = target.substring(i, i+1);
		var aCode = aChar.charCodeAt(0);
//		if( !((0x1100<=aCode && aCode<=0x11FF) || (0x3130<=aCode && aCode<=0x318F) || (0xAC00<=aCode && aCode<=0xD7A3) || aCode==0x20) ) {
		if( !((0xAC00<=aCode && aCode<=0xD7A3) || aCode==0x20) ) {
			alert(textName + "은(는) 한글이어야 합니다.   ");
			obj.focus();
			return true;
		}
	}
	
	return false;
}

/**
	주어진 라디오 버튼을 선택하지 않았는가?
	사용법 : if ( VC_isUnselectRadio(f.batAnswer, "항목") ) { return; }
 */
var VC_isUnselectRadio = function(items, textName)
{
	var isSelected = false;
	
	for ( var i=0; i<items.length; i++ ) {
		if ( items[i].checked ) {
			isSelected = true;
			break;
		}
	}
	
	if ( !isSelected ) {
		alert(textName + "을(를) 선택해 주세요.   ");
		return true;
	} else {
		return false;
	}
}

/**
	주어진 체크박스를 선택하지 않았는가?
	사용법1 : if ( VC_isUnCheckedSelect(f.checkbox, "항목") ) { return; }
	사용법2 : if ( VC_isUnCheckedSelect(f['selectbox[]'], "항목") ) { return; }	
 */
var VC_isUnCheckedSelect = function(items, textName)
{
	var isChecked = false;
	
	for ( var i=0; i<items.length; i++ ) {
		if ( items[i].checked ) {
			isChecked = true;
			break;
		}
	}
	
	if ( !isChecked ) {
		alert(textName + "을(를) 선택해 주세요.   ");
		return true;
	} else {
		return false;
	}
}

/**
	주어진 콤보박스를 선택하지 않았는가?
	사용법 : if ( VC_isUnselect(f.interest, "보험종류") ) return;
*/
var VC_isUnselect = function(mySelect, selectName) { 

    var isSelected = false; 
    
    for( i=0; i<mySelect.options.length; i++ ) { 
        if( mySelect[i].selected ) { 
            if ( mySelect[i].value != '' ) {
            	isSelected = true;
        	}
        } 
    } 

	if ( !isSelected ) {
		alert(selectName + "을(를) 선택해 주세요.   ");
		return true;
	} else {
		return false;
	}    
}

/**
	이메일 형식이 아닌가?
	사용법 : if ( VC_inValidNumber(f.MH_MEGA, "거래금액") ) { return; }
 */
var VC_inValidEmail = function(obj)
{
	var target = obj.value;
	if (target.indexOf("@", 0) < 2 || target.indexOf(".", 0) < 4 || target.length < 6 ) {
		alert("이메일 형식이 아닙니다.   ");
		obj.focus();		
		return true;
	}
	return false;
}

/**
이메일 도메인 형식이 아닌가?
사용법 : if ( VC_inValidEmailDomain(f.email2) ) { return; }
*/
var VC_inValidEmailDomain = function(obj)
{
var target = obj.value;
if (target.indexOf(".", 0) < 4 || target.length < 6 ) {
	alert("이메일 도메인 형식이 아닙니다.   ");
	obj.focus();		
	return true;
}
return false;
}

/**
	날짜 형식 여부 체크.
	사용법 : if ( VC_inValidDate(f.M_BIRTH, "생년월일") ) { return; }
 */
var VC_inValidDate = function(obj, textName)
{
	var target = obj.value;
	var df = /^(19[0-9][0-9]|20\d{2})-(0[0-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;

	if ( !df.test(target) ) {
		alert(textName+"은(는) 날짜 형식이어야 합니다.   ");
		obj.focus();
		return true;
	}
	return false;
}

/**
URL 형식 여부 체크.
사용법 : if ( VC_inValidUrl(f.M_URL, "URL주소") ) { return; }
*/
var VC_inValidUrl = function(obj, textName)
{
	var target = obj.value;
	var df = /(?:(?:(https?|ftp|telnet):\/\/|[\s\t\r\n\[\]\`\<\>\"\'])((?:[\w$\-_\.+!*\'\(\),]|%[0-9a-f][0-9a-f])*\:(?:[\w$\-_\.+!*\'\(\),;\?&=]|%[0-9a-f][0-9a-f])+\@)?(?:((?:(?:[a-z0-9\-가-힣]+\.)+[a-z0-9\-]{2,})|(?:[\d]{1,3}\.){3}[\d]{1,3})|localhost)(?:\:([0-9]+))?((?:\/(?:[\w$\-_\.+!*\'\(\),;:@&=ㄱ-ㅎㅏ-ㅣ가-힣]|%[0-9a-f][0-9a-f])+)*)(?:\/([^\s\/\?\.:<>|#]*(?:\.[^\s\/\?:<>|#]+)*))?(\/?[\?;](?:[a-z0-9\-]+(?:=[^\s:&<>]*)?\&)*[a-z0-9\-]+(?:=[^\s:&<>]*)?)?(#[\w\-]+)?)/gmi;
		
	if ( !df.test(target) ) {
		alert(textName+"은(는) URL 형식이어야 합니다.   ");
		obj.focus();
		return true;
	}
	return false;
}

/**
	유효한 주민번호가 아닌가?
	사용법 : if ( VC_inValidJumin(f.a2.value) ) { return false; }
 */
var VC_inValidJumin = function(jumin)
{
	var tot = 0;
	var tot_end = 0;
	
	if ( jumin.length == 13 && VC_isNumber(jumin) ) {
		j1 = jumin.substring(0,1) * 1;
		j2 = jumin.substring(1,2) * 1;
		j3 = jumin.substring(2,3) * 1;
		j4 = jumin.substring(3,4) * 1;
		j5 = jumin.substring(4,5) * 1;
		j6 = jumin.substring(5,6) * 1;
		j7 = jumin.substring(6,7) * 1;
		j8 = jumin.substring(7,8) * 1;
		j9 = jumin.substring(8,9) * 1;
		j10 = jumin.substring(9,10) * 1;
		j11 = jumin.substring(10,11) * 1;
		j12 = jumin.substring(11,12) * 1;
		j13 = jumin.substring(12,13) * 1;
		
		tot = (j1 * 2) + (j2 * 3) + (j3 * 4) + (j4 * 5) + (j5 * 6) + (j6 * 7) + (j7 * 8) + (j8 * 9) + (j9 * 2) + (j10 * 3) + (j11 * 4) + (j12 * 5);
		tot_end = (tot % 11) + j13
	}

	if ( !( tot_end == 1 || tot_end == 11) ) {
		alert("잘못된 주민번호 입니다.   ");
		return true;	
	} else {
		return false;
	}
}

/**
	thisForm에 입력한 문자열의 길이가 maxLength와 같으면 nextForm으로 커서를 이동한다.
	사용법 : onkeyup="VC_goNextForm(this, document.frm.jumin2, 6);"
 */
var VC_goNextForm = function(thisForm, nextForm, maxLength)
{
	if ( thisForm.value.length >= maxLength ) {
		nextForm.focus();
	}
}

/**
	미성년자 인가?
	사용법 : if ( VC_isMinor(f.a2.value) ) { return false; }
 */
var VC_isMinor = function(jumin)
{
	var isMinor = false;
	
	if ( jumin.length >= 6 ) {
		var toDay = new Date();
		var toYear = parseInt(toDay.getYear(),10);
		var toMonth = parseInt(toDay.getMonth() + 1,10);
		var toDate = parseInt(toDay.getDate(),10);
		
		var prefixYear = '19';
		if ( f.jumin2.value.substring(0,1) == '3' || f.jumin2.value.substring(0,1) == '4' ) {
			prefixYear = '20';
		}
				
		var bhYear = parseInt(prefixYear + jumin.substring(0,2),10);
		var bhMonth = parseInt(jumin.substring(2,4),10);
		var bhDate = parseInt(jumin.substring(4,6),10);
		
		var age = toYear - bhYear;
		
		if( age < 19 ) {
			isMinor = true;
		} else if( age == 19 ) {
			//태어난 달이 아직 안 지났다면 미성년자
			if(toMonth < bhMonth){
				isMinor = true;
			}
			//태어난 달은 같지만 날짜가 아직 안 지났다면 
			else if((toMonth == bhMonth) && (toDate < bhDate)){
				isMinor = true;
			}				
		}
	}
	
	if ( isMinor ) {
		alert("성인이 아닙니다.   ");
		return true;
	} else {
		return false;
	}
}

/**
	한글등의 문자열의 바이트 수를 반환.
	사용법 : var len = VC_getBytes(obj.value);
 */
var VC_getBytes = function(str)
{
    return(str.length +(escape(str) + '%u').match(/%u/g).length-1); 
}

/**
	주어진 문자열을 주어진 길이 까지 잘라서 반환.
	사용법 : var str = VC_cutMaxLength(obj.value, 60);
 */
var VC_cutMaxLength = function(message, max)
{
    var inte = 0; 
    var lcno = 0; 
    var msg = ""; 
    var msglen = message.length; 

    for (i=0; i<msglen; i++) { 
        var ch = message.charAt(i); 
        if (escape(ch).length > 4) { 
            inte = 2; 
        } else if (ch != '\r') { 
            inte = 1; 
        } 
        if ((lcno + inte) > max) { 
            break; 
        } 
        lcno += inte; 
         msg += ch; 
    } 
    return  msg; 
}

/**
	주어진 텍스트 박스의 최대길이 까지만 입력 받는다.
	사용법 : onkeyup="VC_checkContentLength(this, '거래금액', 1000, null)"
 */
var VC_checkContentLength = function(obj, objName, maxLength, viewer) {
	var len = VC_getBytes(obj.value);
	
	if ( viewer != null ) {
		viewer.innerText = len;
	}
	
	if ( len > maxLength ) {
		alert(objName + "은(는) 최대 " + maxLength + "bytes 까지 등록할 수 있습니다.   ");
		obj.value = VC_cutMaxLength(obj.value, maxLength);
		return true;
	}
	
	return false;
}

/**
	처음과 마지막의 공백을 제거해 준다.(가운대 공백은 하나를 남기고 제거)
	사용법 : ex ) var str = "aaaa    ";   str.trim();
 */
String.prototype.trim = function() {
  return this.replace(/(^\s*)|(\s*$)/gi, "");
}

/**
주어진 나이가 제한범위 밖인가?
사용법 : VC_inValidAgeLimit(f.age)
*/
var VC_inValidAgeLimit = function(obj, limit_lower, limit_higher)
{
/*	
	if (obj) {
		if ( limit_lower != "" && limit_lower > "0") {
			var target = obj.value;
			if (Number(target) < Number(limit_lower) || Number(target) > Number(limit_higher)) {
				alert(limit_lower+"세 ~ "+limit_higher+"세 사이만 신청 가능합니다.   ");
				obj.focus();
				return true;
			}
		}
	}
*/	
	
	return false;
}

/**
성별이 여성인가?
사용법 : VC_inValidFemale(f.sex)
*/
var VC_inValidFemale = function(obj, ms_cate, ms_code)
{
/*
	if ( (ms_cate == "22" && ms_code != 'S00109561S' && ms_code != 'S00105893V' && ms_code != 'S00102236H' && ms_code != 'S00106199L' && ms_code != 'S00102379U' && ms_code != 'S00102479E' && ms_code != 'S00106349F' && ms_code != 'S00106201K' && ms_code != 'S00106327H' && ms_code != 'S00106339X' && ms_code != 'S00111256T' && ms_code != 'S00103423C') || (ms_cate == "12" && ms_code != 'S00132985U' && ms_code != 'S00126269W' && ms_code != 'S00131910M') ) {
		if (obj) {
			for(var i=0;i<obj.length;i++){
				if(obj[i].selected == true){
					target = obj[i].value;
				}
	
				if(obj[i].checked == true){
					target = obj[i].value;
				}
			}
		
			if (target.substring(0, 1) == "남") {
					alert("여성만 신청 가능합니다.   ");
					return true;
			}
		}
	}
*/

	return false;
}

/**
 * 주어진 문자열이 동일한가?
 * 사용법 : VC_SameText(f.value, 동일한문자자릿수) 
 * @param obj : element object
 * @param maxLength : 동일한문자 자릿수 
 */
var VC_SameText = function (obj, maxLength){
	var ret ="";
	var val = obj.value;
	var k=0;
	for(var i=0; i <= val.length-maxLength; i++){
		k=0;
		for(j=1 ; j < maxLength; j++){
			if(val.charAt(i) == val.charAt(i+j)){
				k++;
			}
		}		
		if ( k >= maxLength-1 ) {
			alert("동일한 문자가 "+maxLength+"개이상 반복됩니다.\n\n다시 입력해주세요.");
			obj.focus();
			return true;
		}
	}
	
	return false;
}