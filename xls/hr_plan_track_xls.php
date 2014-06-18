<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/topsearch.php');
include(SET_DIR.'setup_hr_auditor_plan.php');

Power::CkPower('Z3S');
$seach_arr=array(
	'username'=>array(
		'kind'=>'username',
		'name'=>'username',
		'msg'=>'姓名',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'hr_id ',
		'sql_kind'=>'in'
	  ),
	  'yjm'=>array(
		'kind'=>'text',
		'name'=>'yjm',
		'msg'=>'易记码',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'',
		'sql_kind'=>''
	  ),
	 'plan_item'=>array(
	  'kind'=>'select',
	  'name'=>'plan_item',
	  'msg'=>'拟培养方向',
	  'width'=>'80px',
	  'arr'=>$setup_hr_plan_item,
	  'sql_field'=>'qualification',
	  'sql_kind'=>'='
	),
);

$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;
$sql_temp	.= Power::xt_htfrom();
$sql_temp = " AND actual_complete_date='0000-00-00'";
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
		<td>姓名</td>
		<td>地区</td>
		<td>体系</td>
		<td>培养方向</td>
		<td>目前经历</td>
	</tr>
<?php
$sql = "SELECT * FROM hr_auditor_plan WHERE 1 $sql_temp ORDER BY id DESC";
$query = $db->query($sql);
while($v = $db->fetch_array($query))
{
	$rows = $db->get_one("SELECT city_msg,username FROM `{$dbtable['hr_information']}` WHERE id='{$v[hr_id]}'"); 
	$v['username'] = $rows['username'];
	$v['city_msg'] = $rows['city_msg'];
	$v['plan_item'] = $setup_hr_auditor_plan[$v['plan_item']];
?>
	<tr>
		<td><?php echo $v[username]; ?></td>
		<td><?php echo $v[city_msg]; ?></td>
		<td><?php echo $v[iso]; ?></td>
		<td><?php echo $v[plan_item]; ?></td>
		<td><?php echo $v[remark]; ?></td>
	</tr>
<?php
}
?>
</table>
