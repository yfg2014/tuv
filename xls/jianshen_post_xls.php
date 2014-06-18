<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=mail-".date("Y-m-d").".xls");

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
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'a.zuzhi_id',
	  'sql_kind'=>'in'
	),
	'audit_code'=>array(
	  'kind'=>'text',
	  'name'=>'audit_code',
	  'msg'=>'专业代码',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'a.audit_code',
	  'sql_kind'=>'%like%'
	),
	'auditplandate1'=>array(
	  'kind'=>'date1',
	  'name'=>'auditplandate1',
	  'msg'=>'计划开始时间',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'a.auditplandate',
	  'sql_kind'=>'>='
	),
	  'auditplandate2'=>array(
	  'kind'=>'date2',
	  'name'=>'auditplandate2',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'a.auditplandate',
	  'sql_kind'=>'<='
	),
	'audit_type'=>array(
	  'kind'=>'select',
	  'name'=>'audit_type',
	  'msg'=>'审核类型',
	  'width'=>'104px',
	  'arr'=>$setup_audit_type,
	  'sql_field'=>'a.audit_type',
	  'sql_kind'=>'='
	),
	'eiarea'=>array(
	  'kind'=>'eiarea',
	  'name'=>'eiarea',
	  'msg'=>'省份',
	  'width'=>'104px',
	  'arr'=>$setup_province,
	  'sql_field'=>'a.zuzhi_id',
	  'sql_kind'=>'in'
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'104px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'a.htfrom',
	  'sql_kind'=>'='
	),
	'br1'=>array(
	'kind'=>'br'
	),
	'htcode'=>array(
	  'kind'=>'htcode',
	  'name'=>'htcode',
	  'msg'=>'合同编号',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'a.ht_id',
	  'sql_kind'=>'='
	),
	'htxmcode'=>array(
		'kind'=>'htxmcode',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'a.htxm_id',
		'sql_kind'=>'in'
	  ),
	  'finalItemDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'finalItemDate1',
	  'msg'=>'监审最后期限',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'a.finalItemDate',
	  'sql_kind'=>'>='
	),
	  'finalItemDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'finalItemDate2',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'a.finalItemDate',
	  'sql_kind'=>'<='
	),
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'认证体系',
	  'width'=>'104px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'a.iso',
	  'sql_kind'=>'='
	),
	'eireg_address'=>array(
	  'kind'=>'text',
	  'name'=>'eireg_address',
	  'msg'=>'地址',
	  'width'=>'104px',
	  'arr'=>'',
	  'sql_field'=>'',
	  'sql_kind'=>''
	),
	'online'=>array(
	  'kind'=>'hidden',
	  'name'=>'online'
	  )
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$sql_temp	= $TopSearch->SearchSql;
//构造搜索SQL

if($online == ''){
	$sql_temp = "  and a.online='5' and (a.audit_type='1002' or a.audit_type='1003' or  a.audit_type='1004')".$sql_temp;
}else{
	$sql_temp = "  and a.online!='5' and (a.audit_type='1002' or a.audit_type='1003' or  a.audit_type='1004')".$sql_temp;
}
if($eireg_address != ''){
	$sql_temp = "  and a.zuzhi_id IN(SELECT id FROM (SELECT id FROM mk_company WHERE eireg_address LIKE'%$eireg_address%') AS t)".$sql_temp;
}

//$sql_temp = " and zs_if!='0' and (audit_type = '1002' or audit_type = '1003' or audit_type = '1004')  ".$sql_temp;
//构造搜索SQL

$sql = "SELECT a.*,b.certStart,b.certEnd,b.online AS zsonline,b.certNo
					 	FROM `{$dbtable['xm_item']}` a LEFT JOIN `{$dbtable['zs_cert']}` b ON b.id=a.zsid
						WHERE 1 $sql_temp AND (b.online = '01' OR b.online = '03' OR b.online = '04') ORDER BY a.finalItemDate ASC";
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