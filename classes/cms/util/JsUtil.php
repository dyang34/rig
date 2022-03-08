<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";

class JsUtil
{
    /**
     * [자바스크립트] alert 실행후 주어진 url로 이동.
     */
    static function alertHref($msg, $url) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."window.location.href = '" . $url . "';"
                    ."</script>";
    }
    
    /**
     * [자바스크립트] alert 실행후 주어진 url로 이동.
     */
    static function alertReplace($msg, $url) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."window.location.replace('" . $url . "');"
                    ."</script>";
    }
    
    /**
     * [자바스크립트] alert 실행후 주어진 url로 이동.
     */
    static function alertReplaceOpenerReload($msg, $url) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."window.location.replace('" . $url . "');"
                    ."opener.location.reload();"
                        ."</script>";
    }
    
    /**
     * [자바스크립트] alert 실행후 back.
     */
    static function alertBack($msg) {
        $msg = str_replace("'", "", str_replace("\n", "\\n", $msg));
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."history.back();"
                    ."</script>";
    }
    
    /**
     * [자바스크립트] alert 실행후 현재 창닫기.
     */
    static function alertClose($msg) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."window.close();"
                    ."</script>";
    }
    
    /**
     * [자바스크립트] alert 실행후 현재 창닫기.
     */
    static function alertCloseOpenerReplace($msg, $url) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."opener.location.replace('" . $url . "');"
                ."window.alert('" . $msg . "');"
                    ."window.close();"
                        ."</script>";
    }
    
    /**
     * [자바스크립트] alert 실행후 현재 창닫기.
     */
    static function alertCloseOpenerReload($msg) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."opener.location.reload();"
                    ."window.close();"
                        ."</script>";
    }
    
    /**
     * [자바스크립트] alert 실행후 현재 창닫기.
     */
    static function alertCloseOpenerReloadFn($msg) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."opener.reload();"
                    ."window.close();"
                        ."</script>";
    }
    
    /**
     * [자바스크립트] alert 실행후 현재 창닫고 부모창으로 POST값 던지기.
     */
    static function alertCloseOpenerSubmit($msg, $url, $OpenerName) {
        $msg = str_replace("'", "", $msg);
        echo "<form name='ResultForm' method='post' action='".$url."'>"
            .RequestUtil::createInputList("hidden", "", "")
            ."</form>"
                ."<script>"
                    ."window.alert('" . $msg . "');"
                        ."document.ResultForm.target = '".$OpenerName."';"
                            ."document.ResultForm.submit();"
                                ."window.close();"
                                    ."</script>";
    }
    
    static function alertOpenerReloadReplace($msg, $reloadFn, $url) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."opener.".$reloadFn.";"
                    ."window.location.replace('" . $url . "');"
                        ."</script>";
    }
    
    // 2013.10.07  parent 를  리로드 시키는 기능   - lsping
    static function alertParentReloadReplace($msg, $reloadFn, $url) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."parent.opener.".$reloadFn.";"
                    ."window.location.replace('" . $url . "');"
                        ."</script>";
    }
    
    static function alertParentReloadReplace2($msg, $url) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."opener.location.replace('" . $url . "');"
                    ."window.close();"
                        ."</script>";
    }
    
    static function alertParentReload($msg, $formName) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."parent.location.reload();"
                    ."</script>";
    }
    
    
    
    static function alertParentReset($msg, $formName) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."parent.".$formName.".reset();"
                    ."</script>";
    }
    
    static function selfReload() {
        
        echo "<script>"
            ."window.location.reload();"
                ."</script>";
    }
    
    
    static function alertOpenerReloadClose($msg, $reloadFn) {
        $msg = str_replace("'", "", $msg);
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."opener.".$reloadFn.";"
                    ."window.close();"
                        ."</script>";
    }
    
    /**
     * [자바스크립트] opener의 경로를 주어진 url로 이동후 현재창 닫기.
     */
    static function openerReplaceClose($url) {
        echo "<script>"
            ."opener.location.replace('" . $url . "');"
                ."window.close();"
                    ."</script>";
    }
    
    /**
     * [자바스크립트] 현재창 닫기.
     */
    static function noAlertClose($out) {
        echo "<script>"
            ."window.close();"
                ."</script>";
    }
    
    /**
     * [자바스크립트] 현재 창닫기.
     */
    static function selfClose($out) {
        echo "<script>"
            ."window.opener = self;"
                ."window.close();"
                    ."</script>";
    }
    
    /**
     * [자바스크립트] alert 실행후 back.
     */
    static function back() {
        echo "<script>"
            ."window.history.back();"
                    ."</script>";
    }
    
    /**
     * [자바스크립트] 주어진 url로 이동.
     */
    static function replace($url) {
        echo "<script>"
            ."window.location.replace('" . $url . "');"
                ."</script>";
    }
    
    /**
     * [자바스크립트] 주어진 url로 이동.
     */
    static function topReplace($url) {
        echo "<script>"
            ."top.window.location.replace('" . $url . "');"
                ."</script>";
    }
    
    /**
     * [자바스크립트] 주어진 url로 이동.
     */
    static function parentReplace($url) {
        echo "<script>"
            ."parent.location.replace('" . $url . "');"
                ."</script>";
    }
    
    
    /**
     * [자바스크립트] alert 실행후 주어진 url로 이동.
     */
    static function topAlertReplace($msg, $url) {
        echo "<script>"
            ."window.alert('" . $msg . "');"
                ."top.window.location.replace('" . $url . "');"
                    ."</script>";
    }
    
    /**
     * [자바스크립트] 현제페이지의 모든 파라메터를 주어진 url로 다시 보낸다.
     */
    static function formSubmit($url) {
        echo "<form name='ResultForm' method='post' action='" . $url . "'>"
            .RequestUtil::createInputList("hidden", "", "")
            ."</form>"
                ."<script>document.ResultForm.submit();</script>";
    }
    
    /**
     * [자바스크립트] alert 실행후 현제페이지의 모든 파라메터를 주어진 url로 다시 보낸다.
     */
    static function alterFormSubmit($msg, $url) {
        echo "<form name='ResultForm' method='post' action='" . $url . "'>"
            .RequestUtil::createInputList("hidden", "", "")
            ."</form>"
                ."<script>window.alert('" . $msg . "');</script>"
                    ."<script>document.ResultForm.submit();</script>";
    }
    
    static function alterFormSubmitMinus($msg, $url, $minusArr) {
        echo "<form name='ResultForm' method='post' action='" . $url . "'>"
            .RequestUtil::createInputList("hidden", $minusArr, false)
            ."</form>"
                ."<script>window.alert('" . $msg . "');</script>"
                    ."<script>document.ResultForm.submit();</script>"
                        ;
    }
    
    static function setCookie($key, $val, $expiredays) {
        echo "<script>"
            ."var todayDate = new Date();"
                ."todayDate.setDate( todayDate.getDate() + ".$expiredays." );"
                    ."document.cookie = \"".$key."=".$val."; path=/; expires=\" + todayDate.toGMTString() + \";\""
                        ."</script>";
    }
    
    /**
     * [자바스크립트] SNS 공유하기에서 seq, sns 추가
     */
    static function snsFormSubmit($url, $seq) {
        echo "<form name='ResultForm' method='post' action='" . $url . "'>"
            .RequestUtil::createInputList("hidden", "", "")
            ."<input type='hidden' name='seq' value='".$seq."'/>"
                ."<input type='hidden' name='sns' value='Y'/>"
                    ."</form>"
                        ."<script>document.ResultForm.submit();</script>";
    }
    
}
?>