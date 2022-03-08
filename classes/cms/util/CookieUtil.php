<?php
class CookieUtil
{
    // static $domain = ".sdentalclinic.co.kr";
    
    static function setCookieWithP3P($key, $val) {
        
        // header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"'); /common/nfor.php에서 설정함.
        setcookie($key, $val, time()+31536000, "/");
    }
    
    static function removeCookie($key) {
        setcookie($key, "", time(), '/');
    }

    static function removeCookieMd5($key) {
//        unset($_COOKIE[md5($key)]);
        setcookie($key, "", time(), '/');
    }
    
    static function setCookieP3pMd5($key, $val, $expire)
    {
        @setcookie($key, base64_encode($val), time() + $expire, '/');
    }
    
    // 쿠키변수값 얻음
    static function getCookieMd5($key)
    {
        return base64_decode($_COOKIE[$key]);
    }
}
?>