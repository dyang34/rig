<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
?>    
    <body style="background: #e1d8d854;" >
        <!-- wrap -->
        <div id="wrap">
            <!-- header -->
            <div class="bit_header">
                <div class="headerInner clearfix">
                    <span>
                        <span style="float: left; margin-top: 15px;">
                            <img src="../images/common/personW.png" alt="" width="40" height="40" />
                        </span>
                        <span style="font-size: 14px; margin: 24px 0 0 7px; float: left; color: #fff;">
                            <span style=" font-size: 16px; font-weight: 600; margin-right: 1px; vertical-align: bottom;"><?=LoginManager::getUserLoginInfo("rm_name")?></span>
                            <span style="vertical-align: top;">님의</span> 
                            <span style="font-size: 18px; vertical-align: bottom; font-weight: bold;">모니터링 시스템</span>
                        </span> <!-- text-decoration: underline; -->
                    </span>
                    <button class="btn_nav mobile">
                        <span>메뉴열기</span>
                        <span></span>
                        <span></span>
                    </button>
                    <nav id="navWrap" class="bit_topmenu">
                        <div class="navInner">
                            <ul class="gnb">
                                <li>
                                    <a href="./dashboard.php">기기 작동 모니터링</a>
                                </li>
                                <li>
                                    <a href="./payouts.php">이더리움 지불 현황</a>
                                </li>
                                <li>
                                    <a href="./rig_change_pw.php">비밀번호 변경</a>
                                </li>
                                <li>
                                    <a href="./rig_logout.php">LOGOUT</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                
                <!-- tab menu start -->
                <div class="menu_tab clearfix">
                    <ul>
                        <li class="menu_entire plus <?=$MenuPage==1?"active":""?>" bit_tab="A">
                            <a href="javascript:" onclick="location.href='dashboard.php'">
                                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor" style="width: 16px;">
                                    <path d="M10 13a2 2 0 110-4 2 2 0 010 4zm0-2.5a.5.5 0 100 1 .5.5 0 000-1z" />
                                    <path d="M20.3 11.8h-8.8a.8.8 0 010-1.6h8.8a.8.8 0 010 1.6zM8.5 11.8H3.7a.8.8 0 010-1.6h4.8a.8.8 0 010 1.6zM15 19a2 2 0 110-4 2 2 0 010 4zm0-2.5a.5.5 0 100 1 .5.5 0 000-1z" />
                                    <path d="M20.3 17.8h-3.8a.8.8 0 010-1.6h3.8a.8.8 0 010 1.6zM13.5 17.8H3.7a.8.8 0 010-1.6h9.8a.8.8 0 010 1.6z" />
                                    <path d="M21.3 23H2.6A2.8 2.8 0 010 20.2V3.9C0 2.1 1.2 1 2.8 1h18.4C22.9 1 24 2.2 24 3.8v16.4c0 1.6-1.2 2.8-2.8 2.8zM2.6 2.5c-.6 0-1.2.6-1.2 1.3v16.4c0 .7.6 1.3 1.3 1.3h18.4c.7 0 1.3-.6 1.3-1.3V3.9c0-.7-.6-1.3-1.3-1.3z" />
                                    <path d="M23.3 6H.6a.8.8 0 010-1.5h22.6a.8.8 0 010 1.5z" />
                                </svg>
                                기기 작동 모니터링
                            </a>
                        </li>
                        <li class="menu_entire <?=$MenuPage==2?"active":""?>" bit_tab="B">
                            <a href="javascript:" onclick="location.href='payouts.php'">
                                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="currentColor" style="width: 16px;">
                                    <path d="M76 240c12.1 0 23.1-4.8 31.2-12.6l44.2 22A44.9 44.9 0 00196 300a45 45 0 0040.6-64.4l60-60a45 45 0 0062.3-54l52.2-39.2a45 45 0 10-18-24l-52.2 39.2a45 45 0 00-65.5 56.8l-60 60a44.7 44.7 0 00-50.6 8.2l-44.2-22A44.9 44.9 0 0076 150a45 45 0 000 90zM436 30a15 15 0 110 30 15 15 0 010-30zm-120 90a15 15 0 110 30 15 15 0 010-30zM196 240a15 15 0 110 30 15 15 0 010-30zM76 180a15 15 0 110 30 15 15 0 010-30zm0 0" />
                                    <path d="M497 482h-16V165a15 15 0 00-15-15h-60a15 15 0 00-15 15v317h-30V255a15 15 0 00-15-15h-60a15 15 0 00-15 15v227h-30V375a15 15 0 00-15-15h-60a15 15 0 00-15 15v107h-30V315a15 15 0 00-15-15H46a15 15 0 00-15 15v167H15a15 15 0 100 30h482a15 15 0 100-30zm-76-302h30v302h-30zm-120 90h30v212h-30zM181 390h30v92h-30zM61 330h30v152H61zm0 0" />
                                </svg>
                                이더리움 지불 현황
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- // tab menu -->                
            </div>
            <!-- // header -->