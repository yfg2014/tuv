<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=cert_list-".date("Y-m-d").".xls");

header("Pragma: no-cache");
header("Expires: 0");

!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Change.php';
include_once S_DIR.'include/module/Certificate.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/cache_certificate_online.php';
include_once S_DIR.'include/setup/setup_changeitem.php';
include(S_DIR.'include/topsearch.php');
Power::CkPower('F0S');
 $seach_arr=array(
 'eiregistername'=>array(
	  'kind'=>'company',
	  'name'=>'eiregistername',
	  'msg'=>'组织名称',
	  'width'=>'95px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	'zuzhi_id'=>array(
	  'kind'=>'text',
	  'name'=>'zuzhi_id',
	  'msg'=>'组织编号',
	  'width'=>'75px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'='
	),
	 'changeitem'=>array(
		'kind'=>'select',
		'name'=>'changeitem',
		'msg'=>'变更类型',
		'width'=>'115px',
		'arr'=>$setup_changeitem,
		'sql_field'=>'changeitem',
		'sql_kind'=>'='
	),
	 'htfrom'=>array(
		'kind'=>'select',
		'name'=>'htfrom',
		'msg'=>'合同来源',
		'width'=>'75px',
		'arr'=>$setup_htfrom,
		'sql_field'=>'htfrom',
		'sql_kind'=>'='
	 ),
	 's_date3'=>array(
		'kind'=>'date1',
		'name'=>'s_date3',
		'msg'=>'制单日期',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'zd_date',
		'sql_kind'=>'>='
	 ),
	'e_date3'=>array(
		'kind'=>'date2',
		'name'=>'e_date3',
		'msg'=>'',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'zd_date',
		'sql_kind'=>'<='
	 ),
	'br'=>array(
		'kind'=>'br'
	 ),
	 'certNo'=>array(
		'kind'=>'certNo',
		'name'=>'certNo',
		'msg'=>'证书编号',
		'width'=>'95px',
		'arr'=>'',
		'sql_field'=>'zsid',
		'sql_kind'=>'in'
	  ),
	  	'ht_id'=>array(
		'kind'=>'text',
		'name'=>'ht_id',
		'msg'=>'合同编号',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'ht_id',
		'sql_kind'=>'='
	  ),
	  's_date2'=>array(
		'kind'=>'date1',
		'name'=>'s_date2',
		'msg'=>'变更时间',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'zs_change_date',
		'sql_kind'=>'>='
	 ),
	'e_date2'=>array(
		'kind'=>'date2',
		'name'=>'e_date2',
		'msg'=>'',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'zs_change_date',
		'sql_kind'=>'<='
	 ),
	 'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'领域',
	  'width'=>'104px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	'zd_ren'=>array(
	  'kind'=>'text',
	  'name'=>'zd_ren',
	  'msg'=>'制单人',
	  'width'=>'104px',
	  'arr'=>'',
	  'sql_field'=>'zd_ren',
	  'sql_kind'=>'%like%'
	),
	'br3' => array(
	'kind'=>'br'
	),
	'htxmcode'=>array(
	  'kind'=>'htxmcode',
	  'name'=>'htxmcode',
	  'msg'=>'项目编号',
	  'width'=>'95px',
	  'arr'=>'',
	  'sql_field'=>'htxm_id',
	  'sql_kind'=>'='
	),
	'listnum'=>array(
	  'kind'=>'text',
	  'name'=>'listnum',
	  'msg'=>'批号',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'listnum',
	  'sql_kind'=>'='
	),
	 'up_date1'=>array(
		'kind'=>'date1',
		'name'=>'up_date1',
		'msg'=>'上报日期',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'up_date',
		'sql_kind'=>'>='
	 ),
	'up_date2'=>array(
		'kind'=>'date2',
		'name'=>'up_date2',
		'msg'=>'',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'up_date',
		'sql_kind'=>'<='
	 )
);
$TopSearch = new TopSearch($seach_arr);
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL

$listNo = 0;
$sql = "SELECT * FROM zs_change  WHERE 1 $sql_temp ORDER BY id DESC";
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
	<th >序号</th>
		<th >体系</th>
		<th >证书编号</th>
		<th >组织名称</th>
		<th >变更类型</th>
		<th >变更日期</th>
		<th >合同编号</th>
		<th >清单批号</th>
		<th >变更原因</th>
		<th >制单日期</th>
        </tr>
<?php
while($result = $db->fetch_array($rows))
{

		$ht = $db->get_one("SELECT htxmcode FROM ht_contract_item where id='{$result['htxm_id']}' ");
        $result['htxmcode'] = $ht['htxmcode'];
		$cm = $db->get_one("SELECT B.eiregistername FROM mk_company as B where  B.id='{$result['zuzhi_id']}' ");
         $result['eiregistername'] = $cm['eiregistername'];
		$ct = $db->get_one(" SELECT C.certNo FROM zs_cert as C where C.id='{$result['zsid']}'");
		$result['certNo'] = $ct['certNo'];
		$result['changeitem'] = Cache::cache_changeitem($result['changeitem']);

?>
        <tr bgcolor="#FFFFFF">
		    	<td  ><?php echo $listNo+=1 ?></td>
				 <td  ><?php echo $result['iso']?></td>
				 <td  ><?php echo $result['certNo']?></td>
				<td  ><?php echo $result['eiregistername']?></td>
				<td ><?php echo $result['changeitem']?></td>
				<td ><?php echo $result['zs_change_date']?></td>
				 <td  ><?php echo $result['htxmcode']?></td>
				 <td  ><?php echo $result['listnum']?></td>
				 <td ><?php echo $result['changereason']?></td>
		         <td ><?php echo $result['up_date']?></td>
        </tr>
<?php
}
?>

</table>