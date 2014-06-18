<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=qiye_list-".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include SET_DIR.'setup_province.php';
include_once S_DIR.'core/audit/xm_maintain_list_arr.php';

Power::CkPower('C8S');//监督维护查询

$width='1400px';
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$sql_temp  .= Power::xt_htfrom('0','1','');

//到期提醒
if($ymdate != ''){
	$ymdate_arr = explode("-",$ymdate);
	$tianshu=date("t",mktime(0,0,0,$ymdate_arr['1'],01,$ymdate_arr['0']));//获得当月天数
	$sql_temp .= "and finalItemDate >= '{$ymdate}-01' and finalItemDate <= '{$ymdate}-{$tianshu}'";
}

if($eireg_address != ''){
	$sql_temp = $sql_temp."  and zuzhi_id IN(SELECT id FROM (SELECT id FROM mk_company WHERE eireg_address LIKE'%$eireg_address%') AS t)";
}
if($online == ''){
	$sql_temp = "  and online='5' and (audit_type='1002' or audit_type='1003' or  audit_type='1004')".$sql_temp;
}elseif($online == 'sv'){
	$sql_temp = "  and online!='5' and (audit_type='1002' or audit_type='1003' or  audit_type='1004')".$sql_temp;
}

$params = array(
	'search' => $sql_temp,
);
$db_perpage = '10000';
$Item = new Item();
$result = $Item->listElementSv($params);

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
	  <td align="center">项目编号</td>
	  <td align="center">企业名称</td>
	  <td align="center">审核类型</td>
	  <td align="center">监审最后期限</td>
	  <td align="center">证书到期</td>
	  <td align="center">证书编号</td>
	  <td align="center">产品/范围</td>
	  <td align="center">计划开始</td>
	  <td align="center">合同来源</td>
	  <td align="center">省份</td>
	  <td align="center">标准</td>
	  <td align="center">证书状态</td>
	  <td align="center">专业代码</td>
	  <td align="center">回访记录</td>
	  <td align="center">维护时间</td>
	</tr>
<?php
foreach($result['data'] AS $v)
{
	$v['wh_date'] == '0000-00-00' && $v['wh_date'] = '';
?>
	<tr>
		<td><?php echo $v[htxmcode]; ?></td>
		<td><?php echo $v[eiregistername]; ?></td>
		<td><?php echo $v[audit_type]; ?></td>
		<td><?php echo $v[finalItemDate]; ?></td>
		<td><?php echo $v[certEnd]; ?></td>
		<td><?php echo $v[certNo]; ?></td>
		<td><?php echo $v[renzhengfanwei]; ?></td>
		<td><?php echo $v[auditplandate]; ?></td>
		<td><?php echo $v[htfrom]; ?></td>
		<td><?php echo $v[eiarea]; ?></td>
		<td><?php echo $v[audit_ver]; ?></td>
		<td><?php echo $v[zsonline]; ?></td>
		<td><?php echo $v[audit_code]; ?></td>
		<td><?php echo $v[sv_other]; ?></td>
		<td><?php echo $v[wh_date]; ?></td>
	</tr>
<?php
}
?>

</table>
