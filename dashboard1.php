<!DOCTYPE html>
<html lang="ko">
    <head>
        <title>채굴분양</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        
        <link type="text/css" rel="stylesheet" href="css/base.css" />
        <link type="text/css" rel="stylesheet" href="css/layout.css" />
        <link type="text/css" rel="stylesheet" href="css/common_1.css" />
        <link type="text/css" rel="stylesheet" href="css/table.css" />
        <link type="text/css" rel="stylesheet" href="css/login.css?t=<?php echo time(); ?>" />
        
        <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="js/chart.js"></script>
    </head>
    <body style="background: #e1d8d854;" >
        <div id="wrap" style="height:100vh;">
            <div class="bit_header">
                <div class="headerInner">
                    <span>
                        <span style="float: left; margin-top: 15px;">
                            <img src="../rig/images/common/personW.png" alt="" width="40" height="40" />
                        </span>
                        <span style="font-size: 14px; margin: 24px 0 0 7px; float: left; color: #fff;">
                            <span style="font-size: 16px; font-weight: 600; margin-right: 1px;">유태현</span>님의 모니터링 시스템
                        </span>
                    </span>
<?php /*                    
                    <h1 class="logo">
                        <a href="#">채굴분양</a>
                    </h1>
*/?>
                    <button class="btn_nav mobile">
                        <span>메뉴열기</span>
                        <span></span>
                        <span></span>
                    </button>
                    <nav id="navWrap" class="bit_topmenu">
                        <div class="navInner">
                            <ul class="gnb">
                                <li><a href="./rig_logout.php">LOGOUT</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="menu_tab">
                <ul>
                    <li class="menu_entire plus active" bit_tab="A">
                        <a href="#none">
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
                    <li class="menu_entire" bit_tab="B">
                        <a href="#none">
                            <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="currentColor" style="width: 16px;">
                                <path d="M76 240c12.1 0 23.1-4.8 31.2-12.6l44.2 22A44.9 44.9 0 00196 300a45 45 0 0040.6-64.4l60-60a45 45 0 0062.3-54l52.2-39.2a45 45 0 10-18-24l-52.2 39.2a45 45 0 00-65.5 56.8l-60 60a44.7 44.7 0 00-50.6 8.2l-44.2-22A44.9 44.9 0 0076 150a45 45 0 000 90zM436 30a15 15 0 110 30 15 15 0 010-30zm-120 90a15 15 0 110 30 15 15 0 010-30zM196 240a15 15 0 110 30 15 15 0 010-30zM76 180a15 15 0 110 30 15 15 0 010-30zm0 0" />
                                <path d="M497 482h-16V165a15 15 0 00-15-15h-60a15 15 0 00-15 15v317h-30V255a15 15 0 00-15-15h-60a15 15 0 00-15 15v227h-30V375a15 15 0 00-15-15h-60a15 15 0 00-15 15v107h-30V315a15 15 0 00-15-15H46a15 15 0 00-15 15v167H15a15 15 0 100 30h482a15 15 0 100-30zm-76-302h30v302h-30zm-120 90h30v212h-30zM181 390h30v92h-30zM61 330h30v152H61zm0 0" />
                            </svg>
                            이더리움 지불 현황
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="M_tab main_T_A page-wrapper">
                <div class="page-content">
<!-- chart -->
<div style="width: 100%; margin:0 auto; padding:0;">
<canvas id="myChart" width="300" height="190"></canvas>
</div>
<script type="text/javascript">
/* chart */
    var ctx = document.getElementById('myChart').getContext('2d'); 
    var chart = new Chart(ctx, {
    type: 'bar', // line
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], 
        datasets: [{ 
            label: 'First dataset', 
            backgroundColor: '#f97c0c', 
            borderColor: '#cb6102', 
            data: [20, 10, 5, 2, 20, 30, 45, 5, 10, 5, 2, 20]
        }]
    },
    options: {
        legend: { display: true },
        title: {
            display: true,
            text: 'Personal rate of return in 2021',
        }
    }
    });
    /* // chart */
</script>

<!-- // chart -->

                    <table cellpadding="0" cellspacing="0" border="0" class="bit_display" >
                        <thead>
                            <tr>
                                <th>기기명</th>
                                <th>해시율</th>
                                <th>업데이트 시간</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>a01_3070_8way</td>
                                <td>435.2 MH/s</td>
                                <td>8분 전</td>
                            </tr>
                            <tr>
                                <td>a02_3070_8way</td>
                                <td>478.2 MH/s</td>
                                <td>8분 전</td>
                            </tr>
                            <tr>
                                <td>a03_3070_8way</td>
                                <td>464.4 MH/s</td>
                                <td>8분 전</td>
                            </tr>
                            <tr>
                                <td>a04_3070_8way</td>
                                <td>452.7 MH/s</td>
                                <td>8분 전</td>
                            </tr>
                            <tr>
                                <td>a05_3070_8way</td>
                                <td>489.4 MH/s</td>
                                <td>8분 전</td>
                            </tr>
                            <tr>
                                <td>a06_3070_8way</td>
                                <td>435.2 MH/s</td>
                                <td>8분 전</td>
                            </tr>
                            <tr>
                                <td>a07_3070_8way</td>
                                <td>478.2 MH/s</td>
                                <td>8분 전</td>
                            </tr>
                            <tr>
                                <td>a08_3070_8way</td>
                                <td>464.4 MH/s</td>
                                <td>8분 전</td>
                            </tr>
                            <tr>
                                <td>a09_3070_8way</td>
                                <td>452.7 MH/s</td>
                                <td>8분 전</td>
                            </tr>
                            <tr>
                                <td>a10_3070_8way</td>
                                <td>489.4 MH/s</td>
                                <td>8분 전</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="M_tab main_T_B page-wrapper" style="display: none;">
                <div class="page-content">
                    <table cellpadding="0" cellspacing="0" border="0" class="bit_display">
                        <thead>
                                <tr>
                                    <th>일시</th>
                                    <th>ETH</th>
                                </tr>
                            </thead>
                        <tbody>
                                <tr>
                                    <td>2021년 09월 09일 <br/>10시 51분 34초</td>
                                    <td>11.64592 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 09월 08일 <br/>09시 05분 36초</td>
                                    <td>10.45657 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 09월 07일 <br/>08시 58분 30초</td>
                                    <td>11.24987 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 09월 06일 <br/>08시 56분 18초</td>
                                    <td>10.67896 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 09월 05일 <br/>08시 45분 06초</td>
                                    <td>12.15667 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 09월 04일 <br/>08시 16분 51초</td>
                                    <td>12.24691 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 09월 03일 <br/>07시 40분 16초</td>
                                    <td>11.45915 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 09월 02일 <br/>07시 37분 07초</td>
                                    <td>11.85715 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 09월 01일 <br/>06시 45분 43초</td>
                                    <td>11.53157 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 08월 31일 <br/>10시 51분 34초</td>
                                    <td>11.64592 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 08월 30일 <br/>09시 05분 36초</td>
                                    <td>10.45657 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 08월 29일 <br/>08시 58분 30초</td>
                                    <td>11.24987 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 08월 28일 <br/>08시 56분 18초</td>
                                    <td>10.67896 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 08월 27일 <br/>08시 45분 06초</td>
                                    <td>12.15667 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 08월 26일 <br/>08시 16분 51초</td>
                                    <td>12.24691 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 08월 25일 <br/>07시 40분 16초</td>
                                    <td>11.45915 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 08월 24일 <br/>07시 37분 07초</td>
                                    <td>11.85715 ETH</td>
                                </tr>
                                <tr>
                                    <td>2021년 08월 23일 <br/>06시 45분 43초</td>
                                    <td>11.53157 ETH</td>
                                </tr>
                            </tbody>
                    </table>
                </div>
            </div>

            <a href="#" class="bit_top" style="width:47px; height:43px; line-height:40px; position:fixed; right:23px; bottom:25px; background-color:#fff; border-radius:50%; box-sizing:border-box; transform: translateX(100px);">
                <img src="../rig/images/common/top.png" alt="top" style="width:100%; object-fit:cover; margin:-4px 0 0;">
            </a>
            
            <script>
                var lnb = $('.page-wrapper').offset().top;
                $(window).scroll(function(){
                    var window = $(this).scrollTop();
                    if(lnb <= window) {
                      $('.bit_top').css({
                            transform: 'translateX(0)',
                            transition: 'all .4s'
                        });
                    } else {
                        $('.bit_top').css({
                            transform: 'translateX(100px)'
                        });
                    }
                });
                
                $('.menu_entire').on('click',function(){
                    $('.M_tab').hide();
                    $('.main_T_'+$(this).attr('bit_tab')).show();

                    return false;
                });
                
                $('.btn_nav').click(function(){
                    $(this).toggleClass('on');
                    $('.bit_topmenu').toggleClass('active');
                    $('html,body').toggleClass('not-scroll');
                });
                
                $('.menu_entire').on('click',function(){
                    $('.menu_entire').removeClass('active');
                    $(this).addClass('active');
                });
                
                var lnb = $('.page-content').offset().top;
                $(window).scroll(function(){
                    var window = $(this).scrollTop();
                    if(lnb <= window) {
                      $('.bit_header').addClass('fixed');
                    } else {
                      $('.bit_header').removeClass('fixed');
                    }
                });
            </script>
        </div>
    </body>
</html>
































