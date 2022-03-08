<?php
class StringUtil
{
    static function cutString($target, $length, $subfix) {
        if ( empty($target) || mb_strlen($target, "utf-8") <= $length ) {
            return $target;
        }
        
        return mb_substr($target, 0, $length, "utf-8") . $subfix;
    }
    
    static function cutStringOld($target, $length, $subfix) {
        if ( empty($target) || strlen($target) <= $length ) {
            return $target;
        }
        
        return mb_strcut($target, 0, $length, "utf-8") . $subfix;
    }
    
    static function validVal($target, $default) {
        if ( empty($target) ) {
            return $default;
        } else {
            return $target;
        }
    }
    
    static function validNum($target, $default) {
        if ( empty($target) ) {
            return number_format($default);
        } else {
            return number_format($target);
        }
    }
    static function validDate($target) {
        if ( $target == '0000-00-00 00:00:00' ) {
            return "";
        } else {
            return $target;
        }
    }
    
    static function percentage($numerator, $denominator, $num) {
        if ( empty($denominator) ) {
            return 0;
        } else {
            return number_format(($numerator*100)/$denominator, 2);
        }
    }
    
    static function getRandString($size) {
        
        $ret = array();
        $en = range("a", "z");
        $nu = range("0", "9");
        
        for ( $i=0; $i<$size; $i++ ) {
            if ( rand(0, 1) ) {
                array_push($ret, $en[rand(0, count($en)-1)]);
            } else {
                array_push($ret, $nu[rand(0, count($nu)-1)]);
            }
        }
        
        return implode("", $ret);
    }
    
    static function getRandNumber($size) {
        
        $ret = array();
        $nu = range("0", "9");
        
        for ( $i=0; $i<$size; $i++ ) {
            array_push($ret, $nu[rand(0, count($nu)-1)]);
        }
        
        return implode("", $ret);
    }
    
    static function checkAuth($target, $default, $authLevel) {
        if ( $authLevel == "1" ) {
            return $default;
        } else {
            return $target;
        }
    }
    
    static function colorEmpty($target, $color, $subfix) {
        if ( empty($target) ) {
            return $target.$subfix;
        } else {
            return "<font color='".$color."'>".$target.$subfix."</font>";
        }
    }
    
    static function colorLessThan($target, $size, $color, $subfix) {
        if ( $target < $size ) {
            return "<font color='".$color."'>".$target.$subfix."</font>";
        } else {
            return $target.$subfix;
        }
    }
    
    static function colorDot($target, $color, $subfix) {
        if ( strstr($target, ".") ) {
            return str_replace(".", "<font color='".$color."'>.", $target).$subfix."</font>";
        } else {
            return $target.$subfix;
        }
    }
    
    static function getRandomIP() {
        $firstIdx = rand(1,8);
        $firstIP = "";
        if ( $firstIdx == "1" ) $firstIP = rand(58,61);
        else if ( $firstIdx == "2" ) $firstIP = rand(218,222);
        else $firstIP = rand(116,169);
        return $firstIP.".".rand(2,254).".".rand(2,254).".".rand(2,254);
    }
    
    function convertStarRight($target, $length_show){
        $str_len = mb_strlen($target, "utf-8");
        
        if ($str_len<=$length_show) {
            return $target;
        } else {
            
            $str = mb_substr($target, 0, $length_show, "utf-8");
            
            for($i=0; $i<$str_len-$length_show; $i++){
                $str .= "*";
            }
            return $str;
        }
    }
    
    static function skipRight($target, $length, $mask) {
        $skipLength = $length;
        if ( mb_strlen($target, "utf-8") < $length ) {
            $skipLength = mb_strlen($target, "utf-8");
        }
        
        $arrMask = array();
        for ( $i=0; $i<$skipLength; $i++ ) {
            array_push($arrMask, $mask);
        }
        
        return mb_substr($target, 0, mb_strlen($target, "utf-8")-$skipLength, "utf-8").implode("", $arrMask);
    }
    
    static function skipRightStar($target, $length) {
        return StringUtil::skipRight($target, $length, "*");
    }
    
    static function skipRightForNick($target) {
        if ( mb_strlen($target, "utf-8") >= 4 ) {
            return StringUtil::skipRight($target, 2, "*");
        } else {
            return StringUtil::skipRight($target, 1, "*");
        }
    }
    
    static function skipRightForName($target) {
        if ( mb_strlen($target, "utf-8") >= 6 ) {
            return StringUtil::skipRight($target, 3, "*");
        } else if ( mb_strlen($target, "utf-8") >= 4 ) {
            return StringUtil::skipRight($target, 2, "*");
        } else {
            return StringUtil::skipRight($target, 1, "*");
        }
    }
    
    static function skipAllStar($target) {
        return StringUtil::skipRightStar($target, strlen($target));
    }
    
    static function skipSpecial($target) {
        return preg_replace("/[#\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $target);
    }
    
    static function bt2nl($target) {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $target);
    }
    
    static function arrayToStringForDb($arr) {
        $ret = "";
        for ( $i=0; $i<count($arr); $i++ ) {
            if ( !empty($ret) ) $ret .= ",";
            $ret .= "'".$arr[$i]."'";
        }
        return $ret;
    }
    
    static function urlAutoLink($str)
    {
        $str = preg_replace("/&lt;/", "\t_lt_\t", $str);
        $str = preg_replace("/&gt;/", "\t_gt_\t", $str);
        $str = preg_replace("/&amp;/", "&", $str);
        $str = preg_replace("/&quot;/", "\"", $str);
        $str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);
        $str = preg_replace("/([^(http:\/\/)]|\(|^)(www\.[^[:space:]]+)/i", "\\1<A HREF=\"http://\\2\" TARGET='_blank'>\\2</A>", $str);
        //$str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i", "\\1<A HREF=\"\\2\" TARGET='$config[cf_link_target]'>\\2</A>", $str);
        // 100825 : () 추가
        $str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET='_blank'>\\2</A>", $str);
        // 이메일 정규표현식 수정 061004
        //$str = preg_replace("/(([a-z0-9_]|\-|\.)+@([^[:space:]]*)([[:alnum:]-]))/i", "<a href='mailto:\\1'>\\1</a>", $str);
        $str = preg_replace("/([0-9a-z]([-_\.]?[0-9a-z])*@[0-9a-z]([-_\.]?[0-9a-z])*\.[a-z]{2,4})/i", "<a href='mailto:\\1'>\\1</a>", $str);
        $str = preg_replace("/\t_nbsp_\t/", "&nbsp;" , $str);
        $str = preg_replace("/\t_lt_\t/", "&lt;", $str);
        $str = preg_replace("/\t_gt_\t/", "&gt;", $str);
        
        return $str;
    }
    
    static function getStrLen_utf8($str){
        preg_match_all('/[\xE0-\xFF][\x80-\xFF]{2}|./', $str, $match);
        $m = $match[0];
        $count = 0;
        for ($i=0; $i < count($m); $i++){
            $count += (strlen($m[$i]) > 1)?2:1;
            
        }
        return $count;
    }
    
    static function convertUnit($num) {
        if ($num==0) {
            return "";
        } else if ($num>=1000000) {
            return number_format(floor($num/10000));
        } else {
            return number_format($num);
        }
    }
    
    static function convertUnitText($num) {
        if ($num==0) {
            return "상담문의";
        } else if ($num>=1000000) {
            return "만원";
        } else {
            return "원";
        }
    }
    /*
     static function iconvUtf8($val) {
     
     return iconv("EUC-KR", "UTF-8", $val);
     }
     
     static function rowIconvUtf8($row) {
     
     foreach ( $row as $key => $val ) {
     $row[$key] = iconv("EUC-KR", "UTF-8", $val);
     }
     
     return $row;
     }
     
     static function rowsIconvUtf8($rows) {
     $newRows = array();
     
     for ( $i=0; $i<count($rows); $i++ ) {
     $row = $rows[$i];
     
     if (!empty($row)){
     foreach ( $row as $key => $val ) {
     $row[$key] = iconv("EUC-KR", "UTF-8", $val);
     }
     }
     
     array_push($newRows, $row);
     }
     
     return $newRows;
     }
     */
}
?>