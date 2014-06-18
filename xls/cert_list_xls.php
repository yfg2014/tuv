<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=cert_list-".date("Y-m-d").".xls");

header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Certificate.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_organize_information.php';
include_once S_DIR.'include/setup/setup_certificate_online.php';
include(S_DIR.'core/certificate/cert_list_search_arr.php');
Power::CkPower('E0S');

$width = '1100px';

$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL

if($product != '')
{
	$sql_temp = $sql_temp." AND  coverFields LIKE '%{$product}%'";
}
$sql_temp = $sql_temp." AND zsprintdate!='0000-00-00'";

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
		<th align="center">档案编号</th>
		<th align="center">企业名称</th>
		<th align="center">企业简称</th>
		<th align="center">企业地址</th>
		<th align="center">编号</th>
		<th align="center">联系人电话</th>
		<th align="center">企业传真</th>
		<th align="center">企业性质</th>
		<th align="center">地区</th>
		<th align="center">法人</th>
		<th align="center">管代</th>
		<th align="center">联系人</th>
		<th align="center">企业人数</th>
		<th align="center">注册地址</th>
		<th align="center">注册地址邮编</th>
		<th align="center">审核标准</th>
		<th align="center">易记码</th>
		<th align="center">产品证书编号</th>
		<th align="center">产品证书日期</th>
		<th align="center">认证产品名称</th>
		<th align="center">体系证书编号</th>
		<th align="center">体系证书日期</th>
		<th align="center">体系覆盖范围</th>
		<th align="center">备注</th>
        </tr>
<?php
$q_zuzhi_id = $db->query("SELECT zuzhi_id FROM zs_cert  WHERE 1 $sql_temp GROUP BY zuzhi_id");
while($zuzhi_id_rows = $db->fetch_array($q_zuzhi_id)){
	$company = $db->get_one("SELECT * FROM mk_company WHERE id='{$zuzhi_id_rows['zuzhi_id']}'");
	foreach($setup_organize_information AS $v){
		if($company['eikind'] == $v['code']){
			$company['eikind'] = $v['msg'];
			break;
		}
	}
	$q_cert = $db->query("SELECT * FROM zs_cert WHERE zuzhi_id='{$zuzhi_id_rows['zuzhi_id']}'");
	$result['certNo'] = array();$result['certNo_p'] = array();
	$result['certStart']= array();$result['certStart_p']= array();
	$result['coverFields']= array();
	$result['audit_ver']= array();
	$result['product']= array();
	$result['other'] = array();
	while($cert_rows = $db->fetch_array($q_cert))
	{
		if($cert_rows['kind'] == '2'){
			$cert_rows['certNo'] != '' && $result['certNo_p'] []= $cert_rows['certNo'];
			$cert_rows['certStart'] != '' && $result['certStart_p'] []= $cert_rows['certStart'];
			$cert_rows['coverFields'] != '' && $result['product'] []= $cert_rows['coverFields'];
			$cert_rows['product_ver'] != '' && $result['audit_ver'] []= Cache::cache_product_ver($cert_rows['product_ver']);
		}else{
			$cert_rows['certNo'] != '' && $result['certNo'] []= $cert_rows['certNo'];
			$cert_rows['certStart'] != '' && $result['certStart'] []= $cert_rows['certStart'];
			$cert_rows['coverFields'] != '' && $result['coverFields'] []= $cert_rows['coverFields'];
			$cert_rows['audit_ver'] != '' && $result['audit_ver'] []= Cache::cache_audit_ver($cert_rows['audit_ver']);
		}
		$cert_rows['certNo'] != '' && $result['other'] []= $cert_rows['other'];
	}
	$result['certNo'] = implode("；",array_unique($result['certNo']));
	$result['certNo_p'] = implode("；",array_unique($result['certNo_p']));
	$result['other'] = implode("；",array_unique($result['other']));
	$result['certStart'] = implode("；",array_unique($result['certStart']));
	$result['certStart_p'] = implode("；",array_unique($result['certStart_p']));
	$result['coverFields'] = implode("；",array_unique($result['coverFields']));
	$result['audit_ver'] = implode("；",array_unique($result['audit_ver']));
	$result['product'] = implode("；",array_unique($result['product']));
?>
      <tr bgcolor="#FFFFFF">
        <td><?php echo $company['eientercode']?></td>
        <td><?php echo $company['eiregistername']?></td>
		<td><?php echo $company['eialias']?></td>
        <td><?php echo $company['eisc_address']?></td>
        <td><?php echo $company['eiscpostalcode']?></td>
        <td><?php echo $company['eilinkman_mob']?></td>
        <td><?php echo $company['eifax']?></td>
		<td><?php echo $company['eikind']?></td>
        <td><?php echo $company['eiaddress']?></td>
        <td><?php echo $company['eifaren']?></td>
        <td><?php echo $company['eiguandai']?></td>
        <td><?php echo $company['eilinkman']?></td>
		<td><?php echo $company['eiman_amount']?></td>
        <td><?php echo $company['eireg_address']?></td>
        <td><?php echo $company['eiregpostalcode']?></td>
        <td><?php echo $result['audit_ver']?></td>
        <td><?php echo $company['eimark']?></td>
		<td><?php echo $result['certNo_p']?></td>
        <td><?php echo $result['certStart_p']?></td>
        <td><?php echo $result['product']?></td>
        <td><?php echo $result['certNo']?></td>
        <td><?php echo $result['certStart']?></td>
		<td><?php echo $result['coverFields']?></td>
        <td><?php echo $result['other']?></td>
     </tr>
<?php
}
?>

</table>