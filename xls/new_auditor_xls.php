<?php
set_time_limit(300);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=auditor_month_report".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");
!defined('IN_SUPU') && exit('Forbidden');

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
TD{
    font-size: 12px;
    vertical-align: middle;
	vnd.ms-excel.numberformat:@;
}
</style>
<table border="1">
    <tr>
		<td>1.认证机构批准号</td>
		<td>2.证书编号</td>
		<td>3.审核开始时间</td>
		<td>4.审核一二阶段</td>
		<td>5.审核截止日期</td>
		<td>6.审核员姓名</td>
		<td>7.证件类型</td>
		<td>8.证件号码</td>
		<td>9.资格类型</td>
		<td>10.资格证书编号</td>
		<td>11.审核员身份角色</td>
		<td>12.是否专业审核员</td>
		<td>13.是否专职</td>
		<td>14.见证人/被见证人</td>
		<td>企业名称</td>
		<td>认证体系</td>
		<td>证书时间</td>
		<td>评定时间</td>
    </tr>
<?php
$sql_setup	="select * from sys_report_auditor limit 20000";
$quer_setup	= mysql_query($sql_setup);
while($forum_setup= mysql_fetch_array($quer_setup))
{
$a1=$forum_setup['a1'];
$a2=$forum_setup['a2'];
$a3=$forum_setup['a3'];
$a4=$forum_setup['a4'];
$a5=$forum_setup['a5'];
$a6=$forum_setup['a6'];
$a7=$forum_setup['a7'];
$a8=$forum_setup['a8'];
$a9=$forum_setup['a9'];
$a10=$forum_setup['a10'];
$a11=$forum_setup['a11'];
$a12=$forum_setup['a12'];
$a13=$forum_setup['a13'];
$a14=$forum_setup['a14'];
$eiregistername = $forum_setup['eiregistername'];
$iso = $forum_setup['iso'];
$zs_date=$forum_setup['zs_date'];
$xm_date=$forum_setup['xm_date'];
if($zs_date=='0000-00-00'){$zs_date='';}
if($xm_date=='0000-00-00'){$xm_date='';}
if($a3=='0000-00-00'){$a3='';}
if($a5=='0000-00-00'){$a5='';}

?>
<tr bgcolor=#FFFFFF>
<td><?echo $a1;?></td>
<td><?echo $a2;?></td>
<td><?echo $a3;?></td>
<td><?echo $a4;?></td>
<td><?echo $a5;?></td>
<td><?echo $a6;?></td>
<td><?echo $a7;?></td>
<td><?echo $a8;?></td>
<td><?echo $a9;?></td>
<td><?echo $a10;?></td>
<td><?echo $a11;?></td>
<td><?echo $a12;?></td>
<td><?echo $a13;?></td>
<td><?echo $a14;?></td>
<td><?echo $eiregistername;?></td>
<td><?echo $iso;?></td>
<td><?echo $zs_date;?></td>
<td><?echo $xm_date;?></td>
</tr>
<? }?>

</table>