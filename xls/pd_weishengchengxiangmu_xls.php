<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/AssessmentItem.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'/include/setup/setup_pd_online.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'core/assess/pd_xm_list_search_arr.php';

Power::CkPower('D0S');

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp = $sql_temp.Power::xt_htfrom();
$sql_temp = " and zs_if='1' and (audit_type='1001' or audit_type='1008' or audit_type='1005' or audit_type='1002' or audit_type='1003') ".$sql_temp;

if($taskBeginDate1 != '' && $taskBeginDate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE taskBeginDate>='$taskBeginDate1' AND taskEndDate<='$taskBeginDate2')";
}
if($zl_okdate1 != '' && $zl_okdate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE zl_okdate>='$zl_okdate1' AND zl_okdate<='$zl_okdate2')";
}

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
		<td>企业名称</td>
		<td>审核开始日期</td>
        <td>审核结束日期</td>
		<td>审核类型</td>
		<td>体系</td>
		<td>未生成</td>
	</tr>
<?php
$q_taskId = $db->query("SELECT * FROM `{$dbtable['pd_xm']}` WHERE 1 $sql_temp");
while($rows = $db->fetch_array($q_taskId)){
	$audit_type = '';
	switch($rows['audit_type']){
		case '1001':$audit_type = '1002';break;
		case '1008':$audit_type = '1002';break;
		case '1005':$audit_type = '1002';break;
		case '1002':$audit_type = '1003';break;
		case '1003':if($rows['kind'] == '2'){$audit_type = '1004';}break;
	}
	if($audit_type != ''){
		$xm = $db->get_one("SELECT id FROM `{$dbtable['xm_item']}` WHERE htxm_id='{$rows['htxm_id']}' and audit_type='{$audit_type}' ORDER BY id ASC");
		if($xm['id'] == ''){
			$xm = $db->get_one( "SELECT taskBeginDate,taskEndDate FROM `{$dbtable['xm_item']}` WHERE id='{$rows['xmid']}' ORDER BY id ASC");
			$rows['taskBeginDate'] = $xm['taskBeginDate'];
			$rows['taskEndDate'] = $xm['taskEndDate'];
			$rows['audit_type'] = Cache::cache_audit_type($rows['audit_type']);
			$rows['eiregistername'] = Cache::cache_company($rows['zuzhi_id']);
			$audit_type = Cache::cache_audit_type($audit_type);
?>
	<tr>
		<td><?php echo $rows['eiregistername'];?></td>
		<td><?php echo $rows['taskBeginDate'];?></td>
		<td><?php echo $rows['taskEndDate'];?></td>
		<td><?php echo $rows['audit_type'];?></td>
		<td><?php echo $rows['iso'];?></td>
		<td><?php echo $audit_type;?></td>
	</tr>
<?php
		}
	}
}
?>
