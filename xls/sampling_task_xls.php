<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=sampling-".date("Y-m-d").".xls");

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

$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;
$sql_temp .= Power::xt_htfrom();
if($svonline == '')
{
	$sql_temp = " AND a.online='0'".$sql_temp;
}else{
	$sql_temp = " AND a.online!='0'".$sql_temp;
}
$cp = $product = array();
$cp['one']['info'] = '产品类别/周期（天）';
$cp['a']['info'] = '1-7';
$cp['b']['info'] = '8-13';
$cp['c']['info'] = '14-21';
$cp['d']['info'] = '22-28';
$cp['e']['info'] = '29-35';
$cp['f']['info'] = '36-42';
$cp['g']['info'] = '43-49';
$cp['h']['info'] = '50-90';

$product[0] = 'info';
$sql = "SELECT  a.*,b.* FROM {$dbtable['ht_sampling']} a LEFT JOIN {$dbtable['ht_contract_item']} b ON a.htxm_id=b.id WHERE b.product!='' $sql_temp ORDER BY a.id DESC";
$query = $db->query($sql);
while($rows = $db->fetch_array($query))
{
	$product [$rows['product']]= $rows['product'];
	$cp['one'][$rows['product']] = $rows['product'];
	if($rows['tianshu'] >= '1' && $rows['tianshu'] <= '7')
	{
		$cp['a'][$rows['product']]++;
	}elseif($rows['tianshu'] >= '8' && $rows['tianshu'] <= '13')
	{
		$cp['b'][$rows['product']]++;
	}elseif($rows['tianshu'] >= '14' && $rows['tianshu'] <= '21')
	{
		$cp['c'][$rows['product']]++;
	}elseif($rows['tianshu'] >= '22' && $rows['tianshu'] <= '28')
	{
		$cp['d'][$rows['product']]++;
	}elseif($rows['tianshu'] >= '29' && $rows['tianshu'] <= '35')
	{
		$cp['e'][$rows['product']]++;
	}elseif($rows['tianshu'] >= '36' && $rows['tianshu'] <= '42')
	{
		$cp['f'][$rows['product']]++;
	}elseif($rows['tianshu'] >= '43' && $rows['tianshu'] <= '49')
	{
		$cp['g'][$rows['product']]++;
	}elseif($rows['tianshu'] >= '50' && $rows['tianshu'] <= '90')
	{
		$cp['h'][$rows['product']]++;
	}
	
}
$product_test = Cache::cache_product_test($product_test);
if($product_test == '')
{
	$product_test = '无';
}
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
<tr bgcolor="#FFFFFF">
<?php
$i = 0;
foreach($cp as $k=>$v){
?>
	<tr>
<?php
$i = 0;
foreach($product as $vv){
	$i++;
	if($k == 'one'){
		$v[$vv] = Cache::cache_product($v[$vv]);
	}	
?>
		<td><?php echo $v[$vv];?></td>
<?php
}
?>
	</tr>
<?php
}
$i = $i - 1;
?>	<tr>
		<td>检测机构</td>
		<td colspan="<?php echo $i;?>"><?php echo $product_test;?></td>
	</tr>
</table>