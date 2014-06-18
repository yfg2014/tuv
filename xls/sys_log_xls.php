<?php
set_time_limit(60);
$filename = iconv("utf-8","gbk",'操作日记');
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".date("Y-m-d")."_".$filename.".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/topsearch.php');

$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'eiregistername'=>array(
		'kind'=>'company',   //要搜索的类型，企业搜索固定为company
		'name'=>'eiregistername',
		'msg'=>'企业名称',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'zuzhi_id',
		'sql_kind'=>'in'
	),
	'eilinkman'=>array(
		'kind'=>'text',
		'name'=>'eilinkman',
		'msg'=>'操作人员',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'username',
		'sql_kind'=>'%like%'
	),
	's_date'=>array(
		'kind'=>'date1',
		'name'=>'s_date',
		'msg'=>'时间范围',
		'width'=>'80px',
		'arr'=>'',
		'sql_field'=>'logtime',
		'sql_kind'=>'>='
	 ),
	'e_date'=>array(
		'kind'=>'date2',
		'name'=>'e_date',
		'msg'=>'',
		'width'=>'80px',
		'arr'=>'',
		'sql_field'=>'logtime',
		'sql_kind'=>'<='
	 ),
	 'info'=>array(
		'kind'=>'text',
		'name'=>'info',
		'msg'=>'操作内容',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'info',
		'sql_kind'=>'%like%'
	 ),
);
$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;

$sql = "SELECT * FROM {$dbtable['sys_log']} WHERE 1 {$sql_temp} ORDER BY id DESC";
$query = $db->query($sql);
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
	<tr>
		<td>ID</td>
		<td>操作人</td>
		<td>组织名称</td>
		<td>操作内容</td>
		<td>IP</td>
		<td>时间</td>	
	</tr>
<?php 
while ($rows = $db->fetch_array($query))
{
	$rows['eiregistername'] = Cache::cache_company($rows['zuzhi_id']);
?>
	<tr>
		<td><?php echo $rows['id'];?></td>
		<td><?php echo $rows['username'];?></td>
		<td><?php echo $rows['eiregistername'];?></td>
		<td><?php echo $rows['info'];?></td>
		<td><?php echo $rows['ip'];?></td> 
		<td><?php echo $rows['logtime'];?></td> 
	</tr>
<?php
}
?>
</table>