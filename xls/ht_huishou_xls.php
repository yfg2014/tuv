<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'core/assess/pd_list_search_arr.php';

$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;

if($taskBeginDate1 != '' && $taskBeginDate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE taskBeginDate>='$taskBeginDate1' AND taskEndDate<='$taskBeginDate2')";
}
if($taskEndDate1 != '' && $taskEndDate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE taskEndDate>='$taskEndDate1' AND taskEndDate<='$taskEndDate2')";
}
if($zl_okdate1 != '' && $zl_okdate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE zl_okdate>='$zl_okdate1' AND zl_okdate<='$zl_okdate2')";
}

$sql_temp = " 1 and audit_type!='1007' and (zs_if='0' or zs_if='2')".$sql_temp;
$q_taskId = $db->query("SELECT id,taskId,audit_type,iso,zuzhi_id,xmid FROM `{$dbtable['pd_xm']}` WHERE $sql_temp GROUP BY taskId");
while($rows = $db->fetch_array($q_taskId)){
	if($rows['xmid'] > '0'){
		$xm = $db->get_one( "SELECT taskBeginDate,taskEndDate FROM `{$dbtable['xm_item']}` WHERE id='{$rows['xmid']}' ORDER BY id ASC");
		$rows['taskBeginDate'] = $xm['taskBeginDate'];
		$rows['taskEndDate'] = $xm['taskEndDate'];
	}
	$empName = array();
	if($rows['taskId'] > 0){
		$q_xm_auditor = $db->query("SELECT empName FROM `xm_auditor` WHERE taskId='{$rows[taskId]}' ORDER BY id ASC");		
		while($xm_auditor_arr = $db->fetch_array($q_xm_auditor)){
			$empName []= $xm_auditor_arr['empName'];
		}
	}
	$empName['empName'] = implode(",",array_unique($empName));
	$rows['audit_type'] = Cache::cache_audit_type($rows['audit_type']);
	$rows['eiregistername'] = Cache::cache_company($rows['zuzhi_id']);
	if($rows['audit_type'] == '监一' or $rows['audit_type'] == '监二' or $rows['audit_type'] == '监三'){
		$rows['iso'] = $rows['iso'] == 'P' ? '产品' : '体系' ;
		//证书变更
		$q_change = $db->query("SELECT id,changeitem FROM  `zs_change` WHERE xmid='{$rows[id]}' AND (changeitem='01' OR changeitem LIKE '02%')");
		$change_id = array(); $changeitem = array();
		while($change_rows = $db->fetch_array($q_rzlx)){
			$change_id []= $change_rows['id'];
			$changeitem []= Cache::cache_changeitem($change_rows['changeitem']);
		}		
		//认证类型变更
		$q_rzlx = $db->query("SELECT id,renzhengleixing FROM  `xm_rzlx` WHERE xmid='{$rows[id]}'");
		$rzlx_id = array(); $renzhengleixing = array();
		while($rzlx_rows = $db->fetch_array($q_rzlx)){
			$rzlx_id []= $rzlx_rows['id'];
			$renzhengleixing []= Cache::cache_type_online($rzlx_rows['renzhengleixing']);
		}
		if(!empty($rzlx_id) or !empty($change_id)){
			$arr1 []=array(
			'eiregistername' => $rows['eiregistername'],
			'taskBeginDate' => $rows['taskBeginDate'],
			'taskEndDate' => $rows['taskEndDate'],
			'audit_type' => $rows['audit_type'].implode(",",array_unique($changeitem)).implode(",",array_unique($renzhengleixing)),
			'iso' => $rows['iso'],
			'empName' => $empName['empName']
			); 	
		}else{
			$arr2 []=array(
				'eiregistername' => $rows['eiregistername'],
				'taskBeginDate' => $rows['taskBeginDate'],
				'taskEndDate' => $rows['taskEndDate'],
				'audit_type' => $rows['audit_type'],
				'iso' => $rows['iso'],
				'empName' => $empName['empName']
			); 
		}
	}else{
		if($rows['iso'] == 'P' and $rows['audit_type'] == '二阶段'){$rows['audit_type'] = '初审';}
		$rows['iso'] = $rows['iso'] == 'P' ? '产品' : '体系' ;
		$arr1 []=array(
			'eiregistername' => $rows['eiregistername'],
			'taskBeginDate' => $rows['taskBeginDate'],
			'taskEndDate' => $rows['taskEndDate'],
			'audit_type' => $rows['audit_type'],
			'iso' => $rows['iso'],
			'empName' => $empName['empName']
		); 		
	}
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
<tr bgcolor="#F2F2F2"><td colspan="6">新发或需换证的：</td></tr>
	<tr bgcolor="#F2F2F2">
		<td>企业名称</td>
		<td>审核开始日期</td>
        <td>审核结束日期</td>
		<td>审核类别</td>
		<td>体系/产品</td>
		<td>审核员</td>
	</tr>
<?php
foreach($arr1 AS $v){
?>
	<tr>
		<td><?php echo $v['eiregistername'];?></td>
		<td><?php echo $v['taskBeginDate'];?></td>
		<td><?php echo $v['taskEndDate'];?></td>
		<td><?php echo $v['audit_type'];?></td>
		<td><?php echo $v['iso'];?></td>
		<td><?php echo $v['empName'];?></td>
	</tr>
<?php
}
?>
<tr bgcolor="#F2F2F2"><td colspan="6">&nbsp;</td></tr>
<tr bgcolor="#F2F2F2"><td colspan="6">监督保持的：</td></tr>
<?php
foreach($arr2 AS $v){
?>
	<tr>
		<td><?php echo $v['eiregistername'];?></td>
		<td><?php echo $v['taskBeginDate'];?></td>
		<td><?php echo $v['taskEndDate'];?></td>
		<td><?php echo $v['audit_type'];?></td>
		<td><?php echo $v['iso'];?></td>
		<td><?php echo $v['empName'];?></td>
	</tr>
<?php
}
?>
</table>