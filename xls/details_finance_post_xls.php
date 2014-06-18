<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=finance_mail-".date("Y-m-d").".xls");

header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'/include/setup/setup_pd_online.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_ver.php';
include SET_DIR.'setup_province.php';


Power::CkPower('Z3S');

$seach_arr = array(
	//每个表单控件NAME值作为KEY
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
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'80px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	'invoice'=>array(
	  'kind'=>'text',
	  'name'=>'invoice',
	  'msg'=>'发票号',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'invoice',
	  'sql_kind'=>'='
	),
	'date_s'=>array(
	  'kind'=>'date1',
	  'name'=>'date_s',
	  'msg'=>'发票日期',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'invoicemoneytime',
	  'sql_kind'=>'>='
	),
	'date_e'=>array(
	  'kind'=>'date2',
	  'name'=>'data_e',
	  'msg'=>'',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'invoicemoneytime',
	  'sql_kind'=>'<='
	),
	'br'=>array(
		'kind'=>'br'
	),
	 'htxmcode'=>array(
	  'kind'=>'text',
	  'name'=>'htxmcode',
	  'msg'=>'项目编号',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'',
	  'sql_kind'=>''
	)
);
$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;
if($htxmcode != ''){
	$sql_temp = "  and ht_id IN(SELECT ht_id FROM (SELECT ht_id FROM ht_contract_item WHERE htxmcode LIKE'%$htxmcode%') AS t)".$sql_temp;
}

$sql = "SELECT * FROM cw_finance_list WHERE 1 $sql_temp  ORDER BY id DESC";
$rows = $db->query($sql);
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
while($result = $db->fetch_array($rows))
{
	if(!in_array($result['zuzhi_id'],$zuzhi_id_db)){
		$com = $db->get_one("SELECT * FROM `{$dbtable['mk_company']}` WHERE id='{$result['zuzhi_id']}'");
		$result['eilinkman'] = $com['eilinkman'];
		$result['eilinkman_mob'] = $com['eilinkman_mob'];
		$result['eiaddress_code'] = $com['eiaddress_code'];
		$result['eiregistername'] = $com['eiregistername'];
		$result['htfrom'] = Cache::cache_htfrom($com['htfrom']);
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
			<td>
				<?php echo $result['htfrom']; ?>
			</td>
			<td style="width:350px">
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