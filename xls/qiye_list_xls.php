<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=qiye_list-".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Company.php');
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'core/company/qiye_list_search_arr.php';

Power::CkPower('A0S');

$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;
$sql_temp .= Power::xt_htfrom();
$sql_temp .= Power::CpPower();

$sql = "SELECT * FROM mk_company WHERE 1 $sql_temp ORDER BY id DESC";
$query = $db->query($sql);
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
	<tr>
	  <td align="center">档案号</td>
	  <td align="center">企业名称</td>
	  <td align="center">地址</td>
	  <td align="center">邮编</td>
	  <td align="center">电话</td>
	  <td align="center">传真</td>
	  <td align="center">法人</td>
	  <td align="center">管代</td>
	  <td align="center">联系人</td>
	  <td align="center">联系电话</td>
	</tr>
<?php
while($result = $db->fetch_array($query))
{
	$result['eilinkman_email'] != '' && $result['eilinkman_email'] = $result['eilinkman_email'].'；';
?>
	<tr>
		<td><?php echo $result[eientercode]; ?></td>
		<td><?php echo $result[eiregistername]; ?></td>
		<td><?php echo $result[eisc_address]; ?></td>
		<td><?php echo $result[eiscpostalcode]; ?></td>
		<td><?php echo $result[eiphone]; ?></td>
		<td><?php echo $result[eifax]; ?></td>
		<td><?php echo $result[eifaren]; ?></td>
		<td><?php echo $result[eiguandai]; ?></td>
		<td><?php echo $result[eilinkman]; ?></td>
		<td><?php echo $result[eilinkman_mob]; ?></td>
	</tr>
<?php
}
?>

</table>
