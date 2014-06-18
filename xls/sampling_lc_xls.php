<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=sl-".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';
include S_DIR.'include/module/Sampling.php';
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_audit_type.php';
include SET_DIR.'setup_audit_iso.php';
include SET_DIR.'setup_htfrom.php';
include SET_DIR.'setup_audit_online.php';
include SET_DIR.'setup_audit_ver.php';
include SET_DIR.'setup_ht_online.php';
include(S_DIR.'core/contract/contract_sampling_seach_arr.php');

$width='1000px';
Power::CkPower('B3S');

$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;
$SearchHtml	= $TopSearch->SearchHtml;
if($svonline == '')
{
	$sql_temp = " AND a.online='0'".$sql_temp;
}else{
	$sql_temp = " AND a.online!='0'".$sql_temp;
}
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
TD{
    font-size: 12px;
    vertical-align: middle;
vnd.ms-excel.numberformat:@;
}
</style>
<table border="1">
        <tr bgcolor="#F2F2F2">
		  <td>企业名称</td>
		  <td>认证单元</td>
          <td>规格型号</td>
		  <td>检验报告编号</td>
        </tr>
<?php


$sql_ht_sampling = "SELECT  a.*,b.product,b.product_test
					 		FROM {$dbtable['ht_sampling']} a LEFT JOIN {$dbtable['ht_contract_item']} b ON a.htxm_id=b.id
								WHERE 1  $sql_temp ORDER BY a.zuzhi_id DESC,b.product DESC";
$quer_ht_sampling = $db->query($sql_ht_sampling);
while($sampling_arr = $db->fetch_array($quer_ht_sampling))
{
	$i++;
	$qy = $db->get_one("SELECT eientercode,eiregistername FROM mk_company WHERE  id='$sampling_arr[zuzhi_id]'");
	$sampling_arr['samplestrue'] == '1' ? $sampling_arr['samplestrue'] = '是' : $sampling_arr['samplestrue'] = '否';
?>
        <tr bgcolor="#FFFFFF">
		  <td><?php echo $qy['eiregistername']; ?></td>
		  <td><?php echo Cache::cache_product($sampling_arr['product']); ?></td>
          <td><?php echo $sampling_arr['specification']; ?></td>
          <td><?php echo $sampling_arr['testreportcode']; ?></td>
        </tr>
<?php
}
?>

</table>