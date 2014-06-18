<?php
set_time_limit(300);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=new_month_report".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/setup/setup_money_unit.php';
function get_unit($unit){
	global $setup_money_unit;
	foreach($setup_money_unit as $k=>$v){
		if($v['code'] == $unit){
			return $k;
		}
	}
}
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
		<td>2.实施审核的关键场所名称</td>
		<td>3.组织名称</td>
		<td>4.组织名称（英文）</td>
		<td>5.组织原名</td>
		<td>6.组织机构代码</td>
		<td>7.认可标志</td>
		<td>8.所属行业</td>
		<td>9.组织认证地址所在国家和地区代码</td>
		<td>10.组织认证地址所在行政区划代码</td>
		<td>11.组织通讯/注册地址</td>
		<td>12.组织通讯邮编</td>
		<td>13.组织联系电话</td>
		<td>14.组织联系传真</td>
		<td>15.法定代表人</td>
		<td>16.组织性质代码</td>
		<td>17.组织注册资本</td>
		<td>18.注册资本币种</td>
		<td>19.组织员工数</td>
		<td>20.体系相关的员工数</td>
		<td>21.初次获证日期</td>
		<td>22.认证证书号</td>
		<td>23.是否是子证书</td>
		<td>24.主认证证书号</td>
		<td>25.是否有多现场</td>
		<td>26.认证项目代码</td>
		<td>27.认证项目代码说明</td>
		<td>28.业务范围代码</td>
		<td>29.证书覆盖范围</td>
		<td>30.认证审核活动代码</td>
		<td>31.证书变更类别代码</td>
		<td>32.证书变更时所处的审核活动期间</td>
		<td>33.是否换证</td>
		<td>34.换证原因</td>
		<td>35.原（已）获认证的认证证书号</td>
		<td>36.原（已）颁证机构名称</td>
		<td>37.换证日期</td>
		<td>38.再认证次数</td>
		<td>39.监督次数</td>
		<td>40.审核开始日期</td>
		<td>41.审核截至日期</td>
		<td>42.审核人日数</td>
		<td>43.是否结合</td>
		<td>44.评定人员名单</td>
		<td>45.实收的认证费用</td>
		<td>46.实收的认证费用币种</td>
		<td>47.发票号码</td>
		<td>48.风险系数</td>
		<td>49.颁证日期</td>
		<td>50.证书截止日期</td>
		<td>51.证书状态</td>
		<td>52.暂停原因</td>
		<td>53.暂停开始时间</td>
		<td>54.暂停截至时间</td>
		<td>55.撤销原因</td>
		<td>56.撤销时间</td>
		<td>57.变更日期</td>
		<td>58.取数开始日期</td>
		<td>59.取数截止日期</td>
		<td>证书时间</td>
		<td>评定时间</td>
		<td>变更时间</td>
    </tr>
<?
$sql_setup	="select * from sys_report50_new limit 20000";
$quer_setup	= mysql_query($sql_setup);
while($forum_setup= mysql_fetch_array($quer_setup))
{
	$a1=$forum_setup['a1'];
	$forum_setup['a2'] == '' ? $a2 = $a1 : $a2=$forum_setup['a2'];
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
	$a59=$forum_setup['a59'];
	$a43=$forum_setup['a43'];
	$a44=$forum_setup['a44'];
	$a45=$forum_setup['a45'];
	$a46=$forum_setup['a46'];
	$a47=$forum_setup['a47'];
	$a48=$forum_setup['a48'];
	$a49=$forum_setup['a49'];
	$a50=$forum_setup['a50'];
	$a51=$forum_setup['a51'];
	$a52=$forum_setup['a52'];
	$a53=$forum_setup['a53'];
	$a54=$forum_setup['a54'];
	$a55=$forum_setup['a55'];
	$a56=$forum_setup['a56'];
	$a57=$forum_setup['a57'];
	$a58=$forum_setup['a58'];
	$zs_date=$forum_setup['zs_date'];
	$xm_date=$forum_setup['xm_date'];
	$cg_date=$forum_setup['cg_date'];

	if($a4 == ''){$a4 = '/';}
	if($a6 == '*********' || $a6 == '**********'){$a6 = '000000000';}
	if($a21=='0000-00-00'){$a21='';}
	//else{$a21=date("Ymd",strtotime($a21));}
	if($a37=='0000-00-00'){$a37='';}
	//else{$a37=date("Ymd",strtotime($a37));}
	if($a40=='0000-00-00'){$a40='';}
	//else{$a40=str_replace('-','',$a40);}
	if($a41=='0000-00-00'){$a41='';}
	//else{$a41=str_replace('-','',$a41);}
	if($a48=='0000-00-00'){$a48='';}
	//else{$a48=date("Ymd",strtotime($a48));}
	if($a49=='0000-00-00'){$a49='';}
	//else{$a49=date("Ymd",strtotime($a49));}
	if($a52=='0000-00-00'){$a52='';}
	//else{$a52=date("Ymd",strtotime($a52));}
	if($a53=='0000-00-00'){$a53='';}
	//else{$a53=date("Ymd",strtotime($a53));}
	if($a55=='0000-00-00'){$a55='';}
	//else{$a55=date("Ymd",strtotime($a55));}
	if($a56=='0000-00-00'){$a56='';}
	//else{$a56=date("Ymd",strtotime($a56));}
	if($a57=='0000-00-00'){$a57='';}
	//else{$a57=date("Ymd",strtotime($a57));}
	if($a58=='0000-00-00'){$a58='';}
	//else{$a58=date("Ymd",strtotime($a58));}
	if($zs_date=='0000-00-00'){$zs_date='';}
	if($xm_date=='0000-00-00'){$xm_date='';}
	if($cg_date=='0000-00-00'){$cg_date='';}
	$a18=='' && $a18='01';
	$a9 == '' && $a9 = '156';
	($a33 == '1' && $a36=='') && $a36 = '';
	if($a30 == '04' || $a30 == '99'){
		$a40=$a41=$a42=$a44=$a45=$a46='';
	}
	if($a33 == '0'){
		$a34=$a35=$a36=$a37='';
	}
	if($a50 != '01'){
		$a32=$a34=$a35=$a36=$a37=$a38=$a39=$a40=$a41=$a42=$a43=$a44=$a45=$a46='';
	}
	$a4 == '' && $a4 = '/';
	$a4 == ' ' && $a4 = '/';
	$a4 == '&nbsp;' && $a4 = '/';
	$a33== '' && $a33= '0';
	$a30 == '01' && $a33 = '0';
	if($a33 == '0'){$a34=$a35=$a36=$a37='';}
?>
	<tr bgcolor="#FFFFFF">
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
		<td><?echo $a15;?></td>
		<td><?echo $a16;?></td>
		<td><?echo $a17;?></td>
		<td><?echo get_unit($a18);?></td>
		<td><?echo $a19;?></td>
		<td><?echo $a20;?></td>
		<td><?echo $a21;?></td>
		<td><?echo $a22;?></td>
		<td><?echo $a23;?></td>
		<td><?echo $a24;?></td>
		<td><?echo $a25;?></td>
		<td><?echo $a26;?></td>
		<td><?echo $a27;?></td>
		<td><?echo $a28;?></td>
		<td><?echo $a29;?></td>
		<td><?echo $a30;?></td>
		<td><?echo $a31;?></td>
		<td><?echo $a32;?></td>
		<td><?echo $a33;?></td>
		<td><?echo $a34;?></td>
		<td><?echo $a35;?></td>
		<td><?echo $a36;?></td>
		<td><?echo $a37;?></td>
		<td><?echo $a38;?></td>
		<td><?echo $a39;?></td>
		<td><?echo $a40;?></td>
		<td><?echo $a41;?></td>
		<td><?echo $a42;?></td>
		<td><?echo $a59;?></td>
		<td><?echo $a43;?></td>
		<td><?echo $a44;?></td>
		<td><?echo $a45;?></td>
		<td><?echo $a46;?></td>
		<td><?echo $a47;?></td>
		<td><?echo $a48;?></td>
		<td><?echo $a49;?></td>
		<td><?echo $a50;?></td>
		<td><?echo $a51;?></td>
		<td><?echo $a52;?></td>
		<td><?echo $a53;?></td>
		<td><?echo $a54;?></td>
		<td><?echo $a55;?></td>
		<td><?echo $a56;?></td>
		<td><?echo $a57;?></td>
		<td><?echo $a58;?></td>
		<td><?echo $zs_date;?></td>
		<td><?echo $xm_date;?></td>
		<td><?echo $cg_date;?></td>
	</tr>
<? }?>

</table>