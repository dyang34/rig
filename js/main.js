$(document).ready(function(){
    
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

    if( $('.bit_display tr').length >= 5 ){
        $('.bit_tblInner').css({
            height: '500px'
        })
    } else {
        $('.bit_tblInner').css({
            height: '400px'
        }),$('#wrap').css({
            height: '100vh'
        })
    };

/*
    var lnb = $('.page-content').offset().top;
    $(window).scroll(function(){
        var window = $(this).scrollTop();
        if(lnb <= window) {
            $('.bit_header').addClass('fixed');
        } else {
            $('.bit_header').removeClass('fixed');
        }
    });
*/
});
