<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    JsUtil::replace("/admin");
    exit;
} else {
    JsUtil::replace("/admin/m/adm_mem_list.php");
    exit;
}
?>