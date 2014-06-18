<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=pd_renzheng-".date("Y-m-d").".xls");

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
Power::CkPower('Z3S');
$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
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
	'actualtaskEndDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'actualtaskEndDate1',
	  'msg'=>'实际结束日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'actualtaskEndDate',
	  'sql_kind'=>'>='
	),
	  'actualtaskEndDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'actualtaskEndDate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'actualtaskEndDate',
	  'sql_kind'=>'<='
	),
		'eiarea'=>array(
	  'kind'=>'eiarea',
	  'name'=>'eiarea',
	  'msg'=>'所在地区',
	  'width'=>'104px',
	  'arr'=>$setup_province,
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	'br1'=>array(
	'kind'=>'br'
	),
	'htxmcode'=>array(
		'kind'=>'htxmcode',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'htxm_id',
		'sql_kind'=>'in'
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
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'认证体系',
	  'width'=>'80px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	  'zsFinallyDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'zsFinallyDate1',
	  'msg'=>'证书到期日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zsFinallyDate',
	  'sql_kind'=>'>='
	),
	  'zsFinallyDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'zsFinallyDate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zsFinallyDate',
	  'sql_kind'=>'<='
	),
	'iffuping'=>array(
	  'kind'=>'hidden',
	  'name'=>'iffuping',
	  'msg'=>'是否复评',
	  'width'=>'60px',
	  'arr'=>array('2'=>'待定','1'=>'接受','0'=>'不接受'),
	  'sql_field'=>'iffuping',
	  'sql_kind'=>'='
	)
);

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$sql_temp	= $TopSearch->SearchSql;
//构造搜索SQL
$sql_temp  .= Power::xt_htfrom();

$sql = "SELECT * FROM  ht_repeat_contract  WHERE 1 $sql_temp ORDER BY id DESC";
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
<table border="1">	<tr>
		<th align="center">合同编号</th>
		<th align="center">项目编号</th>
        <th align="center">多体系</th>
		<th align="center">体系</th>
		<th align="center">审核类型</th>
		<th align="center">体系版本</th>
		<th align="center">证书编号</th>
		<th align="center">证书注册时间</th>
		<th align="center">证书到期时间</th>
		<th align="center">证书状态</th>
		<th align="center">企业名称</th>
		<th align="center">企业人数</th>
		<th align="center">项目来源</th>
	    <th align="center">风险系数</th>
		<th align="center">资质等级</th>
		<th align="center">纪委会意见</th>
		<th align="center">历次审核老师</th>
		<th align="center">联系人</th>
		<th align="center">电话</th>
		<th align="center">传真</th>
		<th align="center">通讯地址</th>
		<th align="center">邮编</th>
		<th align="center">地区</th>
		<th align="center">法人代表</th>
		<th align="center">管理代表</th>
		<th align="center">证书执行情况</th>
		<th align="center">审核范围</th>
		<th align="center">专业代码</th>
		<th align="center">制单人</th>
		<th align="center">制单日期</th>
        </tr>
<?php
while($result = $db->fetch_array($rows))
{
		$ht = $db->get_one("SELECT htcode FROM `ht_contract` where id='{$result['ht_id']}'");
		$result['htcode'] = $ht['htcode'];

        $htx = $db->get_one("SELECT risk,htxmcode FROM `ht_contract_item` where id='{$result['htxm_id']}'");
		 $result['risk']=Cache::cache_risk($htx['risk']);
		 $result['htxmcode'] = $htx['htxmcode'];

		$ht = $db->get_one("SELECT iso FROM ht_contract where id='{$result['ht_id']}'");//合同
		$result['ht_iso'] = str_replace(',','',$ht['iso']);

		$rows_cer = $db->get_one("SELECT certStart,certEnd,certNo,online,coverFields,audit_code FROM zs_cert WHERE id ='{$result['zsid']}' ORDER BY id DESC LIMIT 1");//证书
		$result['certStart'] = $rows_cer['certStart'];
		$result['certEnd'] = $rows_cer['certEnd'];
		$result['certNo'] = $rows_cer['certNo'];
		$result['zsonline'] = Cache::cache_Certification_online($rows_cer['online']);

		$cm = $db->get_one("SELECT eiregistername,eiman_amount,eiarea,eilinkman,eiphone, eifax,eisc_address, eiscpostalcode,eifaren,eiguandai,eiscpostalcode FROM mk_company where id='{$result['zuzhi_id']}'");//企业

			$result['eiregistername'] = $cm['eiregistername'];
			$result['eiman_amount'] = $cm['eiman_amount'];
			$result['eiarea'] = $cm['eiarea'];
			$result['eilinkman'] = $cm['eilinkman'];
			$result['eiphone'] = $cm['eiphone'];
			$result['eifax'] = $cm['eifax'];
			$result['eisc_address'] = $cm['eisc_address'];
			$result['eiscpostalcode'] = $cm['eiscpostalcode'];
			$result['eifaren'] = $cm['eifaren'];
			$result['eiguandai'] = $cm['eiguandai'];
			$result['eiscpostalcode'] = $cm['eiscpostalcode'];


	   $result['contract_money'] = $result['invoicemoney'] = '';
	    $cw = $db->query("SELECT finance_item,contract_money  FROM `cw_finance_item` where  ht_id ='{$result['ht_id']}'");
		while($rows_cw = $db->fetch_array($cw))
		{
		   $result['contract_money']== '' ? $result['contract_money']= Cache::cache_Finance_item($rows_cw['finance_item']).':'.$rows_cw['contract_money'] : $result['contract_money']= $result['contract_money'].'<br>'.Cache::cache_Finance_item($rows_cw['finance_item']).':'.$rows_cw['contract_money'];
		}

	    $cw_list = $db->query("SELECT invoicemoney FROM cw_finance_list where ht_id ='{$result['ht_id']}'");
		while($rows_cw_list = $db->fetch_array($cw_list))
		{
		   $result['invoicemoney'] == '' ? $result['invoicemoney'] = $rows_cw_list['invoicemoney'] : $result['invoicemoney'] = $result['invoicemoney'].'；'.$rows_cw_list['invoicemoney'] ;
		}

		$result['htfrom'] = Cache::cache_htfrom($result['htfrom']);
		//查项目信息
		//$zs_if = '';
		$xmdb = $db->get_one("SELECT audit_type,renzhengfanwei,audit_code FROM xm_item WHERE id='$result[xmid]'");
		$result['audit_type'] = Cache::cache_audit_type($xmdb['audit_type']);
		if($result['audit_type'] == '二阶段'){$result['audit_type'] = '初审';}

	    $xm = $db->query("SELECT taskId,audit_type,describes FROM `xm_item` where  htxm_id  ='{$result['htxm_id']}' ORDER BY id ASC");

		$describes = $empNamelist=array();
		while($rows_xm = $db->fetch_array($xm))
		{
			$describes []= Cache::cache_audit_type($rows_xm['audit_type']).':'.$rows_xm['describes'];
		   $empName = '';
		   $pr_sql = "SELECT empName FROM xm_auditor WHERE taskId='$rows_xm[taskId]'";
		   $pr_q = $db->query($pr_sql);
		   while ($pr = $db->fetch_array($pr_q)){
			  $empName == '' ? $empName= $pr['empName'] : $empName= $empName.','.$pr['empName'];
		   }
			$empNamelist []= Cache::cache_audit_type($rows_xm['audit_type']).':'.$empName;
		}
		$empNamelist = implode('<br>',$empNamelist);
		$describes = implode('<br>',$describes);

foreach ($setup_audit_ver as $kv => $vv){
	if($result['audit_ver'] == $kv){
		$result['audit_ver'] = $vv['msg'];
	}
}
?>
        <tr bgcolor="#FFFFFF">
        	<td  align="center"><?php echo $result['htcode'];?></td>
        	<td  align="center"><?php echo $result['htxmcode'];?></td>
			<td  align="center"><?php echo $result['ht_iso']; ?></td>
			<td  align="center"><?php echo $result['iso']; ?></td>
			<td  align="center"><?php echo $result['audit_type']; ?></td>
			<td  align="center"><?php  echo $result['audit_ver'];?></td>		
			<td ><?php echo $result['certNo']?></td>
			<td  align="center"><?php echo $result['certStart'];?></td>
			<td  align="center"><?php echo $result['certEnd'];?></td>
			<td  align="center"><?php echo $result['zsonline'];?></td>
			<td  align="center"><?php echo $result['eiregistername'];?></td>
			<td  align="center"><?php echo $result['eiman_amount'];?></td>
			<td ><?php echo $result['htfrom'];?></td>
			<td ><?php echo $result['risk'];?></td>
			<td >&nbsp;</td>
			<td >&nbsp;<?php echo $describes;?></td>
			<td >&nbsp;<?php echo $empNamelist; ?></td>
			<td >&nbsp;<?php echo $result['eilinkman'];?></td>
			<td >&nbsp;<?php echo $result['eiphone'];?></td>
			<td >&nbsp;<?php echo $result['eifax'];?></td>
			<td >&nbsp;<?php echo $result['eisc_address'];?></td>

			<td >&nbsp;<?php echo $result['eiscpostalcode'];?></td>
			<td >&nbsp;<?php echo $result['eiarea'];?></td>

			<td >&nbsp;<?php echo $result['eifaren'];?></td>
			<td >&nbsp;<?php echo $result['eiguandai'];?></td>
			<td  align="center"></td>

			<td >&nbsp;<?php echo $rows_cer['coverFields'];?></td>
			<td >&nbsp;<?php echo $rows_cer['audit_code'];?></td>
				<td >&nbsp;<?php echo $result['zd_ren'];?></td>
			<td >&nbsp;</td>
        </tr>
<?php
}
?>

</table>
<?php echo $result['zd_date'];?>