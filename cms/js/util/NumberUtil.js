/**
	주어진 자리수에 해당하는 랜덤한 정수를 반환.
	사용법 : NU_getRandom(1)		
 */
var NU_getRandom = function(digit)
{
	var ran = Math.random();
	var dig = Math.pow(10, digit);
	return NU_toString(Math.floor(ran * dig), digit);
}

/**
	주어진 숫자를 주어진 자리수에 해당하는 문자열로 반환. (빈자리는 앞에 0으로 채움)
	사용법 : NU_getRandom(123, 4)		
 */
var NU_toString = function(number, digit)
{
	var str = new String(number);
	if ( str.length < digit ) {
		var gap = digit - str.length;
		for ( var i=0; i<gap; i++ )	{
			str = "0" + str;
		}
	}
	return str;
}

/**
	주어진 자리수에 해당하는 숫자를 count만큼 랜덤하게 보여준다.
	반드시 랜덤값을 전달하는 콜백메소드 "NU_showNumChangeCallBack(ranNum)"를 구현해 줘야 한다.
	사용법 :
		function setAutoNum() {
			var f = document.frm;
			var digit = 2;
			NU_showNumChange(digit, 20);
		}

		function NU_showNumChangeCallBack(ranNum) {
			var f = document.frm;
			f.cd1.value = ranNum.substring(0, 1);
			f.cd2.value = ranNum.substring(1, 2);
		}	
 */
var NU_showNumChangeDigit;
var NU_showNumChangeCount;
var NU_showNumChange = function(digit, count)
{
	NU_showNumChangeDigit = digit;
	NU_showNumChangeCount = count;
	NU_showNumChangeDemon();
}
var NU_showNumChangeDemon = function()
{
	NU_showNumChangeCallBack(NU_getRandom(NU_showNumChangeDigit));
	NU_showNumChangeCount--;
	if ( NU_showNumChangeCount > 0 ) {
		setTimeout("NU_showNumChangeDemon()", 30);
	}
}

/**
3자리마다 콤마를 삽입하여 반환한다.
사용법 :	NU_comma(price);
*/

function NU_comma(number)
{
	var len, startStrLen, divideNum; 
	var tmpStr, remainStr, startStr, resultStr;
	var num = String(number);
	
	len = num.length;   
	divideNum = Math.floor(len / 3); // 가장 앞 자리를 구하기 위해 3으로 나눔
	startStrLen = len % 3;	// 컴마를 찍을 문자열을 정하기 위해 3으로 나눈 나머지를 구함.
	
	resultStr = num.substr(0, startStrLen ); // 부분 문자열을 구하는데, 위에서 구한 크기만큼 0번째부터 잘라낸다.
	remainStr =  num.substr(startStrLen, len - startStrLen );  // 나머지 문자를 구한다.
	
	tmpStr = "";
	
	if ( remainStr != "" )  // 입력된 문자가  3글자 이상일때만
	{
	 // 나머지 문자열
	 // 앞에 컴마를 붙히고 3글자식 잘라서 더해준다.
	
		for ( i = 0; i < divideNum ; i++ ){
	  		tmpStr = tmpStr + "," + remainStr.substr(i*3, 3);
	  	}
	
	}
	resultStr = resultStr + tmpStr;
	
	// 3으로 딱 나눠 떨어지는 경우 맨 앞의 콤마 제거.
	if ( startStrLen == 0 ) 
		resultStr = resultStr.substr(1, resultStr.length - 1);	
	
	return resultStr;
}