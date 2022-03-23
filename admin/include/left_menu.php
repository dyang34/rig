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
    if (LoginManager::getUserLoginInfo("iam_grade") >= 10) {
    ?>
            <li>
                <a href='#' class="btn <?=$menuCate==3?"on":""?>">기초 정보<span></span></a>
                <div class="subMenu">
                	<a href="/admin/adm_mem_list.php " class="<?=$menuNo==9?"on":""?>">- 회원 관리</a>
                    <a href="/admin/goods_list.php " class="<?=$menuNo==4?"on":""?>">- 상품 관리</a>
                    <a href="/admin/goods_item_list.php " class="<?=$menuNo==24?"on":""?>">- 품목 관리</a>
                    <a href="/admin/brand_list.php " class="<?=$menuNo==5?"on":""?>">- 브랜드 관리</a>
                    <a href="/admin/category_list.php " class="<?=$menuNo==6?"on":""?>">- 카테고리 관리</a>
                    <a href="/admin/channel_list.php " class="<?=$menuNo==7?"on":""?>">- 채널 리스트</a>
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