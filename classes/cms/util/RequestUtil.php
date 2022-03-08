<?php 
date_default_timezone_set('Asia/Seoul');
class RequestUtil
{
	static function getParam2($pName, $default) {
		
		$request = $_GET + $_POST;
		$p = $request[$pName];

		if ( empty($p) ) {
			return $default;
		} else if ( gettype($p) == "array" ) {
			return $_POST[$pName];
		} else {
			return trim($p);
		}
	}
	
	static function getParam($pName, $default) {
	
		$request = $_GET + $_POST;
		$p = $request[$pName];
	
		if ( is_null($p) || $p == '' ) {
			return $default;
		} else if ( gettype($p) == "array" ) {
			return $_POST[$pName];
		} else {
			return trim($p);
		}
	}	

	static function getPostParam($pName, $default) {
	
		$request = $_POST;
		$p = $request[$pName];
	
		if ( is_null($p) || $p == '' ) {
			return $default;
		} else if ( gettype($p) == "array" ) {
			return $_POST[$pName];
		} else {
			return trim($p);
		}
	}	

	static function getPostParamUtf8($pName, $default) {
	
		$request = $_POST;
		$p = $request[$pName];
	
		if ( is_null($p) || $p == '' ) {
			return $default;
		} else if ( gettype($p) == "array" ) {
			return $_POST[$pName];
		} else {
			return iconv("UTF-8", "EUC-KR", trim($p));
		}
	}	

	static function getPostParamUrlUtf8($pName, $default) {
	
		$request = $_POST;
		$p = $request[$pName];
	
		if ( is_null($p) || $p == '' ) {
			return $default;
		} else if ( gettype($p) == "array" ) {
			return $_POST[$pName];
		} else {
			return iconv("UTF-8", "EUC-KR", urldecode(trim($p)));
		}
	}	

	static function getParamUtf8($pName, $default) {
	
		$request = $_GET + $_POST;
		$p = $request[$pName];
	
		if ( is_null($p) || $p == '' ) {
			return $default;
		} else if ( gettype($p) == "array" ) {
			return $_POST[$pName];
		} else {
			return iconv("UTF-8", "EUC-KR", trim($p));
		}
	}

	static function getParamUrl($pName, $default) {
	
		$request = $_GET + $_POST;
		$p = $request[$pName];
	
		if ( is_null($p) || $p == '' ) {
			return $default;
		} else if ( gettype($p) == "array" ) {
			return $_POST[$pName];
		} else {
			return urldecode(trim($p));
		}
	}

	static function getParamUrlUtf8($pName, $default) {
	
		$request = $_GET + $_POST;
		$p = $request[$pName];
	
		if ( is_null($p) || $p == '' ) {
					return $default;
		} else if ( gettype($p) == "array" ) {
			return $_POST[$pName];
		} else {
			return iconv("UTF-8", "EUC-KR", urldecode(trim($p)));
		}
	}	
	
	static function getParamImplode($pName, $delim) {

		$arr = $_POST[$pName];
		$interest = "";
		if ( !empty($arr) ) {
			$interest = implode($delim, $arr);
		}
		
		return $interest;
	}

	static function createInputList($inputType, $arrNames, $isEqualNames) {
	
		$result = "";
		$request = $_GET + $_POST;

		$keys = array_keys($request);
		for ( $i=0; $i<count($keys); $i++ ) {
			if ( !empty($arrNames) ) {
				if ( $isEqualNames ) {
					if ( count(array_intersect($arrNames, array($keys[$i]))) == 0 ) {
						continue;
					}
				} else {
					if ( count(array_intersect($arrNames, array($keys[$i]))) > 0 ) {
						continue;
					}
				}
			}

			$val = $request[$keys[$i]];
			
			if ( gettype($val) == "array" ) {
				for ( $k=0; $k<count($val); $k++ ) {
					$result .= "<input type=\"" . $inputType . "\" name=\"" . $keys[$i] . "[]\" value=\"" . $val[$k] . "\">\n";
				}
			} else {
				$result .= "<input type=\"" . $inputType . "\" name=\"" . $keys[$i] . "\" value=\"" . $val . "\">\n";
			}
		}
	
		return $result;
	}

	static function getQueryStringNoLncd() {
	
		$result = "";
		$keys = array_keys($_GET);
	
		for ( $i=0; $i<count($keys); $i++ ) {
			$key = $keys[$i];
				
			if ( $key == "lncd" ) {
				continue;
			}
	
			$val = $_GET[$key];
	
			$result .= "&" . $key . "=" . $val;
		}
	
		return $result;
	}
	
	static function getSecureParam($key, $defaultVal) {
		if ( empty($_SESSION[$key]) ) {
			return $defaultVal;
		} else {
			return $_SESSION[$key];
		}
	}
	
	static function getSecureParamUrldecode($key, $defaultVal) {
		if ( empty($_SESSION[$key]) ) {
			return $defaultVal;
		} else {
			return urldecode($_SESSION[$key]);
		}
	}
	
	static function setSecureParam($key, $val) {
		$_SESSION[$key] = $val;
	}

	static function setSecureParamUrlencode($key, $val) {
		$_SESSION[$key] = urlencode($val);
	}
	
	static function delSecureParam($key) {
		unset($_SESSION[$key]);
	}
	
	static function isMobileAgent() {

		$mobiles = "/(iphone|ipod|iphoneapp|android|lgtelecom|samsung|blackberry|symbianos|opera mini|windows ce|nokia|sonyericsson|webos|palmos)/";
		$uAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
		
		if ( preg_match($mobiles, $uAgent) ) {
			return true;
		} else {
			return false;
		}
	}
	
	static function isIPhoneAgent() {

		$mobiles = "/(iphone|ipod|ipad|iphoneapp)/";
		$uAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
		
		if ( preg_match($mobiles, $uAgent) ) {
			return true;
		} else {
			return false;
		}
	}

	static function isBlingIPhoneApp() {
	    
	    $mobiles = "/(iphoneapp)/";    // 미클릭 아이폰 앱에는 UserAgent에 iphoneapp 이 추가되어 있다.
	    $uAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
	    
	    if ( preg_match($mobiles, $uAgent) ) {
	        return true;
	    } else {
	        return false;
	    }
	}

	static function isBlingIPhoneAppPop() {
	    
	    $mobiles = "/(iphoneapp popup)/";    // 미클릭 아이폰 앱에는 UserAgent에 iphoneapp 이 추가되어 있다.
	    $uAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
	    
	    if ( preg_match($mobiles, $uAgent) ) {
	        return true;
	    } else {
	        return false;
	    }
	}
	
	static function isBlingAndroidApp() {
	    
	    $mobiles = "/(androidapp)/";    // 미클릭 아이폰 앱에는 UserAgent에 iphoneapp 이 추가되어 있다.
	    $uAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
	    
	    if ( preg_match($mobiles, $uAgent) ) {
	        return true;
	    } else {
	        return false;
	    }
	}
	
	static function isBlingAndroidAppPop() {
	    
	    $mobiles = "/(androidapp popup)/";    // 미클릭 아이폰 앱에는 UserAgent에 iphoneapp 이 추가되어 있다.
	    $uAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
	    
	    if ( preg_match($mobiles, $uAgent) ) {
	        return true;
	    } else {
	        return false;
	    }
	}
	
	static function getRequestURI() {
	
    	$rtnUrl = $_SERVER["REQUEST_URI"];
    	
    	if (preg_match("/\?/", $rtnUrl)){
    	    $split="&";
    	} else{
    	    $split="?";
    	}
    	
    	return array($rtnUrl, $split);
	}
	
	static function isIosApp() {   // 아이폰 앱 여부 체크. 아이폰 사파리의 경우 false. 아이폰 앱의 경우 true. 카카오 브라우저 등도 앱이므로 true return.
	    return preg_match("/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i", $_SERVER["HTTP_USER_AGENT"], $matches);
	}
	
	static function isAndroidApp() {
	    
	    $mobiles = "/(android)/";    // 미클릭 아이폰 앱에는 UserAgent에 iphoneapp 이 추가되어 있다.
	    $uAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);
	    
	    return preg_match($mobiles, $uAgent);
	}
}	
?>