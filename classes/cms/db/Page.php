<?php
class Page
{
    private $totalCount = 0;
    private $currentPage = 1;
    private $pageSize = 10;
    
    function __construct($currentPage, $pageSize) {
        $this->currentPage = $currentPage;
        $this->pageSize = $pageSize;
    }
    
    function getTotalCount() {
        return $this->totalCount;
    }
    
    function setTotalCount($totalCount) {
        $this->totalCount = $totalCount;
    }
    
    function getCurrentPage() {
        return $this->currentPage;
    }
    
    function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
    }
    
    function getPageSize() {
        return $this->pageSize;
    }
    
    function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
    }
    
    function getTotalPage() {
        if ( $this->totalCount <= 0 ) {
            return 0;
        } else {
            return (int)( ($this->totalCount - 1) / $this->pageSize ) + 1;
        }
    }
    
    function getStartIdx() {
        return ($this->currentPage - 1)*$this->pageSize;
    }
    
    function getEndIdx() {
        $endIdx = $this->currentPage * $this->pageSize;
        
        if ( $this->totalCount > 0 && $endIdx > $this->totalCount ) {
            $endIdx = $this->totalCount;
        }
        
        return $endIdx;
    }
    
    function getMaxNumOfPage()	{
        return $this->totalCount - ($this->currentPage - 1) * $this->pageSize;
    }
    
    function getStartPage() {
        $last = 0;
        $rest = 0;
        $start = 0;
        
        $last = $this->totalCount/$this->pageSize;
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $this->blockSize;
            if( $rest == 0 )
                $rest = $this->blockSize;
                $start = $this->currentPage - $rest + 1;
                
                return $start;
    }
    
    function getEndPage() {
        $last = 0;
        $rest = 0;
        $start = 0;
        
        $last = $this->totalCount/$this->pageSize;
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $this->blockSize;
            if( $rest == 0 )
                $rest = $this->blockSize;
                $start = $this->currentPage - $rest + 1;
                
                $end = $start+$this->blockSize-1;
                
                if ( $last < $end ) $end = $last;
                
                return $end;
    }
    
    function getNavi($navigationURL, $pageNumName) {
        
        $ret = "";
        $last = 0;
        $pageBlock = 10;
        $rest = 0;
        $start = 0;
        
        $last = (int)($this->totalCount/$this->pageSize);
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $pageBlock;
            if( $rest == 0 )
                $rest = $pageBlock;
                $start = $this->currentPage - $rest + 1;
                
                if( $last > 1 ) {
                    
                    if( $start - $pageBlock > 0 ) {
                        $ret .= "|&nbsp;<a href='?". $navigationURL . $pageNumName."=1'>START</a>&nbsp;";
                        $ret .= "|&nbsp;<a href='?". $navigationURL . $pageNumName."=". ($start-$pageBlock) ."'>PREVIOUS</a>&nbsp;|&nbsp;";
                    }
                    for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                        if( $i <= $last ) {
                            if( $i == $this->currentPage)
                                $ret .= "<font size=2><b>". $i ."</b></font>";
                                else
                                    $ret .= "<a href='?". $navigationURL . $pageNumName."=". $i ."'>[". $i ."]</a>";
                        }
                    }
                    
                    if( ($start+$pageBlock) <= $last ) {
                        $ret .= "&nbsp;|&nbsp;<a href='?". $navigationURL .$pageNumName."=". ($start+$pageBlock) ."'>NEXT</a>&nbsp;|&nbsp;";
                        $ret .= "<a href='?". $navigationURL .$pageNumName."=". $last ."'>END</a>&nbsp;|";
                    }
                }
                return $ret;
    }
    
    function getNaviForFunc($funcName, $startBtn, $previousBtn, $nextBtn, $endBtn) {
        
        $ret = "";
        $last = 0;
        $pageBlock = 10;
        $rest = 0;
        $start = 0;
        
        $last = (int)($this->totalCount/$this->pageSize);
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $pageBlock;
            if( $rest == 0 )
                $rest = $pageBlock;
                $start = $this->currentPage - $rest + 1;
                if( $last > 1 ) {
                    
                    if( $start - $pageBlock > 0 ) {
                        $ret .= "&nbsp;<a href=\"javascript:" . $funcName . "('1');\">" . $startBtn . "</a>&nbsp;";
                        $ret .= "&nbsp;<a href=\"javascript:" . $funcName . "('" . ($start-$pageBlock) . "');\">" . $previousBtn . "</a>&nbsp;";
                    }
                    for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                        if( $i <= $last ) {
                            if( $i == $this->currentPage)
                                $ret .= "<font size=2><b>". $i ."</b></font>";
                                else
                                    $ret .= "<a href=\"javascript:" . $funcName . "('" . $i . "');\">[". $i ."]</a>";
                        }
                    }
                    
                    if( ($start+$pageBlock) <= $last ) {
                        $ret .= "&nbsp;<a href=\"javascript:" . $funcName . "('" . ($start+$pageBlock) . "');\">" . $nextBtn . "</a>&nbsp;";
                        $ret .= "<a href=\"javascript:" . $funcName . "('" . $last . "');\">" . $endBtn . "</a>&nbsp;";
                    }
                }
                return $ret;
    }
    
    function getNaviForFunc2($funcName, $startBtn, $previousBtn, $onStyle, $offStyle, $nextBtn, $endBtn) {
        
        $startBtn = "<span class='off_num'><img src='/images/mct_list/left_all.jpg' border='0'/></span>";
        $endBtn = "<span class='off_num'><img src='/images/mct_list/right_all.jpg' border='0'/></span>";
        
        $ret = "";
        $last = 0;
        $pageBlock = 10;
        $rest = 0;
        $start = 0;
        
        $last = (int)($this->totalCount/$this->pageSize);
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $pageBlock;
            if( $rest == 0 )
                $rest = $pageBlock;
                $start = $this->currentPage - $rest + 1;
                if( $last > 1 ) {
                    
                    if( $start - $pageBlock > 0 ) {
                        $ret .= "<a href=\"javascript:" . $funcName . "('1');\">" . $startBtn . "</a>";
                        $ret .= "<a href=\"javascript:" . $funcName . "('" . ($start-$pageBlock) . "');\">" . $previousBtn . "</a>";
                    } else {
                        $ret .= $startBtn;
                        $ret .= $previousBtn;
                    }
                    
                    for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                        if( $i <= $last ) {
                            if( $i == $this->currentPage)
                                $ret .= "<font size=2><b>". str_replace("#NUM#", $i, $onStyle) ."</b></font>";
                                else
                                    $ret .= "<a href=\"javascript:" . $funcName . "('" . $i . "');\">". str_replace("#NUM#", $i, $offStyle) ."</a>";
                        }
                    }
                    
                    if( ($start+$pageBlock) <= $last ) {
                        $ret .= "<a href=\"javascript:" . $funcName . "('" . ($start+$pageBlock) . "');\">" . $nextBtn . "</a>";
                        $ret .= "<a href=\"javascript:" . $funcName . "('" . $last . "');\">" . $endBtn . "</a>";
                    } else {
                        $ret .= $nextBtn;
                        $ret .= $endBtn;
                    }
                }
                return $ret;
    }
    
    
    
    
    function getNaviForFunc3($funcName, $startBtn, $previousBtn, $nextBtn, $endBtn) {
        
        $startBtn = "<span class='off_num'><img src='/images/shop/m/list_btn_07.jpg' border='0' width='33' height='31'/></span>";
        $endBtn = "<span class='off_num'><img src='/images/shop/m/list_btn_08.jpg' border='0' width='33' height='31'/></span>";
        
        $previousBtn = "<span class='off_num'> <img src='/images/shop/m/list_btn_02.jpg' border='0' width='33' height='31'/> </span>";
        $nextBtn = "<span class='off_num'> <img src='/images/shop/m/list_btn_03.jpg' border='0' width='33' height='31'/> </span>";
        
        $ret = "";
        $last = 0;
        $pageBlock = 5;
        $rest = 0;
        $start = 0;
        
        $last = (int)($this->totalCount/$this->pageSize);
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $pageBlock;
            if( $rest == 0 )
                $rest = $pageBlock;
                $start = $this->currentPage - $rest + 1;
                
                if( $last > 1 ) {
                    
                    if( $start - $pageBlock > 0 ) {
                        $ret .= "<a href=\"javascript:" . $funcName . "('1');\" style='text-decoration:none;'>" . $startBtn . "</a>";
                        $ret .= "<a href=\"javascript:" . $funcName . "('" . ($start-$pageBlock) . "');\" style='text-decoration:none;'>" . $previousBtn . "</a>";
                    } else {
                        $ret .= "<a href=\"javascript:alert('처음 페이지 입니다.')\" style='text-decoration:none;'>" . $startBtn. "</a>";
                        $ret .= "<a href=\"javascript:alert('처음 페이지 입니다.')\" style='text-decoration:none;'>" . $previousBtn. "</a>";
                    }
                    
                    for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                        if( $i <= $last ) {
                            if( $i == $this->currentPage)
                                $ret .= "<span style=\"display: inline-block; vertical-align: middle; width: 30px; height: 29px; border: solid 1px #dcdcdc; margin: 1px;\"><div style=\"font-weight: bold; font-size: 14pt; height: 28px; display: table-cell; vertical-align:middle;\">". $i ."</div></span>";
                                else
                                    $ret .= "<a href=\"javascript:" . $funcName . "('" . $i . "');\"><span style=\"display: inline-block; vertical-align: middle; width: 30px; height: 29px; border: solid 1px #dcdcdc; margin: 1px;\"><div style=\"font-size: 14pt; height: 28px; display: table-cell; vertical-align:middle;\">". $i ."</div></span></a>";
                        }
                    }
                    
                    if( ($start+$pageBlock) <= $last ) {
                        $ret .= "<a href=\"javascript:" . $funcName . "('" . ($start+$pageBlock) . "');\" style='text-decoration:none;'>" . $nextBtn . "</a>";
                        $ret .= "<a href=\"javascript:" . $funcName . "('" . $last . "');\" style='text-decoration:none;'>" . $endBtn . "</a>";
                    } else {
                        $ret .= "<a href=\"javascript:alert('마지막 페이지 입니다.')\" style='text-decoration:none;'>" . $nextBtn. "</a>";
                        $ret .= "<a href=\"javascript:alert('마지막 페이지 입니다.')\" style='text-decoration:none;'>" . $endBtn. "</a>";
                    }
                }
                
                return $ret;
    }
    
    function getNaviForFunc4($funcName, $startBtn, $previousBtn, $nextBtn, $endBtn) {
        
        $ret = "";
        $last = 0;
        $pageBlock = 10;
        $rest = 0;
        $start = 0;
        
        $last = (int)($this->totalCount/$this->pageSize);
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $pageBlock;
            if( $rest == 0 )
                $rest = $pageBlock;
                $start = $this->currentPage - $rest + 1;
                if( $last > 1 ) {
                    
                    if( $start - $pageBlock > 0 ) {
                        $ret .= "&nbsp;<a href=\"javascript:" . $funcName . "('1');\">" . $startBtn . "</a>&nbsp;";
                        $ret .= "&nbsp;<a href=\"javascript:" . $funcName . "('" . ($start-$pageBlock) . "');\">" . $previousBtn . "</a>&nbsp;";
                    }
                    for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                        if( $i <= $last ) {
                            if( $i == $this->currentPage)
                                $ret .= "<div class='sub_PageBTN'><font size=2><b>". $i ."</b></div></font>";
                                else
                                    $ret .= "<a href=\"javascript:" . $funcName . "('" . $i . "');\"><div class='sub_PageBTN'>". $i ."</div></a>";
                                    
                        }
                    }
                    
                    if( ($start+$pageBlock) <= $last ) {
                        $ret .= "&nbsp;<a href=\"javascript:" . $funcName . "('" . ($start+$pageBlock) . "');\">" . $nextBtn . "</a>&nbsp;";
                        $ret .= "<a href=\"javascript:" . $funcName . "('" . $last . "');\">" . $endBtn . "</a>&nbsp;";
                    }
                }
                return $ret;
    }
    
    function getNaviForFunc5($funcName, $startBtn, $previousBtn, $nextBtn, $endBtn) {
        
        $ret = "";
        $last = 0;
        $pageBlock = 10;
        $rest = 0;
        $start = 0;
        
        $last = (int)($this->totalCount/$this->pageSize);
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $pageBlock;
            if( $rest == 0 )
                $rest = $pageBlock;
                $start = $this->currentPage - $rest + 1;
                if( $last > 1 ) {
                    
                    if( $start - $pageBlock > 0 ) {
                        $ret .= "&nbsp;<a href=\"javascript:" . $funcName . "('1');\">" . $startBtn . "</a>&nbsp;";
                        $ret .= "&nbsp;<a href=\"javascript:" . $funcName . "('" . ($start-$pageBlock) . "');\">" . $previousBtn . "</a>&nbsp;";
                    }
                    for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                        if( $i <= $last ) {
                            if( $i == $this->currentPage)
                                $ret .= "<div class='Csub_PageBTN'><font size=2><b>". $i ."</b></div></font>";
                                else
                                    $ret .= "<a href=\"javascript:" . $funcName . "('" . $i . "');\"><div class='Csub_PageBTN'>". $i ."</div></a>";
                                    
                        }
                    }
                    
                    if( ($start+$pageBlock) <= $last ) {
                        $ret .= "&nbsp;<a href=\"javascript:" . $funcName . "('" . ($start+$pageBlock) . "');\">" . $nextBtn . "</a>&nbsp;";
                        $ret .= "<a href=\"javascript:" . $funcName . "('" . $last . "');\">" . $endBtn . "</a>&nbsp;";
                    }
                }
                return $ret;
    }
    
    function getNaviForFuncMc($funcName, $startBtn, $previousBtn, $nextBtn, $endBtn) {
        
        $ret = "";
        $last = 0;
        $pageBlock = 10;
        $rest = 0;
        $start = 0;
        
        $ret = "<div class='paging'>";
        
        $last = (int)($this->totalCount/$this->pageSize);
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
        $rest = $this->currentPage % $pageBlock;
        if( $rest == 0 )
            $rest = $pageBlock;
        $start = $this->currentPage - $rest + 1;
        if( $last > 1 ) {
            
            if( $start - $pageBlock > 0 ) {
                $ret .= "<a href=\"javascript:" . $funcName . "('1');\" class='p_first'>" . $startBtn . "</a>";
                $ret .= " <a href=\"javascript:" . $funcName . "('" . ($start-$pageBlock) . "');\" class='num_box pre'>" . $previousBtn . "</a>";
            }
            for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                if( $i <= $last ) {
                    if( $i == $this->currentPage)
                        $ret .= " <a href='#' class='on'>". $i ."</a> ";
                        else
                            $ret .= " <a href=\"javascript:" . $funcName . "('" . $i . "');\" class='num_box'>". $i ."</a>";
                }
            }
            
            if( ($start+$pageBlock) <= $last ) {
                $ret .= " <a href=\"javascript:" . $funcName . "('" . ($start+$pageBlock) . "');\" class='p_next'>" . $nextBtn . "</a>";
                $ret .= " <a href=\"javascript:" . $funcName . "('" . $last . "');\" class='p_next'>" . $endBtn . "</a>";
            }
        }
        
        $ret .= "</div>";
        
        return $ret;
    }
    
    function getNaviForFuncMc2($funcName) {
        
        $ret = "";
        $last = 0;
        $pageBlock = 10;
        $rest = 0;
        $start = 0;
        
        $ret = "<ul>";
        
        $last = (int)($this->totalCount/$this->pageSize);
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $pageBlock;
            if( $rest == 0 )
                $rest = $pageBlock;
                $start = $this->currentPage - $rest + 1;
                if( $last > 1 ) {
                    
                    if( $start - $pageBlock > 0 ) {
                        $ret .= "<li class=\"prev-page\"><a href=\"#\" onclick=\"javascript:" . $funcName . "('1');return false;\"></a>";
                        $ret .= "<li class=\"prev\"><a href=\"#\" onclick=\"javascript:" . $funcName . "('" . ($start-$pageBlock) . "');return false;\"></a>";
                    }
                    for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                        if( $i <= $last ) {
                            if( $i == $this->currentPage) {
                                $ret .= "<li class=\"normal active\"><a href=\"#\" onclick=\"javascript:return false;\">". $i."</a></li>";
                            } else {
                                $ret .= "<li class=\"normal\"><a href=\"#\" onclick=\"javascript:".$funcName."('".$i."');return false;\">".$i."</a></li>";
                            }
                        }
                    }
                    
                    if( ($start+$pageBlock) <= $last ) {
                        $ret .= "<li class=\"next\"><a href=\"#\" onclick=\"javascript:" . $funcName . "('" . ($start+$pageBlock) . "');return false;\"></a>";
                        $ret .= "<li class=\"next-page\"><a href=\"#\" onclick=\"javascript:" . $funcName . "('" . $last . "');return false;\"></a>";
                    }
                }
                
                $ret .= "</ul>";
                
                return $ret;
    }
    
    function getNaviForFuncGP($funcName) {
        
        $ret = "";
        $last = 0;
        $pageBlock = 10;
        $rest = 0;
        $start = 0;
        
        $ret = "<ul class='pagination'>";
        
        $last = (int)($this->totalCount/$this->pageSize);
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $pageBlock;
            if( $rest == 0 )
                $rest = $pageBlock;
                $start = $this->currentPage - $rest + 1;
                if( $last > 1 ) {
                    
                    if( $start - $pageBlock > 0 ) {
                        $ret .= "<li class=\"page-item prev-page\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:" . $funcName . "('1');return false;\"><<</a>";
                        $ret .= "<li class=\"page-item prev\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:" . $funcName . "('" . ($start-$pageBlock) . "');return false;\"><</a>";
                    }
                    for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                        if( $i <= $last ) {
                            if( $i == $this->currentPage) {
                                $ret .= "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:return false;\">". $i."</a></li>";
                            } else {
                                $ret .= "<li class=\"page-item\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:".$funcName."('".$i."');return false;\">".$i."</a></li>";
                            }
                        }
                    }
                    
                    if( ($start+$pageBlock) <= $last ) {
                        $ret .= "<li class=\"page-item next\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:" . $funcName . "('" . ($start+$pageBlock) . "');return false;\">></a>";
                        $ret .= "<li class=\"page-item next-page\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:" . $funcName . "('" . $last . "');return false;\">>></a>";
                    }
                } else {
                    $ret .= "<li>&nbsp;</li>";
                }
                
                $ret .= "</ul>";
                
                return $ret;
    }
    
    function getNaviForFuncGP_m($funcName) {
        
        $ret = "";
        $last = 0;
        $pageBlock = 5;
        $rest = 0;
        $start = 0;
        
        $ret = "<ul class='pagination'>";
        
        $last = (int)($this->totalCount/$this->pageSize);
        if( ($this->totalCount % $this->pageSize) > 0 )
            $last += 1;
            $rest = $this->currentPage % $pageBlock;
            if( $rest == 0 )
                $rest = $pageBlock;
                $start = $this->currentPage - $rest + 1;
                if( $last > 1 ) {
                    
                    if( $start - $pageBlock > 0 ) {
                        $ret .= "<li class=\"page-item prev-page\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:" . $funcName . "('1');return false;\"><<</a>";
                        $ret .= "<li class=\"page-item prev\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:" . $funcName . "('" . ($start-$pageBlock) . "');return false;\"><</a>";
                    }
                    for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                        if( $i <= $last ) {
                            if( $i == $this->currentPage) {
                                $ret .= "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:return false;\">". $i."</a></li>";
                            } else {
                                $ret .= "<li class=\"page-item\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:".$funcName."('".$i."');return false;\">".$i."</a></li>";
                            }
                        }
                    }
                    
                    if( ($start+$pageBlock) <= $last ) {
                        $ret .= "<li class=\"page-item next\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:" . $funcName . "('" . ($start+$pageBlock) . "');return false;\">></a>";
                        $ret .= "<li class=\"page-item next-page\"><a class=\"page-link\" href=\"#\" onclick=\"javascript:" . $funcName . "('" . $last . "');return false;\">>></a>";
                    }
                } else {
                    $ret .= "<li>&nbsp;</li>";
                }
                
                $ret .= "</ul>";
                
                return $ret;
    }

    function getNaviForFuncMcParam($funcName, $startBtn, $previousBtn, $nextBtn, $endBtn, $totalCount) {
        
        $ret = "";
        $last = 0;
        $pageBlock = 10;
        $rest = 0;
        $start = 0;
        
        $ret = "<div class='paging'>";
        
        $last = (int)($totalCount/$this->pageSize);
        if( ($totalCount % $this->pageSize) > 0 ) {
            $last += 1;
        }
        
        $rest = $this->currentPage % $pageBlock;
        if( $rest == 0 ) {
            $rest = $pageBlock;
        }
        $start = $this->currentPage - $rest + 1;
        if( $last > 1 ) {
            
            if( $start - $pageBlock > 0 ) {
                $ret .= "<a href=\"javascript:" . $funcName . "('1');\" class='p_first'>" . $startBtn . "</a>";
                $ret .= " <a href=\"javascript:" . $funcName . "('" . ($start-$pageBlock) . "');\" class='num_box pre'>" . $previousBtn . "</a>";
            }
            
            for($i = $start; $i <= ($start+$pageBlock-1); $i++) {
                if( $i <= $last ) {
                    if( $i == $this->currentPage) {
                        $ret .= " <a href='#' class='on'>". $i ."</a> ";
                    } else {
                        $ret .= " <a href=\"javascript:" . $funcName . "('" . $i . "');\" class='num_box'>". $i ."</a>";
                    }
                }
            }
            
            if( ($start+$pageBlock) <= $last ) {
                $ret .= " <a href=\"javascript:" . $funcName . "('" . ($start+$pageBlock) . "');\" class='p_next'>" . $nextBtn . "</a>";
                $ret .= " <a href=\"javascript:" . $funcName . "('" . $last . "');\" class='p_next'>" . $endBtn . "</a>";
            }
        }
        
        $ret .= "</div>";
        
        return $ret;
    }
}
?>