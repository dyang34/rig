<nav class="ism_topmenu">
                        <div class="navInner">
                            <ul class="gnb">
								<li>
									<a href="/admin/m/adm_mem_list.php">회원 관리<span></span></a>
									<div class="subMenu">
										<a href="/admin/m/adm_mem_list.php">- 회원 리스트</a>
									</div>
								</li>
								<li style="margin-top: 10px;">
									<a href="#">HashRate<span></span></a>
									<div class="subMenu">
										<a href="/admin/m/hashrate_list.php">- HashRate 명세</a>
										<a href="/admin/m/hashrate_aggr_list.php" style="margin-top:10px;">- HashRate 집계</a>
									</div>
								</li>
								<li>
									<a href="/admin/m/payouts_list.php">Payouts<span></span></a>
									<div class="subMenu">
										<a href="/admin/m/payouts_list.php">- Payouts</a>
									</div>
								</li>
                                <li style="margin-top: 10px;">
									<a href="/admin/admin_logout.php">Logout<span></span></a>
									<div class="subMenu">
									</div>
								</li>
							</ul>
                        </div>
                    </nav>

<script type="text/javascript">
    $(document).ready(function() {
        $('.btn_nav').click(function(){
            $(this).toggleClass('on');
            $('.ism_topmenu').toggleClass('active');
            $('html,body').toggleClass('not-scroll');
        });
    });
</script>
