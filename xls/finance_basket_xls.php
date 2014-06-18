<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'core/finance/details_finance_list_search_arr.php';
$width = '900px';
$export = $_GET['export'];
if($export == '1'){
	Power::CkPower('G2X');
}else{
	Power::CkPower('Z3S');
}
$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;

$sql_temp .= Power::xt_htfrom();

if($htxmcode != ''){
	$sql_temp = "  and ht_id IN(SELECT ht_id FROM (SELECT ht_id FROM ht_contract_item WHERE htxmcode LIKE'%$htxmcode%') AS t)".$sql_temp;
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
	<tr bgcolor="#F2F2F2" align="center">
		<td>合同号</td>
		<td>合同日</td>
		<td>企业名称</td>
		<td>申请项目</td>
		<td>人数</td>
		<td>风险</td>
		<td>部门</td>
		<td>推荐人</td>
		<td>审核日期</td>
		<td>审核性质</td>
		<td>合同金额</td>
		<td>到款总额</td>
		<td>到款金额</td>
		<td>到款日期</td>
		<td>结算价</td>
		<td>净收入</td>
		<td>审核补助</td>
		<td>部门结余</td>
		<td>项目费</td>
		<td>净收入</td>
		<td>审核补助</td>
		<td>部门结余</td>
		<td>项目费</td>
		<td>费用说明</td>
		<td>最后修改时间</td>
		<td>上次导出时间</td>
	</tr>
<?php
$sql = "SELECT * FROM cw_finance_list WHERE 1 $sql_temp ORDER BY id DESC";
$query = $db->query($sql);
while($v = $db->fetch_array($query))
{
	if($export == '1'){
		$db->query("UPDATE cw_finance_list SET export_time=now() WHERE id='$v[id]'");
		$db->query("UPDATE cw_finance_list_ex SET export_time=now() WHERE cwid='$v[id]'");
	}
	$ex = $db->get_one("SELECT xmid FROM `{$dbtable['cw_finance_list_ex']}` WHERE cwid='{$v['id']}'");

	$com = $db->get_one("SELECT * FROM `{$dbtable['mk_company']}` WHERE id='{$v['zuzhi_id']}'");
	$v['eiregistername'] = $com['eiregistername'];
	$v['htfrom'] = Cache::cache_htfrom($com['htfrom']);
	$v['recommended_man'] = $com['recommended_man'];

	$ht = $db->get_one("SELECT htcode,htdate,auditplandate FROM `{$dbtable['ht_contract']}` WHERE id='{$v['ht_id']}'");

	$htxm = $db->get_one("SELECT audit_ver,iso_people_num,risk,audit_type FROM `{$dbtable['ht_contract_item']}` WHERE id=(SELECT htxm_id FROM `{$dbtable['xm_item']}` WHERE id='{$ex['xmid']}')");
	$htxm['audit_type'] = Cache::cache_audit_type($htxm['audit_type']);

	$bk = $db->get_one("SELECT * FROM `{$dbtable['cw_finance_basket']}` WHERE f_item_id='{$v['f_item_id']}' ORDER BY id DESC");
	$remark_arr = array();$remark = '';
	$all_bk_q = $db->query("SELECT remark FROM {$dbtable['cw_finance_basket']} WHERE f_item_id='{$v['f_item_id']}' AND zd_time>'$v[export_time]'");
	while($all_bk = $db->fetch_array($all_bk_q)){
		$remark_arr []=  $all_bk['remark'];
	}
	$remark = implode('；',$remark_arr);
	//cw_finance_basket 数据为空，所以暂时查不出 cw_finance_item表的数据
	$f_item = $db->get_one("SELECT * FROM `{$dbtable['cw_finance_item']}` WHERE id='{$v['f_item_id']}'");
	$v[export_time] == '0000-00-00 00:00:00' && $v[export_time] = '';
?>
	<tr>
		<td><?php echo $ht[htcode]; ?></td>
		<td><?php echo $ht[htdate]; ?></td>
		<td><?php echo $v[eiregistername]; ?></td>
		<td><?php echo $htxm[audit_ver]; ?></td>
		<td><?php echo $htxm[iso_people_num]; ?></td>
		<td><?php echo $htxm[risk]; ?></td>
		<td><?php echo $v[htfrom]; ?></td>
		<td><?php echo $v[recommended_man]; ?></td>
		<td><?php echo $ht[auditplandate]; ?></td>
		<td><?php echo $htxm[audit_type]; ?></td>
		<td><?php echo$f_item[contract_money]; ?></td>
		<td><?php echo$v[feespaid]; ?></td>
		<td><?php echo $v[feespaid]; ?></td>
		<td><?php echo $v[costtime]; ?></td>
		<td></td>
		<td><?php echo $f_item[basket1]; ?></td>
		<td><?php echo $f_item[basket2]; ?></td>
		<td><?php echo $f_item[basket3]; ?></td>
		<td><?php echo $f_item[basket4]; ?></td>
		<td><?php echo $bk[basket1]; ?></td>
		<td><?php echo $bk[basket2]; ?></td>
		<td><?php echo $bk[basket3]; ?></td>
		<td><?php echo $bk[basket4]; ?></td>
		<td><?php echo $remark; ?></td>
		<td><?php echo $bk[zd_time]; ?></td>
		<td><?php echo $v[export_time]; ?></td>
	</tr>
<?php
}
?>
</table>

