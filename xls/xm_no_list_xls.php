<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=pd_jianshen-".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_item_online.php';
include_once S_DIR.'core/audit/xm_no_list_search_arr.php';

Power::CkPower('C0S');

$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;
$sql_temp .= Power::xt_htfrom();
$sql_temp .= " AND online='0'";
if($product!='')
{
	$sql_temp = $sql_temp." AND product in( SELECT code FROM setup_product WHERE msg LIKE '%$product%')";
}

$sql = "SELECT * FROM `xm_item` WHERE 1 $sql_temp ORDER BY id DESC";
$query = $db->query($sql);
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
td{
    font-size: 12px;
    vertical-align: middle;
    vnd.ms-excel.numberformat:@;
}
br{mso-data-placement:same-cell;}
</style>
<table border="1">
	<tr>
		<td align="center">档案号</td>
		<td align="center">企业名称</td>
		<td align="center">人数</td>
		<td align="center">审核范围</td>
		<td align="center">审核类型</td>
		<td align="center">上次审核时间</td>
		<td align="center">证书到期时间</td>
		<td align="center">企业要求时间、联系记录</td>
		<td align="center">审核时间</td>
		<td align="center">审核员</td>
		<td align="center">任务单号</td>
    </tr>
<?php
while($rows = $db->fetch_array($query))
{
	$com = $db->get_one("SELECT eiregistername,eientercode FROM mk_company where id='{$rows['zuzhi_id']}'");
	$rows['eiregistername'] = $com['eiregistername'];
	$rows['eientercode'] = $com['eientercode'];
	
	$htxm = $db->get_one("SELECT iso_people_num FROM `ht_contract_item` where id='{$rows['htxm_id']}'");
	$rows['iso_people_num'] = $htxm['iso_people_num'];
	$rows['audit_type'] = Cache::cache_audit_type($rows['audit_type']);	
	
	$xm = $db->get_one("SELECT * FROM `xm_item` where htxm_id='{$rows['htxm_id']}' and taskEndDate!='0000-00-00' and id!='{$rows['id']}' order by id desc");
	$rows['taskDate'] = $xm['taskBeginDate'].' '.$xm['taskEndDate'];
	
	if($rows['zsid'] > 0){
		$zs = $db->get_one("SELECT certEnd FROM zs_cert where id='{$rows['zsid']}'");
		$rows['certEnd'] = $zs['certEnd'];
	}
?>
	<tr>
		<td align="center"><?php echo $rows['eientercode'];?> </td>
		<td align="center"><?php echo $rows['eiregistername'];?> </td>
		<td align="center"><?php echo $rows['iso_people_num'];?> </td>
		<td align="center"><?php echo $rows['renzhengfanwei'];?> </td>
		<td align="center"><?php echo $rows['audit_type'];?> </td>
		<td align="center"><?php echo $rows['taskDate'];?> </td>
		<td align="center"><?php echo $rows['certEnd'];?> </td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center"></td>
    </tr>
<?php
}
?>	
