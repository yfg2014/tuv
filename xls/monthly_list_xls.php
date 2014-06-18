<?php
set_time_limit(60);
$now = date("Y-m-d-H-i-s");
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=month_report_{$now}.xls");
header("Pragma: no-cache");
header("Expires: 0");
!defined('IN_SUPU') && exit('Forbidden');

$a19db = array();
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
		  <td>2.认可标志</td>
          <td>3.组织名称</td>
          <td>4.组织名称（英文）</td>

          <td>5.组织原名</td>
		  <td>6.组织机构代码</td>
          <td>7.组织所在地域代码</td>
          <td>8.组织通讯地址</td>
          <td>9.组织通讯邮编</td>
          <td>10.组织联系电话</td>
          <td>11.组织联系传真</td>
          <td>12.法定代表人</td>
          <td>13.组织性质代码</td>
          <td>14.组织注册资本</td>
          <td>15.组织违法违规情况</td>
          <td>16.组织员工数</td>
          <td>17.体系人数</td>
          <td>18.初评获证日期</td>
          <td>19.认证证书注册号</td>
          <td>20.是否是子证书</td>
          <td>21.是否是多现场</td>
          <td>22.认证标准代码</td>
          <td>23.产品认证标准编号</td>
          <td>24.产品认证性质</td>
          <td>25.业务范围代码</td>
          <td>26.证书覆盖范围</td>
          <td>27.认证类型</td>
          <td>28.再认证次数</td>
          <td>29.监督次数</td>
          <td>30.实施审核的关键场所名称</td>
          <td>31.审核组成员</td>
          <td>32.审核日期</td>
          <td>33.审核人日数</td>
          <td>34.评定人员名单</td>
          <td>35.实收的认证费用</td>
          <td>36.风险系数</td>
          <td>37.发票号码</td>
          <td>38.颁证日期</td>
          <td>39.证书截止日期</td>
          <td>40.换证日期</td>
          <td>41.原注册号</td>
          <td>42.原颁证机构</td>
          <td>43.变更类别</td>
          <td>44.暂停原因</td>
          <td>45.撤销原因</td>
          <td>46.变更日期</td>
          <td>47.机构上报年月</td>
          <td>48.取数开始日期</td>
          <td>49.取数截止日期</td>
          <td>50.检查报告</td>

        </tr>
<?php
$sql_setup	="select * from sys_report50 limit 20000";
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
$a15=$forum_setup['a15'];
$a16=$forum_setup['a16'];
$a17=$forum_setup['a17'];
$a18=$forum_setup['a18'];
$a19=$forum_setup['a19'];
$a20=$forum_setup['a20'];
$a21=$forum_setup['a21'];
$a22=$forum_setup['a22'];
$a23=$forum_setup['a23'];
$a24=$forum_setup['a24'];
$a25=$forum_setup['a25'];
$a25 = cg_daima($a25);

$a26=$forum_setup['a26'];
$a27=$forum_setup['a27'];
$a28=$forum_setup['a28'];
$a29=$forum_setup['a29'];
$a30=$forum_setup['a30'];
$a31=$forum_setup['a31'];
$a32=$forum_setup['a32'];
$a33=$forum_setup['a33'];
$a34=$forum_setup['a34'];
$a35=$forum_setup['a35'];
$a36=$forum_setup['a36'];
$a37=$forum_setup['a37'];
$a38=$forum_setup['a38'];
$a39=$forum_setup['a39'];
$a40=$forum_setup['a40'];
$a41=$forum_setup['a41'];
$a42=$forum_setup['a42'];
$a43=$forum_setup['a43'];
$a44=$forum_setup['a44'];
$a45=$forum_setup['a45'];
$a46=$forum_setup['a46'];
$a47=$forum_setup['a47'];
$a48=$forum_setup['a48'];
$a49=$forum_setup['a49'];
$a50=$forum_setup['a50'];

if($a18=='0000-00-00'){$a18='';}
if($a38=='0000-00-00'){$a38='';}
if($a39=='0000-00-00'){$a39='';}
if($a40=='0000-00-00'){$a40='';}
if($a46=='0000-00-00'){$a46='';}
if($a48=='0000-00-00'){$a48='';}
if($a49=='0000-00-00'){$a49='';}

?>
<tr bgcolor=#FFFFFF>
<td><?php echo $a1;?></td>
<td><?php echo $a2;?></td>
<td><?php echo $a3;?></td>
<td><?php echo $a4;?></td>
<td><?php echo $a5;?></td>
<td><?php echo $a6;?></td>
<td><?php echo $a7;?></td>
<td><?php echo $a8;?></td>
<td><?php echo $a9;?></td>
<td><?php echo $a10;?></td>
<td><?php echo $a11;?></td>
<td><?php echo $a12;?></td>
<td><?php echo $a13;?></td>
<td><?php echo $a14;?></td>
<td><?php echo $a15;?></td>
<td><?php echo $a16;?></td>
<td><?php echo $a17;?></td>
<td><?php echo $a18;?></td>
<td><?php echo $a19;?></td>
<td><?php echo $a20;?></td>
<td><?php echo $a21;?></td>
<td><?php echo $a22;?></td>
<td><?php echo $a23;?></td>
<td><?php echo $a24;?></td>
<td><?php echo $a25;?></td>
<td><?php echo $a26;?></td>
<td><?php echo $a27;?></td>
<td><?php echo $a28;?></td>
<td><?php echo $a29;?></td>
<td><?php echo $a30;?></td>
<td><?php echo $a31;?></td>
<td><?php echo $a32;?></td>
<td><?php echo $a33;?></td>
<td><?php echo $a34;?></td>
<td><?php echo $a35;?></td>
<td><?php echo $a36;?></td>
<td><?php echo $a37;?></td>
<td><?php echo $a38;?></td>
<td><?php echo $a39;?></td>
<td><?php echo $a40;?></td>
<td><?php echo $a41;?></td>
<td><?php echo $a42;?></td>
<td><?php echo $a43;?></td>
<td><?php echo $a44;?></td>
<td><?php echo $a45;?></td>
<td><?php echo $a46;?></td>
<td><?php echo $a47;?></td>
<td><?php echo $a48;?></td>
<td><?php echo $a49;?></td>
<td><?php echo $a50;?></td>

<td>
<?php
	if(in_array($a19,$a19db))
	{
		$a43_t = '';
		echo "$a43_t 重复证书 $a19 ";
	}
?></td>
</tr>
<?php
$a19db []= $a19; //储存证书号
}
?>

</table>
<?php
//代码转换
function cg_daima($daima)
{
	global $db;
	$n_daima = array();
	$daima = explode('；',$daima);
	foreach($daima as $v)
	{
		$daimadb = $db->get_one("SELECT shangbao FROM setup_audit_code WHERE code ='$v'");
		if($daimadb['shangbao']!='')
		{
		$n_daima []= $daimadb['shangbao'];
		}
	}
	$n_daima = array_unique($n_daima);
	$n_daima = implode('；',$n_daima);
	return $n_daima;
}
?>