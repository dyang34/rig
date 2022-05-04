<div class="side_scroll">

    <div id="side">
        <h1 class="wms_setting">MENU</h1>
        <ul class="gp_wms_snb mainMenu">
            <li class="">
                <a href='#' class="btn <?=$menuCate==1?"on":""?>">회원 관리<span></span></a>
                <div class="subMenu">
                    <a href="/admin/adm_mem_list.php" class="<?=$menuNo==1?"on":""?>">- 회원 리스트</a>
    			</div>
            </li>
    <?php
    if (LoginManager::getManagerLoginInfo("adm_grade") >= 5) {
    ?>
            <li>
                <a href='#' class="btn <?=$menuCate==2?"on":""?>">HashRate<span></span></a>
                <div class="subMenu">
                	<a href="/admin/hashrate/hashrate_list.php " class="<?=$menuNo==2?"on":""?>">- HashRate 명세</a>
                    <a href="/admin/hashrate/hashrate_aggr_list.php " class="<?=$menuNo==3?"on":""?>">- HashRate 집계</a>
                </div>
            </li>
            <li>
                <a href='#' class="btn <?=$menuCate==3?"on":""?>">Payouts<span></span></a>
                <div class="subMenu">
                	<a href="/admin/payouts/payouts_list.php " class="<?=$menuNo==4?"on":""?>">- Payouts 명세</a>
                </div>
            </li>
    <?php
    }
    ?>
        </ul>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click','.mainMenu .btn',function(){
        if( $(this).hasClass('on') ){
            $('.mainMenu .btn').removeClass('on').next().slideUp(300);
            $('.mainMenu .subMenu a').removeClass('on');
        } else {
            $(this).addClass('on').next().slideDown(300).parent().siblings().find('.btn').removeClass('on').next().slideUp(300);
        }
    });
				
    $(document).on('click','.mainMenu .subMenu a',function(e){

        $('.mainMenu .subMenu a').removeClass('on');
        $(this).addClass('on');
        
    });

    $(document).ready(function() {
    	$(".mainMenu .btn.on").next().slideDown(300).parent().siblings().find('.btn').removeClass('on').next().slideUp(300);
    	
//    	$('.mainMenu .subMenu a.on').scrollIntoView(true);
//    	alert($('.mainMenu .subMenu .on').scrollTop());
    });

</script>