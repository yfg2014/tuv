<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=contractitem-".date("Y-m-d").".xls");
header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include S_DIR.'include/module/Contract.php';
include S_DIR.'include/module/ContractItem.php';
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_audit_type.php';
include SET_DIR.'setup_audit_iso.php';
include SET_DIR.'setup_htfrom.php';
include SET_DIR.'setup_audit_online.php';
include SET_DIR.'setup_audit_ver.php';
include SET_DIR.'setup_ht_online.php';


Power::CkPower('B1S');
$seach_arr=array(
	 'eiregistername'=>array(
	  'kind'=>'company',
	  'name'=>'eiregistername',
	  'msg'=>'组织名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	  'zd_ren'=>array(
		'kind'=>'zd_ren',
		'name'=>'zd_ren',
		'msg'=>'受 理 人',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'ht_id',
		'sql_kind'=>'%like%'
	  ),
	  'htfrom'=>array(
	  'kind'=>'select',
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'100px',
	  'arr'=>$setup_htfrom ,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	 'htdate1'=>array(
		'kind'=>'htdate1',
		'name'=>'htdate1',
		'msg'=>'受理日期',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'ht_id',
		'sql_kind'=>'>='
	 ),
	'htdate2'=>array(
		'kind'=>'htdate2',
		'name'=>'htdate2',
		'msg'=>'受理日期',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'ht_id',
		'sql_kind'=>'<='
	 ),
	 'online'=>array(
		'kind'=>'select',
		'name'=>'online',
		'msg'=>'状态',
		'width'=>'100px',
		'arr'=>$cache_ht_online,
		'sql_field'=>'online',
		'sql_kind'=>'='
	  ),
	 'br'=>array(
		'kind'=>'br',
		'name'=>'',
		'msg'=>'',
		'width'=>'',
		'arr'=>'',
		'sql_field'=>'',
		'sql_kind'=>''
	 ),
	 'htcode'=>array(
		'kind'=>'htcode',
		'name'=>'htcode',
		'msg'=>'合同编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'ht_id',
		'sql_kind'=>'='
	  ),
	 'htxmcode'=>array(
		'kind'=>'text',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'htxmcode',
		'sql_kind'=>'%like%'
	  ),

	'audit_code'=>array(
		'kind'=>'text',
		'name'=>'audit_code',
		'msg'=>'专业代码',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'audit_code ',
		'sql_kind'=>'like% '
	  ),
	  'audit_type'=>array(
		'kind'=>'select',
		'name'=>'audit_type',
		'msg'=>'审核类型',
		'width'=>'100px',
		'arr'=>$setup_audit_type,
		'sql_field'=>'audit_type',
		'sql_kind'=>'='
	),
	 'iso'=>array(
		'kind'=>'select',
		'name'=>'iso',
		'msg'=>'体系',
		'width'=>'100px',
		'arr'=>$setup_audit_iso,
		'sql_field'=>'iso',
		'sql_kind'=>'='
	),
);
$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$sql_temp	= $TopSearch->SearchSql;
$sql_temp .= Power::xt_htfrom();

$sql = "SELECT * FROM {$dbtable['ht_contract_item']} WHERE 1  $sql_temp ORDER BY id DESC";
$rows = $db->query($sql);

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
		<th align="center">序号</th>
		<th align="center">认证类型</th>
		<th align="center">企业名称</th>
		<th align="center">人数</th>
		<th align="center">合同编号</th>
		<th align="center">项目编号</th>
		<th align="center">合同金额</th>
		<th align="center">备注</th>
        </tr>
<?php
while($result = $db->fetch_array($rows))
{
     	$ht = $db->get_one("SELECT htcode FROM `ht_contract` where id='{$result['ht_id']}'");
		$result['htcode'] = $ht['htcode'];

		$ht = $db->get_one("SELECT * FROM ht_contract where id='{$result['ht_id']}'");
		$result['other'] = $ht['other'];
		
		$cm = $db->get_one("SELECT eiregistername,eiman_amount FROM mk_company  where id='{$result['zuzhi_id']}'");
		$result['eiregistername'] = $cm['eiregistername'];
		$result['eiman_amount'] = $cm['eiman_amount'];

	    $cw = $db->query("SELECT *  FROM `cw_finance_item` where  ht_id ='{$result['ht_id']}'");
		$contract_money='';
		$count_money ='';
		while($rows_cw = $db->fetch_array($cw))
		{
		    $contract_money .='收费项目:'.Cache::cache_Finance_item($rows_cw['finance_item']).'金额:'. $rows_cw['contract_money'].'<br/>';
			$count_money +=$rows_cw['contract_money'];
		}
		if($count_money>0)
		{
		   $contract_money .='合计：'.$count_money ;
		}

		$result['htfrom'] = Cache::cache_htfrom($result['htfrom']);
		$result['audit_type'] = Cache::cache_audit_type($result['audit_type']);
		if($result['audit_type'] == '二阶段'){$result['audit_type'] = '初审';}

?>
        <tr bgcolor="#FFFFFF">
		<td  align="center"><?php echo $result['id']?></td>
        <td >&nbsp;<?php echo $result['audit_type']?></td>
		<td  align="center"><?php echo $result['eiregistername']?></td>
        <td  align="center"><?php echo $result['eiman_amount']?></td>
		<td  align="center"><?php echo $result['htcode']?></td>
		<td  align="center"><?php echo $result['htxmcode']?></td>
        <td  align="center"><?php echo $contract_money ?></td>
		<td >&nbsp;<?php echo $result['other']?></td>
        </tr>
<?php
}
?>

</table>