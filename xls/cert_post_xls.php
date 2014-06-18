<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=cert_mail-".date("Y-m-d").".xls");

header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Certificate.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_certificate_online.php';
Power::CkPower('Z3S');

$seach_arr = array(
	//每个表单控件NAME值作为KEY
	 'htxmcode'=>array(
		'kind'=>'htxmcode',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'htxm_id',
		'sql_kind'=>'in'
	  ),
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	  'htcode'=>array(
	  'kind'=>'htcode',
	  'name'=>'htcode',
	  'msg'=>'合同编号',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'ht_id',
	  'sql_kind'=>'='
	),

	'audit_type'=>array(
	  'kind'=>'select',
	  'name'=>'audit_type',
	  'msg'=>'审核类型',
	  'width'=>'70px',
	  'arr'=>$setup_audit_type,
	  'sql_field'=>'audit_type',
	  'sql_kind'=>'='
	),
	'zs_change_date1'=>array(
	  'kind'=>'date1',
	  'name'=>'zs_change_date1',
	  'msg'=>'变更开始',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zs_change_date',
	  'sql_kind'=>'>='
	),
	  'zs_change_date2'=>array(
	  'kind'=>'date2',
	  'name'=>'zs_change_date2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zs_change_date',
	  'sql_kind'=>'<='
	),
	'audit_code'=>array(
		'kind'=>'text',
		'name'=>'audit_code',
		'msg'=>'专业代码',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'audit_code ',
		'sql_kind'=>'like%'
	  ),
		'br1'=>array(
	'kind'=>'br'
	),
	 'certStart1'=>array(
	  'kind'=>'date1',
	  'name'=>'certStart1',
	  'msg'=>'注册日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'certStart',
	  'sql_kind'=>'>='
	),
	'certStart2'=>array(
	  'kind'=>'date2',
	  'name'=>'certStart2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'certStart',
	  'sql_kind'=>'<='
	),
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'认证体系',
	  'width'=>'70px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),

	'certNo'=>array(
	  'kind'=>'text',
	  'name'=>'certNo',
	  'msg'=>'证书编号',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'certNo',
	  'sql_kind'=>'%like%'
	),
	'coverFields'=>array(
	  'kind'=>'text',
	  'name'=>'coverFields',
	  'msg'=>'证书范围',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'coverFields',
	  'sql_kind'=>'%like%'
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'70px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	  'zsprintdate1'=>array(
	  'kind'=>'date1',
	  'name'=>'zsprintdate1',
	  'msg'=>'登记日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zsprintdate',
	  'sql_kind'=>'>='
	),
	  'zsprintdate2'=>array(
	  'kind'=>'date2',
	  'name'=>'zsprintdate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zsprintdate',
	  'sql_kind'=>'<='
	),
		'br2'=>array(
	'kind'=>'br'
	),
	'certEnd1'=>array(
	  'kind'=>'date1',
	  'name'=>'certEnd1',
	  'msg'=>'注册到期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'certEnd',
	  'sql_kind'=>'>='
	),
	  'certEnd2'=>array(
	  'kind'=>'date2',
	  'name'=>'certEnd2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'certEnd',
	  'sql_kind'=>'<='
	),
	 'online'=>array(
	  'kind'=>'select',
	  'name'=>'online',
	  'msg'=>'证书状态',
	  'width'=>'70px',
	  'arr'=>$setup_certificate_online,
	  'sql_field'=>'online',
	  'sql_kind'=>'='
	),

    'maildate_s'=>array(
	  'kind'=>'date1',
	  'name'=>'maildate_s',
	  'msg'=>'邮寄日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'maildate',
	  'sql_kind'=>'>='
	),
	  'maildate_e'=>array(
	  'kind'=>'date2',
	  'name'=>'maildate_e',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'maildate',
	  'sql_kind'=>'<='
	),
	'eisc_address'=>array(
	  'kind'=>'text',
	  'name'=>'eisc_address',
	  'msg'=>'地址',
	  'width'=>'120px',
	  'arr'=>'',
	  'sql_field'=>'eisc_address',
	  'sql_kind'=>'%like%'
	),
	'ifpost'=>array(
	  'kind'=>'hidden',
	  'name'=>'ifpost'
	),
	'op'=>array(
	  'kind'=>'hidden',
	  'name'=>'op'
	)

);
$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL

$sql_temp .= Power::xt_htfrom();
if ($op != '1'){
	if($ifpost == '0' or $ifpost == ''){
		$sql_temp.=" AND maildate='0000-00-00'";
	}else if($ifpost == '1'){
		$sql_temp.=" AND maildate!='0000-00-00'";
	}
}


$sql = "SELECT * FROM `{$dbtable['zs_cert']}`	WHERE 1 $sql_temp ORDER BY id DESC";
$rows = $db->query($sql);

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
TD{
    font-size: 12px;
    vertical-align: middle;
vnd.ms-excel.numberformat:@;
}
br
	{mso-data-placement:same-cell;}
</style>
<table border="1" >
 <tr bgcolor="#FFFFFF">
<?php
while($result = $db->fetch_array($rows))
{
	if(!in_array($result['zuzhi_id'],$zuzhi_id_db)){
		$com = $db->get_one("SELECT * FROM `{$dbtable['mk_company']}` WHERE id='{$result['zuzhi_id']}'");
		$result['eilinkman'] = $com['eilinkman'];
		$result['eilinkman_mob'] = $com['eilinkman_mob'];
		$result['eiaddress_code'] = $com['eiaddress_code'];
		$result['eiregistername'] = $com['eiregistername'];
		if($com['eipro_address']!='')
		{
			$result['eisc_address'] = $com['eipro_address'];
		}
		elseif($com['eireg_address']!='')
		{
			$result['eisc_address'] = $com['eireg_address'];
		}
		elseif($com['eisc_address']!='')
		{
			$result['eisc_address'] = $com['eisc_address'];
		}
?>

			<td style="width:700px">
			邮编：<?php echo $result['eiaddress_code']; ?> <br>
			企业地址：<?php echo $result['eisc_address']; ?> <br>
			企业名称：<?php echo $result['eiregistername']; ?> <br>
			联系人：<?php echo $result['eilinkman']; ?>
			</td>

<?php
		$i++;
		if($i == '2'){
			echo"</tr><tr>";
			$i='0';
		}
	}
	$zuzhi_id_db []= $result['zuzhi_id'];
}
?>
</tr>
</table>