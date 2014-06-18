<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=prove_list-".date("Y-m-d").".xls");

header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_certificate_online.php';
include(S_DIR.'include/topsearch.php');
Power::CkPower('Z3S');
$seach_arr = array(
'htxmcode'=>array(
		'kind'=>'htxmcode',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'a.htxm_id',
		'sql_kind'=>'in'
	  ),
	'eiregistername'=>array(
	  'kind'=>'company',
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'a.zuzhi_id',
	  'sql_kind'=>'in'
	),

	  'htcode'=>array(
	  'kind'=>'htcode',
	  'name'=>'htcode',
	  'msg'=>'合同编号',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'a.ht_id',
	  'sql_kind'=>'='
	),

	'audit_type'=>array(
	  'kind'=>'select',
	  'name'=>'audit_type',
	  'msg'=>'审核类型',
	  'width'=>'70px',
	  'arr'=>$setup_audit_type,
	  'sql_field'=>'a.audit_type',
	  'sql_kind'=>'='
	),
	'assessmentdate1'=>array(
	  'kind'=>'date1',
	  'name'=>'assessmentdate1',
	  'msg'=>'评定日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'a.assessmentdate',
	  'sql_kind'=>'>='
	),
	  'assessmentdate2'=>array(
	  'kind'=>'date2',
	  'name'=>'assessmentdate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'a.assessmentdate',
	  'sql_kind'=>'<='
	),
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'认证体系',
	  'width'=>'70px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'a.iso',
	  'sql_kind'=>'='
	),
	'br1'=>array(
	'kind'=>'br'
	),
	'certNo'=>array(
	  'kind'=>'text',
	  'name'=>'certNo',
	  'msg'=>'证书编号',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'a.certNo',
	  'sql_kind'=>'='
	),
	'coverFields'=>array(
	  'kind'=>'text',
	  'name'=>'coverFields',
	  'msg'=>'证书范围',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'a.coverFields',
	  'sql_kind'=>'%like%'
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'70px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'a.htfrom',
	  'sql_kind'=>'='
	),
	  'maildate1'=>array(
	  'kind'=>'date1',
	  'name'=>'maildate1',
	  'msg'=>'邮寄日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'a.maildate',
	  'sql_kind'=>'>='
	),
	  'maildate2'=>array(
	  'kind'=>'date2',
	  'name'=>'a.maildate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'a.maildate',
	  'sql_kind'=>'<='
	),
	 'ifpost'=>array(
	  'kind'=>'hidden',
	  'name'=>'a.ifpost'
	)
);

$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL

$sql_temp = $sql_temp." AND a.zs_if='1' AND (a.audit_type='1002' OR a.audit_type='1003' OR a.audit_type='1004') AND a.ifchangecert='0'";
$sql = "SELECT * FROM xm_item  a  WHERE 1 $sql_temp ORDER BY id DESC";
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
		<th align="center">组织名称</th>
		<th align="center">合同来源</th>
		<th align="center">合同编号</th>
		<th align="center">项目编号</th>
		<th align="center">合同包含的体系</th>
		<th align="center">合同金额</th>
		<th align="center">标准版本</th>
		<th align="center">审核类型</th>
		<th align="center">是否转机构</th>
		<th align="center">证书编号</th>
		<th align="center">证书注册日期</th>
		<th align="center">证书注册到期</th>
		<th align="center">原证书号</th>
		<th align="center">初次获证日期</th>
		<th align="center">换证日期</th>
		<th align="center">邮寄日期</th>
		<th align="center">邮寄备注</th>
		<th align="center">变更信息</th>
        </tr>
<?php
while($result = $db->fetch_array($rows))
{
        $contract_money='';
	    $count_mone='';
		$change_info='';
		$rows_cg = array();
		
		$result['eiregistername'] = Cache::cache_company($result['zuzhi_id']);
		$zs = $db->get_one("SELECT * FROM `zs_cert` where id='{$result['zsid']}'");
		$result['certNo'] = $zs['certNo'];
		$result['certStart'] = $zs['certStart'];
		$result['certEnd'] = $zs['certEnd'];
		$result['certNo_y'] = $zs['certNo_y'];
		$result['firstDate'] = $zs['firstDate'];
		$result['renewaldate'] = $zs['renewaldate'];
		
        $htx = $db->get_one("SELECT * FROM `ht_contract_item` where id='{$result['htxm_id']}' and ht_id='{$result['ht_id']}'");
		$result['zjg'] = $htx['zjg'];
		$result['htxmcode']=$htx['htxmcode'];

		if($htx['zjg'] =='0'){
		  $result['zjg']='否';
		}else{$result['zjg']='是';}

		$ht = $db->get_one("SELECT * FROM ht_contract where id='{$result['ht_id']}'");
		$result['htcode'] = $ht['htcode'];

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

		$cg = $db->query("SELECT * FROM zs_change where zsid ='{$result['zsid']}'");
        $change_info='';
		while($rows_cg = $db->fetch_array($cg) )
		{
		   $change_info .= '*** 变更类型：'.Cache::cache_changeitem($rows_cg['changeitem']).',   变更原因：'.$rows_cg['changereason'].',  证书暂停时间：'.$rows_cg['zs_change_date'].',  暂定到期时间：'.$rows_cg['zs_zanting_edate'].'<br />';
		}

		$result['htfrom'] = Cache::cache_htfrom($result['htfrom']);
		$result['audit_type'] = Cache::cache_audit_type($result['audit_type']);
		if($result['audit_type'] == '二阶段'){$result['audit_type'] = '初审';}

?>
        <tr bgcolor="#FFFFFF">
		    	<td  align="center"><?php echo $result['eiregistername']?></td>
        <td >&nbsp;<?php echo $result['htfrom']?></td>
		<td  align="center"><?php echo $result['htcode']?></td>
		  <td  align="center"><?php echo $result['htxmcode']?></td>
        <td  align="center"><?php echo $result['iso']?></td>
        <td  align="center"><?php echo $contract_money ?></td>
		<td  align="center"><?php echo $result['audit_ver']?></td>
        <td  align="center"><?php echo $result['audit_type']?></td>
        <td  align="center"><?php echo $result['zjg']?></td>
		     <td  align="center"><?php echo $result['certNo']?></td>
        <td  align="center"><?php echo $result['certStart']?></td>
        <td  align="center"><?php  echo $result['certEnd']?></td>
        <td >&nbsp;<?php echo $result['certNo_y']?></td>
        <td ><?php echo $result['firstDate']?></td>
        <td >&nbsp;<?php echo $result['renewaldate']?></td>
		<td >&nbsp;<?php echo $result['maildate']?></td>
		<td >&nbsp;<?php echo $result['mailother']?></td>
		<td >&nbsp;<?php echo $change_info?></td>
        </tr>
<?php
}
?>

</table>