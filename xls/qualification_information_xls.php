<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/topsearch.php');
Power::CkPower('Z3S');
$seach_arr=array(
	'username'=>array(
		'kind'=>'hidden',
		'name'=>'username',
		'sql_field'=>'hr_id ',
		'sql_kind'=>'in'
	  ),
	 'qualification_no'=>array(
	  'kind'=>'hidden',
	  'name'=>'qualification_no',
	  'sql_field'=>'qualification_no',
	  'sql_kind'=>'='
	),
	 'qualification'=>array(
	  'kind'=>'hidden',
	  'name'=>'qualification',
	  'sql_field'=>'qualification',
	  'sql_kind'=>'='
	),
	'iso'=>array(
		'kind'=>'hidden',
		'name'=>'iso',
		'sql_field'=>'iso',
		'sql_kind'=>'%like%'
	),
	'online'=>array(
		'kind'=>'hidden',
		'name'=>'online',
		'sql_field'=>'online',
		'sql_kind'=>'='
	),
	'htfrom'=>array(
	  'kind'=>'hidden',
	  'name'=>'htfrom',
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	'province'=>array(
	  'kind'=>'hidden',
	  'name'=>'province',
	  'sql_field'=>'hr_id',
	  'sql_kind'=>'in'
	),
	't_date'=>array(
		'kind'=>'hidden',
		'name'=>'t_date',
		'sql_field'=>'e_date',
		'sql_kind'=>'<='
	),
	'worktype'=>array(
	  'kind'=>'hidden',
	  'name'=>'worktype',
	  'sql_field'=>'hr_id',
	  'sql_kind'=>'='
	),
	 'date1'=>array(
		'kind'=>'hidden',
		'name'=>'date',
		'sql_field'=>'s_date',
		'sql_kind'=>'>='
	 ),
	'date2'=>array(
		'kind'=>'hidden',
		'name'=>'e_date',
		'sql_field'=>'s_date',
		'sql_kind'=>'<='
	 ),
	 'yearok'=>array(
		'kind'=>'hidden',
		'name'=>'yearok',
		'sql_field'=>'yearok',
		'sql_kind'=>'='
	),
);

$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
td{
    font-size: 12px;
    vertical-align: middle;
vnd.ms-excel.numberformat:@;
}
</style>
<table border="1">
	<tr bgcolor="#F2F2F2">
		<td>序号</td>
		<td>人员编号</td>
		<td>姓名</td>
		<td>行政档案号</td>
		<td>审核档案号</td>
		<td>学历</td>
		<td>毕业学校</td>
		<td>毕业时间</td>
		<td>所学专业</td>
		<td>职称等级</td>
		<td>职称</td>
		<td>性别</td>
		<td>民族</td>
		<td>政治面貌</td>
		<td>身份证地址</td>
		<td>证件名称</td>
		<td>证件号码</td>
		<td>联系手机</td>
		<td>电子邮件</td>
		<td>在职	</td>
		<td>所属分支</td>
		<td>出生日期</td>
		<td>省级地址</td>
		<td>县级地址</td>
		<td>家庭地址</td>
		<td>家庭电话</td>
		<td>单位地址</td>
		<td>单位电话</td>
		<td>传真</td>
		<td>开户地址</td>
		<td>银行卡号</td>
		<td>所属部门</td>
		<td>身份</td>
		<td>转机构公告号</td>
		<td>批准转换日期</td>
		<td>转换机构证明编号</td>
		<td>合同编号</td>
		<td>社保编号</td>
		<td>电脑编号</td>
		<td>审核频次</td>
		<td>入职时间</td>
		<td>聘用开始</td>
		<td>聘用结束</td>
		<td>解聘日期</td>
		<td>在职状况</td>
		<td>备注信息</td>
		<td>体系</td>
		<td>标准版本</td>
		<td>注册资格</td>
		<td>资格证号码</td>
		<td>资格证开始日期</td>
		<td>资格证结束日期</td>
		<td>资格证状态</td>
		<td>专业类别</td>
	</tr>
<?php
$sql	= "SELECT * FROM `{$dbtable['hr_reg_qualification']}` WHERE 1 {$sql_temp} ORDER BY id DESC";
$query	= $db->query($sql);
while($row = $db->fetch_array($query))
{
	$rows = '';
	$arr = $xingzhi = array();
	$rows = $db->get_one("SELECT * FROM `{$dbtable['hr_information']}` WHERE id='{$row['hr_id']}' LIMIT 0,1");
	$rows['sex'] = Cache::cache_sex($rows['sex']);
	$rows['worktype'] = Cache::cache_zaizhi($rows['worktype']);
	$rows['bumen'] = Cache::cache_hr_department($rows['bumen']);
	$rows['working'] = Cache::cache_working($rows['working']);
	$rows['shenfenkind'] = Cache::cache_hr_certificate($rows['shenfenkind']);
	$rows['politics'] = Cache::cache_hr_politics($rows['politics']);
	$arr = explode(',',$rows['xingzhi']);
	foreach ($arr as $v){
		$xingzhi []= Cache::cache_hr_xingzhi($v);
	}
	$rows['xingzhi'] = implode(';',$xingzhi);


	$xueli  = $school = $graduate_date = $zhuanye = array();
	$sql2	= "SELECT xueli,school,graduate_date,zhuanye FROM `{$dbtable['hr_education']}` WHERE hr_id='{$row['hr_id']}' ORDER BY id DESC";
	$query2	= $db->query($sql2);
	while($result = $db->fetch_array($query2))
	{
		$xueli 		   []= $result['xueli'];
		$school 	   []= $result['school'];
		$graduate_date []= $result['graduate_date'];
		$zhuanye 	   []= $result['zhuanye'];
	}
	$rows['xueli'] = implode(';',$xueli);
	$rows['school'] = implode(';',$school);
	$rows['graduate_date'] = implode(';',$graduate_date);
	$rows['zhuanye'] = implode(';',$zhuanye);

	$profession = $zc_msg = array();
	$sql2	= "SELECT profession,zc_msg FROM `{$dbtable['hr_profession']}` WHERE hr_id='{$row['hr_id']}' ORDER BY id DESC";
	$query2	= $db->query($sql2);
	while($result = $db->fetch_array($query2))
	{
		$profession	[]= Cache::cache_hr_profession($result['profession']);
		$zc_msg		[]= $result['zc_msg'];
	}
	$rows['profession'] = implode(';',$profession);
	$rows['zc_msg'] = implode(';',$zc_msg);

	$sreach = '';
	switch ($row['qualification'])
	{
		case '1001':
			$sreach = " and qualification = '1001'";
			break;
		case '1002':
		case '1003':
		case '1004':
			$sreach = " and qualification = '1003'";
			break;
		case '1005':
			$sreach = " and qualification = '1005'";
			break;
		case '1006':
			$sreach = " and qualification = '1006'";
			break;
		default:
			$sreach = '';
	}
	$audit_code = array();
	$sql2	= "SELECT * FROM `{$dbtable['hr_audit_code']}` WHERE hr_id='{$row['hr_id']}' and iso='{$row['iso']}' {$sreach} ORDER BY iso ASC";
	$query2	= $db->query($sql2);
	while($result = $db->fetch_array($query2))
	{
		$audit_code []= $result['xiaolei'];
	}
	$rows['audit_code'] = implode(';',$audit_code);

	$row['qualification'] = Cache::cache_hr_reg_qualification($row['qualification']);
	$row['online'] = Cache::cache_hr_reg_qualification_online($row['online']);
?>
	<tr>
		<td><?php echo $rows['id']; ?></td>
		<td><?php echo $rows['idcode']; ?></td>
		<td><?php echo $rows['username']; ?></td>
		<td><?php echo $rows['xz_dangan']; ?></td>
		<td><?php echo $rows['sh_dangan']; ?></td>
		<td><?php echo $rows['xueli']; ?></td>
		<td><?php echo $rows['school']; ?></td>
		<td><?php echo $rows['graduate_date']; ?></td>
		<td><?php echo $rows['zhuanye']; ?></td>
		<td><?php echo $rows['profession']; ?></td>
		<td><?php echo $rows['zc_msg']; ?></td>
		<td><?php echo $rows['sex']; ?></td>
		<td><?php echo $rows['national']; ?></td>
		<td><?php echo $rows['politics']; ?></td>
		<td><?php echo $rows['cardaddress']; ?></td>
		<td><?php echo $rows['shenfenkind']; ?></td>
		<td><?php echo $rows['cardid']; ?></td>
		<td><?php echo $rows['phone']; ?></td>
		<td><?php echo $rows['email']; ?></td>
		<td><?php echo $rows['worktype']; ?></td>
		<td><?php echo $rows['suozaidanwei']; ?></td>
		<td><?php echo $rows['birthday']; ?></td>
		<td><?php echo $rows['city_msg']; ?></td>
		<td><?php echo $rows['city_msg']; ?></td>
		<td><?php echo $rows['address']; ?></td>
		<td><?php echo $rows['tel']; ?></td>
		<td><?php echo $rows['dwdizhi']; ?></td>
		<td><?php echo $rows['dwtel']; ?></td>
		<td><?php echo $rows['fax']; ?></td>
		<td><?php echo $rows['bankmsg']; ?></td>
		<td><?php echo $rows['bankcode']; ?></td>
		<td><?php echo $rows['bumen']; ?></td>
		<td><?php echo $rows['xingzhi']; ?></td>
		<td></td>
		<td></td>
		<td></td>
		<td><?php echo $rows['hetonghao']; ?></td>
		<td><?php echo $rows['shebaohao']; ?></td>
		<td></td>
		<td></td>
		<td><?php echo $rows['ruzhidate']; ?></td>
		<td><?php echo $rows['pinyongdate']; ?></td>
		<td><?php echo $rows['pinyongover']; ?></td>
		<td><?php echo $rows['lizhidate']; ?></td>
		<td><?php echo $rows['working']; ?></td>
		<td><?php echo $row['other']; ?></td>
		<td><?php echo $row['iso']; ?></td>
		<td><?php echo $row['audit_ver']; ?></td>
		<td><?php echo $row['qualification']; ?></td>
		<td><?php echo $row['qualification_no']; ?></td>
		<td><?php echo $row['s_date']; ?></td>
		<td><?php echo $row['e_date']; ?></td>
		<td><?php echo $row['online']; ?></td>
		<td><?php echo $rows['audit_code']; ?></td>
<?php
}
?>
	</tr>
</table>

















