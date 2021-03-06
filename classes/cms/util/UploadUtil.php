<?php
@ini_set('gd.jpeg_ignore_warning', 1);
class UploadUtil
{
    static $Img_UpWebPath 			= "/data/item_project/";
    static $Img_MaxFileSize 		= 10240000;
    static $Img_AllowFileType		= array("image/pjpeg", "image/jpeg", "image/gif", "image/png");

    static $ImgItemL_UpWebPath 			= "/data/list/";
    static $ImgItemL_MaxFileSize 		= 204800;
    static $ImgItemL_AllowFileType		= array("image/pjpeg", "image/jpeg", "image/gif", "image/png");
    
    static $ImgItemM_UpWebPath 			= "/data/main/";
    static $ImgItemM_MaxFileSize 		= 512000;
    static $ImgItemM_AllowFileType		= array("image/pjpeg", "image/jpeg", "image/gif", "image/png");
    
    static $ImgItemP_UpWebPath 			= "/data/payment/";
    static $ImgItemP_MaxFileSize 		= 5120000;
    static $ImgItemP_AllowFileType		= array("image/pjpeg", "image/jpeg", "image/gif", "image/png");
    
    static $Cooperation_UpWebPath 		= "/data/cooperation/";
    static $Cooperation_MaxFileSize 	= 40960000;
    static $Cooperation_AllowFileType	= array();

    static $Review_UpWebPath 		= "/data/review/";
    static $Review_MaxFileSize 		= 10240000;
    static $Review_AllowFileType		= array("image/pjpeg", "image/jpeg", "image/gif", "image/png");

    static $Talk_UpWebPath 		    = "/data/file/";
    static $Talk_MaxFileSize 		= 10240000;
    static $Talk_AllowFileType		= array("image/pjpeg", "image/jpeg", "image/gif", "image/png");

    static $Movie_UpWebPath 		    = "/data/movie/";
    static $Movie_MaxFileSize 		= 10240000;
    static $Movie_AllowFileType		= array("image/pjpeg", "image/jpeg", "image/gif", "image/png");
    
    static $denyfile = array("php","php3","exe","cgi","phtml","html","htm","pl","asp","jsp","inc","dll","webarchive","bin");
    
    static function getNewFileName() {
        $en = range("A", "Z");
        return $en[rand(0, count($en)-1)].date("Ymd").time().rand(0, 9);
    }
    
    static function getNewFileName2($sort) {
        $en = range("A", "Z");
        return "F".time().$sort.$en[rand(0, count($en)-1)].rand(0, 9).rand(0, 9);
    }
    
    static function getNewFileName3() {
        $en = range("A", "Z");
        return "F".time().rand(0, 9).rand(0, 9).$en[rand(0, count($en)-1)];
    }
    
    /* ????????? - ?????? ????????? ????????? ????????? ???.
     $newFileName = UploadUtil::getNewFileName3();
     $ret = UploadUtil::upload2("file_item", $newFileName, UploadUtil::$Modoo_Chat_UpWebPath, UploadUtil::$Modoo_Chat_MaxFileSize, UploadUtil::$Modoo_Chat_AllowFileType);
     $newWebPath = $ret["newWebPath"];
     $newFileName = $ret["newFileName"];
     $fileExtName = $ret["fileExtName"];
     $fileSize = $ret["fileSize"];
     
     if ( !empty($ret["err_code"]) ) {
     $ret["err_code"]
     $ret["err_msg"]
     ...
     exit;
     }
     */
    static function upload2($tagName, $newFileName, $upWebPath, $maxFileSize, $allowFileType, $createYymmDir=false) {
        
        $ret = array();
        
        try {
            // ????????? ?????? ??????
            if ( $_FILES[$tagName]["error"] > 0 ) {
                $ret["err_code"] = "501";
                $ret["err_msg"] = "CODE[".$_FILES[$tagName]["error"]."] ????????? ?????? ?????????.";
                return $ret;
            }
            
            // ???????????? ??????
            if ( $_FILES[$tagName]["size"] > $maxFileSize ) {
                if ( $maxFileSize > 1073741824 ) $ret["fileSize"] = (int)($maxFileSize / 1073741824)."GB";
                else if ( $maxFileSize > 1048576 ) $ret["fileSize"] = (int)($maxFileSize / 1048576)."MB";
                else if ( $maxFileSize > 1024 ) $ret["fileSize"] = (int)($maxFileSize / 1024)."KB";
                else $ret["fileSize"] = $maxFileSize."Bytes";
                
                $ret["err_code"] = "502";
                $ret["err_msg"] = "???????????? ??????! ??? ?????? ????????? ".$ret["fileSize"]." ????????? ????????? ????????? ?????????.";
                return $ret;
            }
            
            // ???????????? ??????
            if ( count($allowFileType) > 0 && count(array_intersect($allowFileType, array($_FILES[$tagName]["type"]))) == 0 ) {
                $ret["err_code"] = "503";
                $ret["err_msg"] = implode(", ", $allowFileType)." ?????? ????????? ????????? ???????????????.";
                return $ret;
            }
            
            if (count(array_intersect(UploadUtil::$denyfile, explode('/',$_FILES[$tagName]["type"]))) > 0) {
                return "????????? ??????! - ".$_FILES[$tagName]["type"]." ?????? ????????? ??????????????????.";
            }
            
            // ????????? ??????
            $arrStr = explode(".", basename($_FILES[$tagName]["name"]));
            if ( count($arrStr) > 1 ) {
                $ret["fileExtName"] = $arrStr[count($arrStr)-1];
                $ret["newFileName"] = $newFileName.".".$ret["fileExtName"];
            } else {
                $ret["err_code"] = "504";
                $ret["err_msg"] = "????????? ???????????????.";
                return $ret;
            }
            
            // ???????????????
            if ( is_uploaded_file($_FILES[$tagName]["tmp_name"]) ) {
                // ????????? ?????? (?????? ????????? ?????? ?????? ?????? ?????????)
                if ($createYymmDir)
                    $ret["newWebPath"] = $upWebPath.date("Ym")."/";
                else
                    $ret["newWebPath"] = $upWebPath;
                    
                $ret["newFullPath"] = $_SERVER['DOCUMENT_ROOT'].$ret["newWebPath"];
                
                if ( !is_dir($ret["newFullPath"]) ) {
                    mkdir($ret["newFullPath"], 0777, true);
                }
                
                if ( !move_uploaded_file($_FILES[$tagName]["tmp_name"], $ret["newFullPath"].$ret["newFileName"]) ) {
                    $ret["err_code"] = "505";
                    $ret["err_msg"] = "????????? ?????? ?????? ?????????.";
                    return $ret;
                }
            } else {
                $ret["err_code"] = "506";
                $ret["err_msg"] = "????????? ?????? ?????? ?????????.";
                return $ret;
            }
        } catch(Exception $e) {
            $ret["err_code"] = "510";
            $ret["err_msg"] = $e->getMessage();
            return $ret;
        }
        
        return $ret;
    }
    
    /* ????????? - ??????????????? ????????? ?????? ??????????????? ????????? ????????? ???.
     $newFileName = UploadUtil::getNewFileName3();
     $ret = UploadUtil::upload3("file_item", $newFileName, UploadUtil::$Modoo_Chat_UpWebPath, UploadUtil::$Modoo_Chat_MaxFileSize, UploadUtil::$Modoo_Chat_AllowFileType);
     $newWebPath = $ret["newWebPath"];
     $newFileName = $ret["newFileName"];
     $fileExtName = $ret["fileExtName"];
     $fileSize = $ret["fileSize"];
     
     if ( !empty($ret["err_code"]) ) {
     $ret["err_code"]
     $ret["err_msg"]
     ...
     exit;
     }
     */
    static function upload3($tagName, $newFileName, $upWebPath, $maxFileSize, $allowFileType) {
        
        $ret = array();
        $UploadUtil = new UploadUtil;
        
        try {
            // ????????? ?????? ??????
            if ( $_FILES[$tagName]["error"] > 0 ) {
                $ret["err_code"] = "501";
                $ret["err_msg"] = "CODE[".$_FILES[$tagName]["error"]."] ????????? ?????? ?????????.";
                return $ret;
            }
            
            // ???????????? ??????
            if ( $_FILES[$tagName]["size"] > $maxFileSize ) {
                if ( $maxFileSize > 1024000000 ) $ret["fileSize"] = (int)($maxFileSize / 1024000000)."GB";
                else if ( $maxFileSize > 1048576 ) $ret["fileSize"] = (int)($maxFileSize / 1048576)."MB";
                else if ( $maxFileSize > 1024 ) $ret["fileSize"] = (int)($maxFileSize / 1024)."KB";
                else $ret["fileSize"] = $maxFileSize."Bytes";
                
                $ret["err_code"] = "502";
                $ret["err_msg"] = "???????????? ??????! ??? ?????? ????????? ".$ret["fileSize"]." ????????? ????????? ????????? ?????????.";
                return $ret;
            }
            
            // ???????????? ??????
            if ( count($allowFileType) > 0 && count(array_intersect($allowFileType, array($_FILES[$tagName]["type"]))) == 0 ) {
                $ret["err_code"] = "503";
                $ret["err_msg"] = implode(", ", $allowFileType)." ?????? ????????? ????????? ???????????????.";
                return $ret;
            }
            
            if (count(array_intersect(UploadUtil::$denyfile, explode('/',$_FILES[$tagName]["type"]))) > 0) {
                return "????????? ??????! - ".$_FILES[$tagName]["type"]." ?????? ????????? ??????????????????.";
            }
            
            // ????????? ??????
            $arrStr = explode(".", basename($_FILES[$tagName]["name"]));
            if ( count($arrStr) > 1 ) {
                $ret["fileExtName"] = $arrStr[count($arrStr)-1];
                $ret["newFileName"] = $newFileName.".".$ret["fileExtName"];
            } else {
                $ret["err_code"] = "504";
                $ret["err_msg"] = "????????? ???????????????.";
                return $ret;
            }
            
            // ???????????????
            if ( is_uploaded_file($_FILES[$tagName]["tmp_name"]) ) {
                // ????????? ?????? (?????? ????????? ?????? ?????? ?????? ?????????)
                $ret["newWebPath"] = $upWebPath.date("Ym")."/";
                $ret["newFullPath"] = $_SERVER['DOCUMENT_ROOT'].$ret["newWebPath"];
                if ( !is_dir($ret["newFullPath"]) ) {
                    mkdir($ret["newFullPath"], 0777, true);
                }
                
                if ( !move_uploaded_file($_FILES[$tagName]["tmp_name"], $ret["newFullPath"].$ret["newFileName"]) ) {
                    $ret["err_code"] = "505";
                    $ret["err_msg"] = "????????? ?????? ?????? ?????????.";
                    return $ret;
                } else {
                    $UploadUtil->imgRebuild($ret["newFullPath"].$ret["newFileName"], $ret["newFullPath"]);
                }
            } else {
                $ret["err_code"] = "506";
                $ret["err_msg"] = "????????? ?????? ?????? ?????????.";
                return $ret;
            }
        } catch(Exception $e) {
            $ret["err_code"] = "510";
            $ret["err_msg"] = $e->getMessage();
            return $ret;
        }
        
        return $ret;
    }
    
    /* ????????? -  ????????? ?????? ???????????? ?????? ?????? ?????????
     $maxWidth = "600";
     $ret = UploadUtil::uploadResize("file_item", "", UploadUtil::$Modoo_Chat_UpWebPath, UploadUtil::$Modoo_Chat_MaxFileSize, UploadUtil::$Modoo_Chat_AllowFileType, $maxWidth);
     $newWebPath = $ret["newWebPath"];
     $newFileName = $ret["newFileName"];
     $fileExtName = $ret["fileExtName"];
     $fileSize = $ret["fileSize"];
     
     
     if ( !empty($ret["err_code"]) ) {
     
     $ret["err_code"]
     $ret["err_msg"]
     ...
     exit;
     } else {
     for ($i=0; $i < count($ret); $i++) {
     $ret[$i]["newFileName"];
     $ret[$i]["newFullPath"];
     }
     }
     */
    static function uploadResize($tagName, $newFileNameType, $upWebPath, $maxFileSize, $allowFileType, $maxWidth, $createYymmDir=false) {
        
        $ret = array();
        $UploadUtil = new UploadUtil;
        
        try {
            for($i = 0; $i < count($_FILES[$tagName]["name"]); $i++){
                if ($_FILES[$tagName]["name"][$i] != "") {
                    
                    if($newFileNameType == 1){
                        $newFileName = $UploadUtil->getNewFileName();
                    } else if ($newFileNameType == 2){
                        $newFileName = $UploadUtil->getNewFileName2();
                    } else {
                        $newFileName = $UploadUtil->getNewFileName3();
                    }
                    // ????????? ?????? ??????
                    if ( $_FILES[$tagName]["error"][$i] > 0 ) {
                        $ret["err_code"] = "501";
                        $ret["err_msg"] = "CODE[".$_FILES[$tagName]["error"][$i]."] ????????? ?????? ?????????.";
                        return $ret;
                    }
                    
                    // ???????????? ??????
                    if ( $_FILES[$tagName]["size"][$i] > $maxFileSize ) {
                        if ( $maxFileSize > 1024000000 ) $ret["fileSize"] = (int)($maxFileSize / 1024000000)."GB";
                        else if ( $maxFileSize > 1048576 ) $ret["fileSize"] = (int)($maxFileSize / 1048576)."MB";
                        else if ( $maxFileSize > 1024 ) $ret["fileSize"] = (int)($maxFileSize / 1024)."KB";
                        else $ret["fileSize"] = $maxFileSize."Bytes";
                        
                        $ret["err_code"] = "502";
                        $ret["err_msg"] = "???????????? ??????! ??? ?????? ????????? ".$ret["fileSize"]." ????????? ????????? ????????? ?????????.";
                        return $ret;
                    }
                    
                    // ???????????? ??????
                    if ( count($allowFileType) > 0 && count(array_intersect($allowFileType, array($_FILES[$tagName]["type"][$i]))) == 0 ) {
                        $ret["err_code"] = "503";
                        $ret["err_msg"] = implode(", ", $allowFileType)." ?????? ????????? ????????? ???????????????.";
                        return $ret;
                    }
                    
                    if (count(array_intersect(UploadUtil::$denyfile, explode('/',$_FILES[$tagName]["type"][$i]))) > 0) {
                        $ret["err_code"] = "503";
                        $ret["err_msg"] = implode(", ", $allowFileType)." ?????? ????????? ????????? ???????????????.";
                        return $ret;
                    }
                    
                    // ????????? ??????
                    $arrStr = explode(".", basename($_FILES[$tagName]["name"][$i]));
                    
                    if ( count($arrStr) > 1 ) {
                        $ret[$i]["fileExtName"] = $arrStr[count($arrStr)-1];
                        
                        $ret[$i]["newFileName"] = $newFileName.".".$ret[$i]["fileExtName"];
                        $ret[$i]["orgFileName"] = $_FILES[$tagName]["name"][$i];
                    } else {
                        $ret["err_code"] = "504";
                        $ret["err_msg"] = "????????? ???????????????.";
                        return $ret;
                    }
                    
                    // ???????????????
                    if ( is_uploaded_file($_FILES[$tagName]["tmp_name"][$i]) ) {
                        
                        // ????????? ?????? (?????? ????????? ?????? ?????? ?????? ?????????)
                        if ($createYymmDir)
                            $ret[$i]["newWebPath"] = $upWebPath.date("Ym")."/";
                        else
                            $ret[$i]["newWebPath"] = $upWebPath;

                        $ret[$i]["newFullPath"] = $_SERVER['DOCUMENT_ROOT'].$ret[$i]["newWebPath"];
                        
                        if ( !is_dir($ret[$i]["newFullPath"]) ) {
                            mkdir($ret[$i]["newFullPath"], 0777, true);
                        }
                        
                        if ( !move_uploaded_file($_FILES[$tagName]["tmp_name"][$i], $ret[$i]["newFullPath"].$ret[$i]["newFileName"]) ) {
                            $ret["err_code"] = "505";
                            $ret["err_msg"] = "????????? ?????? ?????? ?????????.";
                        } else {
                            
                            $info_image = getimagesize($ret[$i]["newFullPath"].$ret[$i]["newFileName"]);
                            
                            if ($info_image[0] > $maxWidth || $info_image[1] > $maxWidth ) {
                                $UploadUtil->imgResize($ret[$i]["newFullPath"].$ret[$i]["newFileName"], $maxWidth, $ret[$i]["newFullPath"]);
                            }
                        }
                        
                    } else {
                        $ret["err_code"] = "506";
                        $ret["err_msg"] = "????????? ?????? ?????? ?????????.";
                        return $ret;
                    }
                }
            }
            
            return $ret;
        } catch(Exception $e) {
            $ret["err_code"] = "510";
            $ret["err_msg"] = $e->getMessage();
            return $ret;
        }
        
    }
    
    static function uploadXmlResize($tagName, $newFileNameType, $upWebPath, $maxFileSize, $allowFileType, $maxWidth, $createYymmDir=false) {
        
        $ret = array();
        $UploadUtil = new UploadUtil;
        
        try {
            if ($_FILES[$tagName]["name"][$i] != "") {
                
                if($newFileNameType == 1){
                    $newFileName = $UploadUtil->getNewFileName();
                } else if ($newFileNameType == 2){
                    $newFileName = $UploadUtil->getNewFileName2();
                } else {
                    $newFileName = $UploadUtil->getNewFileName3();
                }
                // ????????? ?????? ??????
                if ( $_FILES[$tagName]["error"] > 0 ) {
                    $ret["err_code"] = "501";
                    $ret["err_msg"] = "CODE[".$_FILES[$tagName]["error"]."] ????????? ?????? ?????????.";
                    return $ret;
                }
                
                // ???????????? ??????
                if ( $_FILES[$tagName]["size"] > $maxFileSize ) {
                    if ( $maxFileSize > 1024000000 ) $ret["fileSize"] = (int)($maxFileSize / 1024000000)."GB";
                    else if ( $maxFileSize > 1048576 ) $ret["fileSize"] = (int)($maxFileSize / 1048576)."MB";
                    else if ( $maxFileSize > 1024 ) $ret["fileSize"] = (int)($maxFileSize / 1024)."KB";
                    else $ret["fileSize"] = $maxFileSize."Bytes";
                    
                    $ret["err_code"] = "502";
                    $ret["err_msg"] = "???????????? ??????! ??? ?????? ????????? ".$ret["fileSize"]." ????????? ????????? ????????? ?????????.";
                    return $ret;
                }
                
                // ???????????? ??????
                if ( count($allowFileType) > 0 && count(array_intersect($allowFileType, array($_FILES[$tagName]["type"]))) == 0 ) {
                    $ret["err_code"] = "503";
                    $ret["err_msg"] = implode(", ", $allowFileType)." ?????? ????????? ????????? ???????????????.aa".$_FILES[$tagName]["size"].$_FILES[$tagName]["name"].$_FILES[$tagName]["type"];
                    return $ret;
                }
                
                if (count(array_intersect(UploadUtil::$denyfile, explode('/',$_FILES[$tagName]["type"]))) > 0) {
                    $ret["err_code"] = "5031";
                    $ret["err_msg"] = implode(", ", $allowFileType)." ?????? ????????? ????????? ???????????????.";
                    return $ret;
                }
                
                // ????????? ??????
                $arrStr = explode(".", basename($_FILES[$tagName]["name"]));
                
                if ( count($arrStr) > 1 ) {
                    $ret["fileExtName"] = $arrStr[count($arrStr)-1];
                    
                    $ret["newFileName"] = $newFileName.".".$ret["fileExtName"];
                    $ret["orgFileName"] = $_FILES[$tagName]["name"];
                } else {
                    $ret["err_code"] = "504";
                    $ret["err_msg"] = "????????? ???????????????.";
                    return $ret;
                }
                
                // ???????????????
                if ( is_uploaded_file($_FILES[$tagName]["tmp_name"]) ) {
                    
                    // ????????? ?????? (?????? ????????? ?????? ?????? ?????? ?????????)
                    if ($createYymmDir)
                        $ret["newWebPath"] = $upWebPath.date("Ym")."/";
                        else
                            $ret["newWebPath"] = $upWebPath;
                            
                            $ret["newFullPath"] = $_SERVER['DOCUMENT_ROOT'].$ret["newWebPath"];
                            
                            if ( !is_dir($ret["newFullPath"]) ) {
                                mkdir($ret["newFullPath"], 0777, true);
                            }
                            
                            if ( !move_uploaded_file($_FILES[$tagName]["tmp_name"], $ret["newFullPath"].$ret["newFileName"]) ) {
                                $ret["err_code"] = "505";
                                $ret["err_msg"] = "????????? ?????? ?????? ?????????.";
                            } else {
                                
                                $info_image = getimagesize($ret["newFullPath"].$ret["newFileName"]);
                                
                                if ($info_image[0] > $maxWidth || $info_image[1] > $maxWidth ) {
                                    $UploadUtil->imgResize($ret["newFullPath"].$ret["newFileName"], $maxWidth, $ret["newFullPath"]);
                                }
                            }
                            
                } else {
                    $ret["err_code"] = "506";
                    $ret["err_msg"] = "????????? ?????? ?????? ?????????.";
                    return $ret;
                }
            }
            
            return $ret;
        } catch(Exception $e) {
            $ret["err_code"] = "510";
            $ret["err_msg"] = $e->getMessage();
            return $ret;
        }
        
    }
    
    static function upload($tagName, $newFileName, $newFilePath, $maxFileSize, $arrFileType, &$returnNewName) {
        
        if ( $_FILES[$tagName]["error"] > 0 ) {
            return "CODE[".$_FILES[$tagName]["error"]."] ????????? ?????? ?????????.   ";
        }
        
        if ( $_FILES[$tagName]["size"] > $maxFileSize ) {
            $msgSize = "";
            if ( $maxFileSize > 1024000000 ) $msgSize = (int)($maxFileSize / 1024000000)."GB";
            else if ( $maxFileSize > 1048576 ) $msgSize = (int)($maxFileSize / 1048576)."MB";
            else if ( $maxFileSize > 1024 ) $msgSize = (int)($maxFileSize / 1024)."KB";
            else $msgSize = $maxFileSize."Bytes";
            return "????????? ??????! - ???????????? ??????! ??? ?????? ????????? ".$msgSize." ????????? ????????? ????????? ?????????.   ";
        }
        
        if ( count($arrFileType) > 0 && count(array_intersect($arrFileType, array($_FILES[$tagName]["type"]))) == 0 ) {
            return "????????? ??????! - ".implode(", ", $arrFileType)." ?????? ????????? ???????????????.   ";
        }
        
        if (count(array_intersect(UploadUtil::$denyfile, explode('/',$_FILES[$tagName]["type"]))) > 0) {
            return "????????? ??????! - ".$_FILES[$tagName]["type"]." ?????? ????????? ??????????????????.";
        }
        
        $arrStr = explode(".", basename($_FILES[$tagName]["name"]));
        if ( count($arrStr) > 1 ) {
            $returnNewName = $newFileName.".".$arrStr[count($arrStr)-1];
        } else {
            return "????????? ??????! - ????????? ???????????????.   ";
        }
        
        if ( is_uploaded_file($_FILES[$tagName]["tmp_name"]) ) {
            if ( !is_dir($newFilePath) ) {
                mkdir($newFilePath, 0777, true);
            }
            
            
            
            if ( !move_uploaded_file($_FILES[$tagName]["tmp_name"], $newFilePath.$returnNewName) ) {
                
                return "????????? ?????? ?????? ?????????.   ";
            }
        } else {
            return "????????? ?????? ?????? ?????????.   ";
        }
        
        return "";
    }
    
    //????????? ?????? ?????????.
    static function uploadFileArray($tagName, $newFileName, $newFilePath, $maxFileSize, &$returnNewName, $arrNum) {
        
        if ( $_FILES[$tagName]["error"][$arrNum] > 0 ) {
            return "CODE(".$_FILES[$tagName]["error"].") ????????? ?????? ?????????.   ";
        }
        
        if ( $_FILES[$tagName]["size"][$arrNum] > $maxFileSize ) {
            $msgSize = "";
            if ( $maxFileSize >= 1073741824 ) $msgSize = (int)($maxFileSize / 1073741824)."GB";
            else if ( $maxFileSize >= 1048576 ) $msgSize = (int)($maxFileSize / 1048576)."MB";
            else if ( $maxFileSize >= 1024 ) $msgSize = (int)($maxFileSize / 1024)."KB";
            else $msgSize = $maxFileSize."Bytes";
            return "???????????? ??????! ??? ?????? ????????? ".$msgSize." ????????? ????????? ????????? ?????????.   ";
        }
        
        if (count(array_intersect(UploadUtil::$denyfile, explode('/',$_FILES[$tagName]["type"]))) > 0) {
            return "????????? ??????! - ".$_FILES[$tagName]["type"]." ?????? ????????? ??????????????????.";
        }
        
        $arrStr = explode(".", basename($_FILES[$tagName]["name"][$arrNum]));
        if ( count($arrStr) > 1 ) {
            $returnNewName = $newFileName.".".$arrStr[count($arrStr)-1];
        } else {
            return "????????? ????????? ?????? ?????????.   ";
        }
        
        if ( is_uploaded_file($_FILES[$tagName]["tmp_name"][$arrNum]) ) {
            
            if ( !is_dir($newFilePath) ) {
                mkdir($newFilePath, 0777, true);
            }
            
            if ( !move_uploaded_file($_FILES[$tagName]["tmp_name"][$arrNum], $newFilePath.$returnNewName ) ) {
                return "????????? ?????? ?????? ?????????.   ";
            }
        } else {
            return "????????? ?????? ?????? ?????????.   ";
        }
        
        return "";
    }
    
    //????????? ?????? ?????????.
    static function uploadFileArray2($tagName, $newFileName, $upWebPath, $maxFileSize, $arrFileType, $arrNum) {
        $ret = array();
        
        try {
            // ????????? ?????? ??????
            if ( $_FILES[$tagName]["error"][$arrNum] > 0 ) {
                $ret["err_code"] = "501";
                $ret["err_msg"] = "CODE[".$_FILES[$tagName]["error"][$arrNum]."] ????????? ?????? ?????????.";
                return $ret;
            }
            
            if (count(array_intersect(UploadUtil::$denyfile, explode('/',$_FILES[$tagName]["type"]))) > 0) {
                $ret["err_code"] = "501";
                $ret["err_msg"] = "CODE[".$_FILES[$tagName]["error"][$arrNum]."] ????????? ?????? ?????????.";
                return $ret;
            }
            
            // ???????????? ??????
            if ( $_FILES[$tagName]["size"][$arrNum] > $maxFileSize ) {
                if ( $maxFileSize > 1024000000 ) $ret["fileSize"] = (int)($maxFileSize / 1024000000)."GB";
                else if ( $maxFileSize > 1048576 ) $ret["fileSize"] = (int)($maxFileSize / 1048576)."MB";
                else if ( $maxFileSize > 1024 ) $ret["fileSize"] = (int)($maxFileSize / 1024)."KB";
                else $ret["fileSize"] = $maxFileSize."Bytes";
                
                $ret["err_code"] = "502";
                $ret["err_msg"] = "???????????? ??????! ??? ?????? ????????? ".$ret["fileSize"]." ????????? ????????? ????????? ?????????.";
                return $ret;
            }
            
            // ???????????? ??????
            if ( count($allowFileType) > 0 && count(array_intersect($allowFileType, array($_FILES[$tagName]["type"][$arrNum]))) == 0 ) {
                $ret["err_code"] = "503";
                $ret["err_msg"] = implode(", ", $allowFileType)." ?????? ????????? ????????? ???????????????.";
                return $ret;
            }
            
            // 	????????? ??????
            $arrStr = explode(".", basename($_FILES[$tagName]["name"][$arrNum]));
            if ( count($arrStr) > 1 ) {
                $ret["fileExtName"] = $arrStr[count($arrStr)-1];
                $ret["newFileName"] = $newFileName.".".$ret["fileExtName"];
            } else {
                $ret["err_code"] = "504";
                $ret["err_msg"] = "????????? ???????????????.";
                return $ret;
            }
            
            // ???????????????
            if ( is_uploaded_file($_FILES[$tagName]["tmp_name"][$arrNum]) ) {
                // ????????? ?????? (?????? ????????? ?????? ?????? ?????? ?????????)
                $ret["newWebPath"] = $upWebPath.date("Ym")."/";
                $ret["newFullPath"] = $_SERVER['DOCUMENT_ROOT'].$ret["newWebPath"];
                if ( !is_dir($ret["newFullPath"]) ) {
                    mkdir($ret["newFullPath"], 0777, true);
                }
                
                if ( !move_uploaded_file($_FILES[$tagName]["tmp_name"][$arrNum], $ret["newFullPath"].$ret["newFileName"]) ) {
                    $ret["err_code"] = "505";
                    $ret["err_msg"] = "????????? ?????? ?????? ?????????.";
                    return $ret;
                }
            } else {
                $ret["err_code"] = "506";
                $ret["err_msg"] = "????????? ?????? ?????? ?????????.";
                return $ret;
            }
        } catch(Exception $e) {
            $ret["err_code"] = "510";
            $ret["err_msg"] = $e->getMessage();
            return $ret;
        }
        
        return $ret;
    }
    
    static function uploadTest($tagName, $newFileName, $newFilePath, $maxFileSize, $arrFileType, &$returnNewName) {
        
        if ( $_FILES[$tagName]["error"] > 0 ) {
            return "CODE[".$_FILES[$tagName]["error"]."] ????????? ?????? ?????????.   ";
        }
        
        if ( $_FILES[$tagName]["size"] > $maxFileSize ) {
            $msgSize = "";
            if ( $maxFileSize > 1024000000 ) $msgSize = (int)($maxFileSize / 1024000000)."GB";
            else if ( $maxFileSize > 1048576 ) $msgSize = (int)($maxFileSize / 1048576)."MB";
            else if ( $maxFileSize > 1024 ) $msgSize = (int)($maxFileSize / 1024)."KB";
            else $msgSize = $maxFileSize."Bytes";
            return "????????? ??????! - ???????????? ??????! ??? ?????? ????????? ".$msgSize." ????????? ????????? ????????? ?????????.   ";
        }
        
        if ( count($arrFileType) > 0 && count(array_intersect($arrFileType, array($_FILES[$tagName]["type"]))) == 0 ) {
            return "????????? ??????! - ".implode(", ", $arrFileType)." ?????? ????????? ???????????????.   ";
        }
        
        if (count(array_intersect(UploadUtil::$denyfile, explode('/',$_FILES[$tagName]["type"]))) > 0) {
            return "????????? ??????! - ".$_FILES[$tagName]["type"]." ?????? ????????? ??????????????????.";
        }
        
        $arrStr = explode(".", basename($_FILES[$tagName]["name"]));
        if ( count($arrStr) > 1 ) {
            $returnNewName = $newFileName.".".$arrStr[count($arrStr)-1];
        } else {
            return "????????? ??????! - ????????? ???????????????.   ";
        }
        
        if ( is_uploaded_file($_FILES[$tagName]["tmp_name"]) ) {
            
            if ( !is_dir($newFilePath) ) {
                mkdir($newFilePath, 0777, true);
            }
            
            if ( !move_uploaded_file($_FILES[$tagName]["tmp_name"], $newFilePath.$returnNewName) ) {
                
                return "????????? ?????? ?????? ?????????.   ";
            }
        } else {
            return "????????? ?????? ?????? ?????????.   ";
        }
        
        return "";
    }
    
    // ????????? - lsping  ????????????
    static function thumbnail($save_filename)
    {
        $ori_save_filename =  $save_filename; //??????
        $info_image=getimagesize($save_filename);
        
        switch($info_image['mime']){
            case "image/gif";
            $src_img = ImageCreateFromGIF($save_filename);
            break;
            case "image/jpeg";
            $src_img = ImageCreateFromJPEG($save_filename);
            break;
            case "image/png";
            $src_img=ImageCreateFromPNG($save_filename);
            break;
        }
        
        
        $img_info = getImageSize($save_filename);//?????????????????? ??????
        
        $img_width = $img_info[0];
        $img_height = $img_info[1];
        $dst_width=$img_width;
        
        
        //$dst_height=$max_width*($img_height/$img_width);
        $dst_height= $img_width; // ??????????????????
        
        if ($img_width < $img_height){
            
            $h = ($img_height - $dst_height)/2;
            $w = 0;
            $img_height = $img_width;
            $dst_img = imagecreatetruecolor($img_width, $img_width); //?????????????????? ??????
            ImageCopyResized($dst_img, $src_img, 0, 0, $w, $h, $img_width, $img_height, $img_width, $img_height); //?????????????????? ????????? ???????????? ???????????? ??????
            
        }else{
            $h = 0;
            $w = ($img_width - $img_height)/2;
            $img_width= $img_height; // ?????? ???????????? ?????? ??????
            $dst_img = imagecreatetruecolor($img_width, $img_width); //?????????????????? ??????
            ImageCopyResized($dst_img, $src_img, 0, 0, $w, $h, $img_width, $img_height, $img_width, $img_height); //?????????????????? ????????? ???????????? ???????????? ??????
        }
        
        
        ImageInterlace($dst_img);
        $target_quality = 90;
        switch($info_image['mime'] ) {
            case "image/jpeg";
            $save_filename = str_replace(".","_A.",$save_filename);
            ImageJPEG($dst_img,  $save_filename,$target_quality); //????????? ?????????????????? ??????
            break;
            case "image/gif";
            $save_filename = str_replace(".","_A.",$save_filename);
            imagegif($dst_img,  $save_filename,$target_quality); //????????? ?????????????????? ??????
            break;
            case "image/png";
            $save_filename = str_replace(".","_A.",$save_filename);
            imagePng($dst_img,  $save_filename,9); //????????? ?????????????????? ??????
            break;
            default:
                break;
        }
        
        ImageDestroy($dst_img);
        ImageDestroy($src_img);
        //UploadUtil::thumbnail2($save_filename);
        
    }
    
    // ????????? - lsping  ????????????
    static function thumbnail2($save_filename)
    {
        $info_image=getimagesize($save_filename);
        
        switch($info_image['mime']){
            case "image/gif";
            $src_img = ImageCreateFromGIF($save_filename);
            break;
            case "image/jpeg";
            $src_img = ImageCreateFromJPEG($save_filename);
            break;
            case "image/png";
            $src_img=ImageCreateFromPNG($save_filename);
            break;
            
            
        }
        
        $img_info = getImageSize($save_filename);//?????????????????? ??????
        
        
        $img_width = $img_info[0];
        $img_height = $img_info[1];
        $dst_width=$img_width;
        
        //$dst_height=$max_width*($img_height/$img_width);
        $dst_height= $img_width; // ??????????????????
        
        if( $img_height * 1.5 >= $img_width) {// 1.5?????????????????????
            
            $h = ($img_height - $img_width/1.5) /2;
            $w = 0;
            $img_width = $img_width ;
            $img_height = $img_width/1.5;
        }else{
            $h = 0;
            $w = ($img_width - $img_height)/2;
            $img_width= $img_height+($img_height/2); // ?????? ???????????? ?????? ??????
        }
        $dst_img = imagecreatetruecolor($img_width, $img_height); //?????????????????? ??????
        ImageCopyResized($dst_img, $src_img, 0, 0, $w, $h, $img_width, $img_height+1, $img_width, $img_height); //?????????????????? ????????? ???????????? ???????????? ??????
        
        
        ImageInterlace($dst_img);
        $target_quality = 90;
        switch($info_image['mime'] ) {
            case "image/jpeg";
            $save_filename = str_replace(".","_B.",$save_filename);
            ImageJPEG($dst_img,  $save_filename,$target_quality); //????????? ?????????????????? ??????
            break;
            case "image/gif";
            $save_filename = str_replace(".","_B.",$save_filename);
            imagegif($dst_img,  $save_filename,$target_quality); //????????? ?????????????????? ??????
            break;
            case "image/png";
            $save_filename = str_replace(".","_B.",$save_filename);
            imagePng($dst_img,  $save_filename,9); //????????? ?????????????????? ??????
            break;
            default:
                break;
        }
        
        ImageDestroy($dst_img);
        ImageDestroy($src_img);
    }
    
    // ????????? - lsping  ???????????????
    static function thumbnail3($save_filename, $maxwidth, $maxheight)
    {
        $ori_save_filename =  $save_filename; //??????
        $info_image=getimagesize($save_filename);
        
        switch($info_image['mime']){
            case "image/gif";
            $src_img = ImageCreateFromGIF($save_filename);
            break;
            case "image/jpeg";
            $src_img = ImageCreateFromJPEG($save_filename);
            break;
            case "image/png";
            $src_img=ImageCreateFromPNG($save_filename);
            break;
        }
        
        
        $img_info = getImageSize($save_filename);//?????????????????? ??????
        
        $img_width = $img_info[0];
        $img_height = $img_info[1];
        
        
        if($img_info[0]>$maxwidth || $img_info[1]>$maxheight) {
            // ??????????????? ??????limit????????? ????????? ??????????????? ??????limit?????? ?????????
            $sumw = (100*$maxheight)/$img_info[1];
            $sumh = (100*$maxwidth)/$img_info[0];
            if($sumw < $sumh) {
                // ????????? ???????????? ?????????
                $h = ($img_height - $img_width) /2;
                $w = 0;
                $img_width = ceil(($img_info[0]*$sumw)/100);
                $img_height = $maxheight;
            } else {
                // ????????? ???????????? ?????????
                $h = 0;
                $w = ($img_height - $img_width) /2;
                $img_height = ceil(($img_info[1]*$sumh)/100);
                $img_width = $maxwidth;
            }
        } else {
            // limit?????? ?????? ?????? ????????? ?????? ????????? ?????????.....
            $img_width = $img_info[0];
            $img_height = $img_info[1];
        }
        $dst_img = imagecreatetruecolor($img_width, $img_height); //?????????????????? ??????
        ImageCopyResized($dst_img, $src_img, 0, 0, 0, 0, $img_width, $img_height, $img_info[0], $img_info[1]); //?????????????????? ????????? ???????????? ???????????? ??????
        
        ImageInterlace($dst_img);
        $target_quality = 90;
        switch($info_image['mime'] ) {
            case "image/jpeg";
            $save_filename = str_replace(".","_A.",$save_filename);
            ImageJPEG($dst_img,  $save_filename,$target_quality); //????????? ?????????????????? ??????
            break;
            case "image/gif";
            $save_filename = str_replace(".","_A.",$save_filename);
            imagegif($dst_img,  $save_filename,$target_quality); //????????? ?????????????????? ??????
            break;
            case "image/png";
            $save_filename = str_replace(".","_A.",$save_filename);
            imagePng($dst_img,  $save_filename,9); //????????? ?????????????????? ??????
            break;
            default:
                break;
        }
        
        ImageDestroy($dst_img);
        ImageDestroy($src_img);
        //UploadUtil::thumbnail2($save_filename);
        
    }
    
    static function appUpload($imgArr, $newFilePath, $maxFileSize, $returnNewName) {
        $rs = array();
        for($i = 0; $i < count($imgArr); $i++){
            if ($imgArr["name"][$i] != "")  {
                if ( $imgArr["error"][$i] > 0 ) {
                    return "CODE(".$_FILES[$tagName]["error"].") ????????? ?????? ?????????.   ";
                }
                
                if ( $imgArr["size"][$i] > $maxFileSize ) {
                    $msgSize = "";
                    if ( $maxFileSize >= 1073741824 ) $msgSize = (int)($maxFileSize / 1073741824)."GB";
                    else if ( $maxFileSize >= 1048576 ) $msgSize = (int)($maxFileSize / 1048576)."MB";
                    else if ( $maxFileSize >= 1024 ) $msgSize = (int)($maxFileSize / 1024)."KB";
                    else $msgSize = $maxFileSize."Bytes";
                    return "???????????? ??????! ??? ?????? ????????? ".$msgSize." ????????? ????????? ????????? ?????????.   ";
                }
                
                $arrStr = explode(".", basename($imgArr["name"][$i]));
                if ( count($arrStr) > 1 ) {
                    $returnNewName2 = $returnNewName.$i.".".$arrStr[count($arrStr)-1];
                } else {
                    return "????????? ????????? ?????? ?????????.   ";
                }
                if ( is_uploaded_file($imgArr["tmp_name"][$i]) ) {
                    for( $x = 1; $x <= 1000; $x++ ){
                        $newFilePath_fix = $newFilePath.date("Y")."/".$x."/";
                        if ( !is_dir($newFilePath_fix) ) {
                            mkdir($newFilePath_fix, 0777, true);
                            break;
                        } else {
                            $file_result = opendir($newFilePath_fix);
                            $file_count = 0;
                            while($file = readdir($file_result)) {
                                if($file=="."||$file=="..") {continue;}
                                $file_count++;
                            }
                            if ($file_count < 990) {
                                break;
                            }
                        }
                    }
                    
                    if ( !move_uploaded_file($imgArr["tmp_name"][$i], $newFilePath.$returnNewName2) ) {
                        return "????????? ?????? ?????? ?????????.   ";
                    }
                    
                    $thumb_img1 = appThumbnail($newFilePath.$returnNewName2, 330, 440);
                    $thumb_img2 = appThumbnail($newFilePath.$returnNewName2, 690, 920);
                    array_push($rs, $thumb_img1, $thumb_img2);
                    
                    array_push($rs2, $newFilePath.$thumb_img1, $newFilePath.$thumb_img2);
                    
                    unlink($newFilePath.$returnNewName2);
                } else {
                    return "????????? ?????? ?????? ?????????.   ";
                }
            }
        }
        return $rs;
    }
    
    function appThumbnail($img, $max_width, $max_height) {
        
        $info_image = getimagesize($img);
        
        $fix_width = $max_width;
        $fix_height = $max_height;
        
        switch($info_image['mime']){
            case "image/gif";
            $src_img = ImageCreateFromGIF($img);
            break;
            case "image/jpeg";
            $src_img = ImageCreateFromJPEG($img);
            break;
            case "image/png";
            $src_img = ImageCreateFromPNG($img);
            break;
        }
        
        $img_info = getImageSize($img);//?????????????????? ??????
        
        $img_width = $img_info[0];
        $img_height = $img_info[1];
        
        $dst_img = imagecreatetruecolor($fix_width, $fix_height); //?????????????????? ??????
        
        if($img_width >= $img_height){
            $max_width = $img_height * 3 / 4;
            $max_height = $img_height;
        } else {
            $max_width = $img_width;
            $max_height = $img_width * 4 / 3;
        }
        
        
        $h = $img_width * 4 / 3;
        $w = $img_height * 3 / 4;
        
        if( $img_height >= $img_width ){
            $h_point = (($img_height - $h) / 2);
            ImageCopyResampled($dst_img, $src_img, 0, 0, 0, $h_point, $fix_width, $fix_height, $img_width, $h);
        }else{
            $w_point = (($img_width - $w) / 2);
            ImageCopyResampled($dst_img, $src_img, 0, 0, $w_point, 0, $fix_width, $fix_height, $w, $img_height);
        }
        
        
        ImageInterlace($dst_img);
        $target_quality = 100;
        $save_filename = "";
        
        switch($info_image['mime'] ) {
            case "image/jpeg";
            $save_filename = str_replace(".","_".$fix_width."x".$fix_height.".", basename($img));
            ImageJPEG($dst_img, $save_filename,$target_quality); //????????? ?????????????????? ??????
            break;
            case "image/gif";
            $save_filename = str_replace(".","_".$fix_width."x".$fix_height.".", basename($img));
            imagegif($dst_img, $save_filename,$target_quality); //????????? ?????????????????? ??????
            break;
            case "image/png";
            $save_filename = str_replace(".","_".$fix_width."x".$fix_height.".", basename($img));
            imagePng($dst_img, $save_filename,10); //????????? ?????????????????? ??????
            break;
            default:
                break;
        }
        
        ImageDestroy($src_img);
        ImageDestroy($dst_img);
        
        return str_replace(".","_".$fix_width."x".$fix_height.".", basename($img));
    }
    
    static function imgResize($img, $maxWidth, $dir) {
        
        $info_image = getimagesize($img);
        
        switch($info_image['mime']){
            case "image/gif":
                $src_img = ImageCreateFromGIF($img);
                break;
            case "image/jpeg":
                // PHP version 5.4.12 ???????????? jpg??? ??????, ????????? ?????? ???????????? ?????? ??????.
                $image = imagecreatefromstring(file_get_contents($img));
                $exif = exif_read_data($img);
                
                $src_img = ImageCreateFromJPEG($img);
                break;
            case "image/png":
                $src_img = ImageCreateFromPNG($img);
                break;
            default:
                return;
                break;
        }
        
        //$img_info = getImageSize($img);//?????????????????? ??????
        
        $img_width = $info_image[0];
        $img_height = $info_image[1];
        
        if ($maxWidth > 0 ) {
            if ($img_width > $img_height) {
                if ( $img_width >= $maxWidth) {	// ??????????????? ??????????????? ?????????
                    $dst_width = $maxWidth;
                    $dst_height = round($maxWidth * ( $img_height / $img_width ));
                } else {
                    $dst_width = $img_width;
                    $dst_height = $img_height;
                }
            } else {
                if ( $img_height >= $maxWidth) {	// ??????????????? ??????????????? ?????????
                    $dst_width = round($maxWidth * ( $img_width / $img_height ));
                    $dst_height = $maxWidth;
                } else {
                    $dst_width = $img_width;
                    $dst_height = $img_height;
                }
            }
        } else {
            $dst_width = $img_width;
            $dst_height = $img_height;
        }
        
        $dst_img = imagecreatetruecolor($dst_width, $dst_height); //?????????????????? ??????
        
        ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $dst_width, $dst_height, $img_width, $img_height);
        
        ImageInterlace($dst_img);
        $target_quality = 60;
        
        if ($info_image['mime']=="image/jpeg") {
            // PHP version 5.4.12 ???????????? jpg??? ??????, ????????? ?????? ???????????? ?????? ??????.
            if(!empty($exif['Orientation'])) {
                switch($exif['Orientation']) {
                    case 8:
                        $dst_img = imagerotate($dst_img,90,0);
                        break;
                    case 3:
                        $dst_img = imagerotate($dst_img,180,0);
                        break;
                    case 6:
                        $dst_img = imagerotate($dst_img,-90,0);
                        break;
                }
            }
        }
        
        switch($info_image['mime']) {
            case "image/jpeg":
            ImageJPEG($dst_img, $dir.basename($img), $target_quality); //????????? ?????????????????? ??????
            break;
            case "image/gif":
            imagegif($dst_img, $dir.basename($img), $target_quality); //????????? ?????????????????? ??????
            break;
            case "image/png":
            imagePng($dst_img, $dir.basename($img), 9); //????????? ?????????????????? ??????
            break;
            default:
                break;
        }
        
        ImageDestroy($src_img);
        ImageDestroy($dst_img);
        
        return basename($img);
    }
    
    static function imgRebuild($img, $dir) {
        
        $info_image = getimagesize($img);
        
        switch($info_image['mime']){
            case "image/gif";
            $src_img = ImageCreateFromGIF($img);
            break;
            case "image/jpeg";
            $src_img = ImageCreateFromJPEG($img);
            break;
            case "image/png";
            $src_img = ImageCreateFromPNG($img);
            break;
        }
        
        $img_info = getImageSize($img);//?????????????????? ??????
        
        $img_width = $img_info[0];
        $img_height = $img_info[1];
        
        $dst_img = imagecreatetruecolor($img_width, $img_height); //?????????????????? ??????
        
        ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $img_width, $img_height, $img_width, $img_height);
        
        ImageInterlace($dst_img);
        $target_quality = 60;
        
        switch($info_image['mime']) {
            case "image/jpeg";
            ImageJPEG($dst_img, $dir.basename($img), $target_quality); //????????? ?????????????????? ??????
            break;
            case "image/gif";
            imagegif($dst_img, $dir.basename($img), $target_quality); //????????? ?????????????????? ??????
            break;
            case "image/png";
            imagePng($dst_img, $dir.basename($img), 9); //????????? ?????????????????? ??????
            break;
            default:
                break;
        }
        
        ImageDestroy($src_img);
        ImageDestroy($dst_img);
        
        return basename($img);
    }
}
?>