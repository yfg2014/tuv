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
$sql_temp .= Power::xt_htfrom();
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
          <td>序号</td>
		  <td>企业名称</td>
          <td>客户编号</td>
          <td>检验机构名称</td>
		  <td>接收报告日期/移交日期</td>
		  <td>检测机构接到样品日期</td>
		  <td>检验完成日期</td>
		  <td>产品名称/认证单元</td>
          <td>抽样规格</td>
		  <td>检验报告编号</td>
          <td>检验结果准确性</td>
          <td>问题</td>

        </tr>
<?php


$sql_ht_sampling = "SELECT  a.*,b.product,b.product_test
					 		FROM {$dbtable['ht_sampling']} a LEFT JOIN {$dbtable['ht_contract_item']} b ON a.htxm_id=b.id
								WHERE 1  $sql_temp ORDER BY a.id DESC";
$quer_ht_sampling = $db->query($sql_ht_sampling);
while($sampling_arr = $db->fetch_array($quer_ht_sampling))
{
	$i++;
	$qy = $db->get_one("SELECT eientercode,eiregistername FROM mk_company WHERE  id='$sampling_arr[zuzhi_id]'");
	$sampling_arr['samplestrue'] == '1' ? $sampling_arr['samplestrue'] = '是' : $sampling_arr['samplestrue'] = '否';
?>
        <tr bgcolor="#FFFFFF">
          <td height="20"><?php echo $i; ?></td>
		  <td><?php echo $qy['eiregistername']; ?></td>
		  <td><?php echo $qy['eientercode']; ?></td>
		  <td><?php echo Cache::cache_product_test($sampling_arr['product_test']); ?></td>
          <td><?php echo $sampling_arr['receivereportdate']; ?></td>
          <td><?php echo $sampling_arr['samplesreceivedate']; ?></td>
          <td><?php echo $sampling_arr['testingcompletedate']; ?></td>
		  <td><?php echo Cache::cache_product($sampling_arr['product']); ?></td>
          <td><?php echo $sampling_arr['specification']; ?></td>
          <td><?php echo $sampling_arr['testreportcode']; ?></td>
          <td><?php echo $sampling_arr['samplestrue']; ?></td>
          <td><?php echo $sampling_arr['other']; ?></td>
		  <td> </td>
          <td> </td>
        </tr>
<?php
}
?>

</table>