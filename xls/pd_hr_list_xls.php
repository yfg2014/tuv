<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=pd_hr_list-".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/Evaluate.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'/include/setup/setup_pd_online.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'core/assess/pd_hr_list_search_arr.php';

Power::CkPower('D1S');
$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$sql_temp	= $TopSearch->SearchSql;
$sql_temp1 = $sql_temp;
if($approvaldate1 != '' && $approvaldate2 != '')
{
	$sql_temp = $sql_temp." AND pdid IN(SELECT id FROM {$dbtable['pd_xm']} WHERE approvaldate>='$approvaldate1' AND approvaldate<='$approvaldate2')";
	$sql_temp1 = $sql_temp1." AND approvaldate>='$approvaldate1' AND approvaldate<='$approvaldate2'";
}
$sql_temp = $sql_temp." AND billingdate!='0000-00-00'";
$sql = "SELECT * FROM pd_evaluation_hr WHERE 1 $sql_temp GROUP BY taskId ORDER BY id DESC";
$query = $db->query($sql);
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
TD{
    font-size: 12px;
    vertical-align: middle;
    vnd.ms-excel.numberformat:@;
}
br{mso-data-placement:same-cell;}
</style>
<table border="1" >
	<tr>
		<td>评定人员</td>
		<td>企业名称</td>
		<td>是否发证</td>
		<td>金额</td>
		<td>评定通过日期</td>
	</tr>
<?php
while($rows = $db->fetch_array($query)){
	$rows['eiregistername'] = Cache::cache_company($rows['zuzhi_id']);
	$rows['mon'] = '30';	
	$hz = array();
	if($rows['taskId'] > 0){
		$query1 = $db->query("SELECT * FROM `{$dbtable['pd_xm']}` WHERE taskId='{$rows['taskId']}' ORDER BY iso ASC");		
		while($dchr = $db->fetch_array($query1)){
			$fazheng = '否';
			if($dchr['ifchangecert'] == '1' || $dchr['audit_type'] == '1001' || $dchr['audit_type'] == '1008' || $dchr['audit_type'] == '1005'){
				$rows['mon'] = '50';
				$fazheng = '是';
			}
			$hz [] = $dchr['iso'].':'.$fazheng;	
			$rows['tongguo'] = $dchr['approvaldate'];	
		}
		
		$rows['fazheng'] = implode(' ',$hz);
	}else{
		$fazheng = '否';
		$dchr = $db->get_one("SELECT * FROM `{$dbtable['pd_xm']}` WHERE id='{$rows['pdid']}' $sql_temp1 ORDER BY id ASC");
		if($dchr['ifchangecert'] == '1' || $dchr['audit_type'] == '1001' || $dchr['audit_type'] == '1008' || $dchr['audit_type'] == '1005'){
			$rows['mon'] = '50';
			$fazheng = '是';
		}
		$rows['fazheng'] = $dchr['iso'].':'.$fazheng;
		$rows['tongguo'] = $dchr['approvaldate'];
	}
	
?>
	<tr>
		<td><?php echo $rows['username']; ?></td>
		<td><?php echo $rows['eiregistername']; ?></td>
		<td><?php echo $rows['fazheng']; ?></td>
		<td><?php echo $rows['mon']; ?></td>
		<td><?php echo $rows['tongguo']; ?></td>
	</tr>
<?php
}
?>
</table>