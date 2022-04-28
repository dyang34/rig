<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/blm_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/rig/miner/CurrentStatsMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";

// ini_set('memory_limit','512M');

if(!LoginManager::isManagerLogined()) {
    JsUtil::alertReplace("로그인이 필요합니다.    ","/admin");
    exit;
}

$arrDayOfWeek = array("일","월","화","수","목","금","토");

$_lastSeen_date_from = RequestUtil::getParam("_lastSeen_date_from", date("Y-m-01"));
$_lastSeen_date_to = RequestUtil::getParam("_lastSeen_date_to", date("Y-m-d"));
$_userid = RequestUtil::getParam("_userid", "");
$_lower_average_hashrate = RequestUtil::getParam("_lower_average_hashrate", "");
$_order_by = RequestUtil::getParam("_order_by", "lastSeen_date");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$wq = new WhereQuery(true, true);
$wq->addAndString("lastSeen_date", ">=", $_lastSeen_date_from);
$wq->addAndStringBind("lastSeen_date", "<", $_lastSeen_date_to, "date_add('?', interval 1 day)");
$wq->addAndString("userid","=",$_userid);
$wq->addAndString("currentHashrate","<=",$_lower_average_hashrate*1000*1000*1000);

$wq->addOrderBy($_order_by, $_order_by_asc);
$wq->addOrderBy("lastSeen_date","desc");

$rs = CurrentStatsMgr::getInstance()->getList($wq);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=루시어돈_Hashrate 명세(".$_lastSeen_date_from."_".$_lastSeen_date_to.")_".date('Ymd').".xls");
Header("Content-Description: PHP5 Generated Data");
Header("Pragma: no-cache");
Header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");
?>
<style>
td{font-size:11px;text-align:center;}
th{font-size:11px;text-align:center;color:white;background-color:#000081;}
</style>

<table cellpadding=3 cellspacing=0 border=1 bordercolor='#bdbebd' style='border-collapse: collapse'>
    <tr>
        <th style="color:white;background-color:#000081;">ID</th>
        <th style="color:white;background-color:#000081;">이름</th>
        <th style="color:white;background-color:#000081;">탐색일</th>
        <th style="color:white;background-color:#000081;">요일</th>
        <th style="color:white;background-color:#000081;">등록일</th>
        <th style="color:white;background-color:#000081;">Current Hashrate(H/s)</th>
        <th style="color:white;background-color:#000081;">Average Hashrate(H/s)</th>
        <th style="color:white;background-color:#000081;">Reported Hashrate(H/s)</th>
        <th style="color:white;background-color:#000081;">valiedShares</th>
        <th style="color:white;background-color:#000081;">activeWorkers</th>
        <th style="color:white;background-color:#000081;">Coin/Min(ETH)</th>
    </tr>
<?php
if ( $rs->num_rows > 0 ) {
    for ( $i=0; $i<$rs->num_rows; $i++ ) {
        $row = $rs->fetch_assoc();

        $idx_day_of_week = date('w', strtotime(substr($row['lastSeen_date'],0,10)));
?>
    <tr>
        <td class="tbl_first" style="text-align:center;"><?=$row["userid"]?></td>
        <td style="text-align:center;"><?=$row["rm_name"]?></td>
        <td style="text-align:center;"><?=$row["lastSeen_date"]?></td>
        <td style="text-align:center;<?=$idx_day_of_week=="6"?"color:blue;":($idx_day_of_week=="0"?"color:red;":"")?>"><?=$arrDayOfWeek[$idx_day_of_week]?></td>
        <td style="text-align:center;"><?=$row["time_date"]?></td>
        <td style="text-align:right;"><?=number_format($row["currentHashrate"], 1)?></td>
        <td style="text-align:right;"><?=number_format($row["averageHashrate"], 1)?></td>
        <td style="text-align:right;"><?=number_format($row["reportedHashrate"], 1)?></td>
        <td style="text-align:right;"><?=number_format($row["validShares"], 0)?></td>
        <td style="text-align:right;"><?=number_format($row["activeWorkers"], 0)?></td>
        <td style="text-align:right;"><?=number_format($row["coinsPerMin"], 5)?></td>
        
        
        </td>
    </tr>
<?php
    }
}
?>
	</table>
<?php
@ $rs->free();
?>



               
지난달 16일 인도

구자라트주 아마다바드 외곽에서 현지 주민들이 수확했던 밀을 
옮기고 있다.로이터뉴스1
  


[파이낸셜뉴스] 코로나19의 세계적 대유행(팬데믹)과 러시아의 우크라이나 침
공에 따른 원자재

가격 상승이 최소 2024년까지 계속될 전망이다.

27일(현지시간) 미국 CNN은 전날 세계은행(WB)이 발간한 원자재

시장 전망 보고서
에서 이같이 밝혔다. 은행은 지난 2년간의 에너지 가격 상승이 지난 1973년 오일 파
동 이후 최대이고, 곡물 가격 상승은 2008년 이후 가장 큰 폭이라고 분석했다.

인더밋 길 WB 부총재는 보고서에서 최근의 원자재

가격 상승에 대해 "전체적
으로 우리가 지난 1970년대 이후 겪은 가장 큰 원자재

충격"이라고 평가했다.

WB는 에너지 가격이 지난해 약 2배, 올해 50% 이상 상승한 이후 내년과 2025년에 
상승세가 다소 진정된다고 전망했다. 또한 은행은 올해 밀 가격이 40% 오를 것이라
며 곡물 가격도 올해 22.9% 치솟는다고 추정했다. 

러시아는 세계 최대 밀 수출국이며 우크라이나 역시 세계 6위의 수출국이다. 두 국
가의 수출량을 합하면 전 세계 물량 대비 27%에 달한다. 동시에 러시아는 현재 전 
세계 비료 공급의 15%를 책임지는 세계 최대 비료 수출국이고 러시아와 함께 서방의
제재를 받게 된 벨라루스도 주요 비료 수출국이다. 

WB는 최근 상승세로 인해 물가상승과 경기침체가 동시에 나타나는 스태그플레이션 
우려가 커졌다고 지적했다. 이어 "정책입안자들은 내수 진작을 위해 모든 기회
를 잡아야 하고, 글로벌 경제에 해를 끼치는 행동을 피해야 한다"고 주장했다.
특히 WB는 2024년 말까지 세계 물가가 전례 없이 높은 수준에 머물 것이며 저소득
가장에 큰 타격을 끼친다고 우려했다.
pjw@fnnews.com 박종원 기자

▶ 1500% 솟구치는 아직 5000원대 황금株, 급등 전 마지막 기회! [클릭]


▶“주식카톡방 완전무료 선언” 파격결정


[파이낸셜뉴스 주요뉴스] 
- 영남대 기숙사 옆 맨홀 속에서 발견된 女시신, 알고 보니...
- 밑가슴 '파격' 노출한 가수에 누리꾼 "한국인도 가능하다니..."
- 인기 女프로골퍼, 남친과 호텔 갔다가 홧김에 저지른 일이...
- 글래머 女 DJ, 항공사 직원 앞에서 바지 벗은 채로... '반전'
- 일론 머스크에게 "섹스 로봇 가능?" 묻자, 이미...
- 한주간 지친 나에게 디저트로 [파인애플]

하루만에 高수익 챙길 ‘초대형’ 바이오 대장株! 비밀리에 입수! ▶확인◀


▶▶[무료공개]◀◀ 지금 "1,000만원"만 있어도 당장 "이것"
부터 사라!! >>> (무료 체험)



※ 저작권자 ⓒ 파이낸셜뉴스. 무단 전재-재배포 금지

올해 밀 가격 40%, 곡물 23% 상승 예상
 
인도

 북부 펀잡 지역에서 27일(현지시간) 인부들이 올해 수확한 햇 밀을 한 데 모
으고 있다. [신화] 
[헤럴드경제=한지숙 기자] 세계은행(WB)은 우크라이나 전쟁이 촉발한 국제 

원자재

 
가격 고공행진이 2024년 말까지 계속될 것으로 내다봤다.

27일(현지시간) CNN 등에 따르면 WB는 지난 26일 발간한 '

원자재

 시장 전망 보고서'
;에서 지난 2년간의 에너지 가격 상승은 지난 1973년 오일 파동 이후 최대이며, 곡
물 가격 상승은 2008년 이후 가장 큰 폭으로 평가했다.

인더밋 길 WB 부총재는 보고서에서 "전체적으로 우리가 지난 1970년대 이후 겪은 
가장 큰 

원자재

 충격"이라고 평가했다.

에너지 가격은 지난해에 거의 두 배로 상승한 데 이어 올해도 50% 이상 오른 뒤에 
내년과 내후년에는 다소 완화할 것으로 WB는 내다봤다.

또 밀 가격이 40% 오르는 것을 비롯해 곡물 가격도 올해 22.9% 치솟을 것으로 보고
서는 전망했다.

우크라이나를 침공한 러시아는 원유와 천연가스, 석탄 등의 주요수출국이고, 침략
을 당한 우크라이나는 밀과 옥수수의 주요 공급자다.

더욱이 비료 가격이 크게 오르고, 주요 금속 가격이 치솟으면서 상황은 더욱 나빠
지고 있다.

WB는 "이런 가격 상승으로 인해 (사람들은) 스태그플레이션의 유령을 떠올리기 시
작했다"면서 "정책입안자들은 내수 진작을 위해 모든 기회를 잡아야 하고, 글로벌 
경제에 해를 끼치는 행동을 피해야 한다"고 조언했다고 CNN은 전했다.

스태그플레이션은 스태그네이션(경기침체)과 인플레이션(물가상승)이 합쳐진 말로,
 경제불황 속에서 물가상승이 동시에 발생하는 상태를 말한다.

이어 WB는 오는 2024년 말까지 물가가 전례 없이 높은 수준에 계속 머물 것으로 예
상된다고 밝혔다.

WB는 특히 높은 물가가 저소득가정에 가장 큰 타격을 줄 것이라고 우려했다.




▶▶ 외인+기관 조정장에서 매수! 역대급 1000%갈 특급 바이오株! (클릭)








▶▶[무료공개]◀◀ 지금 ＂1,000만원＂만 있어도 당장 ＂이것＂부터 사라!! >>> (
무료 체험) 

[프리미엄

 링크]
◆ [평생무료] "주식카톡방" 대박주를 추천받는데 100원도 안낸다~
◆ 아직도 추천주를 돈내고 받으세요?
▶FDA긴급승인! 9배 이상, 최소 300% 터질 2021 하반기 ‘역대급 NEW 바이오주’ (
확인)

10%수익을 40%로 만들어 준다고?? 영웅스탁론이라면 가능~!
[영웅스탁론]효과적인 레버리지를 이용하여 수익률을 4배로!!


▶▶2022년 800% 찍는 메타버스 황금株! 지금이 마지막 기회! [클릭]



◆ “또 상한가 터졌다” 3,000만원 수익회원 속출! [후속주 무료 공개]



[오늘의 인기기사]
◆ [국대스탁론]업계 최저금리 2.29%! 100%한종목 투자가능+신용/ 미수 대환가능
● 메타버스 1800조 시장 거머쥘 新대장주! 아직 바닥권! [선착순 한정공개]
▶기관, 외인 200만주 이상 거침없는 대규모 매집! “o o o” 인수전 시작! 선착순
 공개! (클릭)
▶▶【급등주소식】 실시간 급등 정보 매일마다 확인하세요 (클릭)
▶▶매일 아침 급등주 정보 무료로 보내드립니다 (클릭)



 