<style type="text/css">
<!--
.weekday {
    font-size: 9pt;
    color: #FF0000;
    text-align: center;
}
.normalday {
    font-size: 9pt;
    color: #000000;
    text-align: center;
}
.weekdaytoday {
    font-size: 9pt;
    color: #FF0000;
    text-align: center;
    background-color: #FFD9D9;
    font-weight: bold;
}
.normaldaytoday {
    font-size: 9pt;
    color: #000000;
    text-align: center;
    background-color: #DDDDDD;
    font-weight: bold;
}
.othermonth {
    font-size: 9pt;
    font-style: italic;
    color: #999999;
    text-align: center;
}
-->
</style>
<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
function calendar()
{
	global $db;
	$hr_id = $_GET['hr_id'];
	$s = new hr_information();
	$rows = $s->query($hr_id);

    if($_GET['ym'])
    {
        $year = substr($_GET['ym'],0,4);
        $month = substr($_GET['ym'],4,(strlen($_GET['ym'])-4));

        if($month>12)
        {
            $year += floor($month/12);
            $month = $month % 12;
        }
        if($year > 2030) $year = 2030;
        if($year < 1980) $year = 1980;
    }

    $year = isset($year) ? $year : date('Y');
    $month = isset($month) ? $month : date('n');

    if($year==date('Y') && $month==date('n')) $today = date('j');

    if($month-1 == 0)
        $prevmonth = ($year - 1)."12";
    else $prevmonth = $year.($month - 1);

    if($month+1 == 13)
        $nextmonth = ($year+1)."1";
    else $nextmonth = $year.($month+1);

    $prevyear = ($year - 1).$month;
    $nextyear = ($year + 1).$month;

echo <<<VKN
  <div class="blank"></div>
  <table align="center" width="900" height="30" border="1" cellpadding="1" cellspacing="1" class="c_table">
  <tr>
  	<td align="right" width="80">审核员：</td>
  	<td width="80">$rows[username]</td>
    <td class="weekday"><a href="index.php?m=report&do=auditor_fullcalendar&hr_id=$hr_id&ym=$prevyear"><<</a></td>
    <td class="normalday"><a href="index.php?m=report&do=auditor_fullcalendar&hr_id=$hr_id&ym=$prevmonth"><</a></td>
    <td colspan="3" class="normalday">$year 年 $month 月</td>
    <td class="normalday"><a href="index.php?m=report&do=auditor_fullcalendar&hr_id=$hr_id&ym=$nextmonth">></a></td>
    <td class="weekday"><a href="index.php?m=report&do=auditor_fullcalendar&hr_id=$hr_id&ym=$nextyear">>></a></td>
  </tr>
  </table>
<table align="center" width="900" height="580" border="1" cellpadding="1" cellspacing="1" class="c_table" style="margin-top:5x;">
  <tr>
    <td class="weekday">日</td>
    <td class="normalday">一</td>
    <td class="normalday">二</td>
    <td class="normalday">三</td>
    <td class="normalday">四</td>
    <td class="normalday">五</td>
    <td class="weekday">六</td>
  </tr>
VKN;
    $nowtime = mktime(0,0,0,$month,1,$year);//当月1号转为秒
    $daysofmonth = date(t,$nowtime);//当月天数
    $weekofbeginday = date(w,$nowtime);//当月第一天是星期几
    $weekofendday = date(w,mktime(0,0,0,$month+1,0,$year));//当月最后一天是星期几
    $daysofprevmonth = date(t,mktime(0,0,0,$month,0,$year));//上个月天数

    $count = 1;//计数
    //列出上月后几天
    for($i = 1 ; $i <= $weekofbeginday ; $i++)
        {
            echo     "<td class='othermonth'>".($daysofprevmonth-$weekofbeginday+$i)."</td>";
            $count++;
        }
    //当月全部
    for($i = 1 ; $i <= $daysofmonth ; $i++)
        {
        	$i < 10 ? $i_t = '0'.$i : $i_t = $i;
        	$temp_day = $year.'-'.$month.'-'.$i_t;
        	$zuzhi_id = $eiregistername = '';
        	$pr_q = $db->query("SELECT zuzhi_id FROM xm_auditor WHERE empId='$hr_id' AND (taskBeginDate<='$temp_day' AND taskEndDate>='$temp_day')");
            while($pr = $db->fetch_array($pr_q)){
            	$zuzhi_id []= $pr['zuzhi_id'];
            }
            $zuzhi_id = implode("','",array_unique($zuzhi_id));
            if($zuzhi_id!=''){
            	$qy_q = $db->query("SELECT eiregistername FROM mk_company WHERE id IN('$zuzhi_id')");
            	while($qy = $db->fetch_array($qy_q)){
            		$eiregistername []= $qy['eiregistername'];
            	}
            	 $eiregistername = implode("<br>",array_unique($eiregistername));
            }
            $css = ($count%7==0 || $count%7==1)?"weekday":"normalday";
            if($i == $today) $css .= "today";

            echo     "<td width=14% class='".$css."' valign=top><div align=right>".$i."</div><div align=left style=\"padding:5px\">".$eiregistername."</div></td>";
            if($count%7==0) echo "</tr><tr>";
            $count++;
        }
    //下月前几天
    for ($i = 1;$i <= 6-$weekofendday;$i++)
        {
            echo     "<td class='othermonth'>".$i."</td>";
        }

    echo <<<VKN
</table>
VKN;
}

include T_DIR.'header.htm';
calendar();
include T_DIR.'footer.htm';
?>