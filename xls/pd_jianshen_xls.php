<?php
set_time_limit(60);
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=pd_jianshen-".date("Y-m-d").".xls");

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
<table border="1"><tr>
		<th align="center">合同编号</th>
		<th align="center">项目编号</th>
		<th align="center">审核结束日期</th>
		<th align="center">证书编号</th>
		<th align="center">证书注册时间</th>
		<th align="center">证书到期时间</th>
		<th align="center">证书状态</th>
		<th align="center">企业名称</th>
		<th align="center">企业人数</th>
		<th align="center">合同金额</th>
		<th align="center">发票金额</th>
		<th align="center">体系</th>
		<th align="center">体系版本</th>
		<th align="center">审核类型</th>
		<th align="center">地区</th>
		<th align="center">项目来源</th>
		<th align="center">联系人</th>
		<th align="center">电话</th>
		<th align="center">传真</th>
		<th align="center">通讯地址</th>
		<th align="center">邮编</th>
		<th align="center">专业类别</th>
		<th align="center">法人代表</th>
		<th align="center">管理代表</th>
		<th align="center">风险系数</th>
		<th align="center">资质等级</th>
		<th align="center">审核范围</th>
		<th align="center">纪委会意见</th>
		<th align="center">历次审核老师</th>
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

		$cm = $db->get_one("SELECT eiregistername,eiman_amount,eiarea,eilinkman,eilinkman_tel, eiphone, eifax,eilinkman_fax,eisc_address, eiscpostalcode,eifaren,eiguandai,eiscpostalcode FROM mk_company where id='{$result['zuzhi_id']}'");//企业

			$result['eiregistername'] = $cm['eiregistername'];
			$result['eiman_amount'] = $cm['eiman_amount'];
			$result['eiarea'] = $cm['eiarea'];
			$result['eilinkman'] = $cm['eilinkman'];
			$result['eilinkman_tel'] = $cm['eiphone'];
			$result['eilinkman_fax'] = $cm['eifax'];
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
		$result['audit_type'] = Cache::cache_audit_type($result['audit_type']);
		if($result['audit_type'] == '二阶段'){$result['audit_type'] = '初审';}

	    $xm = $db->query("SELECT taskId,audit_type,describes FROM `xm_item` where  htxm_id  ='{$result['htxm_id']}' ORDER BY id ASC");

		$empNamelist=$describes=array();
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


$result['zsonline'] = Cache::cache_Certification_online($result['zsonline']);


foreach ($setup_audit_ver as $kv => $vv){
	if($result['audit_ver'] == $kv){
		$result['audit_ver'] = $vv['msg'];
	}
}

?>
        <tr bgcolor="#FFFFFF">
        <td  align="center"><?php echo $result['htcode'];?></td>
        <td  align="center"><?php echo $result['htxmcode'];?></td>
		<td  align="center"><?php echo $result['taskBeginDate'];?></td>
		<td >&nbsp;<?php echo $result['certNo'];?></td>
        <td  align="center"><?php echo $result['certStart'];?></td>
        <td  align="center"><?php echo $result['certEnd'];?></td>
		<td  align="center"><?php echo $result['zsonline'];?></td>
		<td  align="center"><?php echo $result['eiregistername'];?></td>
        <td  align="center"><?php echo $result['eiman_amount'];?></td>
        <td  align="center"><?php echo $result['finance_item'].$result['contract_money'];?></td>
		     <td  align="center"><?php echo $result['invoicemoney'];?></td>
        <td  align="center"><?php echo $result['ht_iso'];?></td>
        <td  align="center"><?php  echo $result['audit_ver'];?></td>
		<td  align="center"><?php  echo $result['audit_type'];?></td>
        <td >&nbsp;<?php echo $result['eiarea'];?></td>
        <td ><?php echo $result['htfrom'];?></td>

		<td >&nbsp;<?php echo $result['eilinkman'];?></td>
		<td >&nbsp;<?php echo $result['eilinkman_tel'];?></td>
		<td >&nbsp;<?php echo $result['eilinkman_fax'];?></td>
		<td >&nbsp;<?php echo $result['eisc_address'];?></td>
		<td >&nbsp;<?php echo $result['eiscpostalcode'];?></td>
		<td >&nbsp;<?php echo $result['audit_code'];?></td>
		<td >&nbsp;<?php echo $result['eifaren'];?></td>
		<td >&nbsp;<?php echo $result['eiguandai'];?></td>
		<td >&nbsp;<?php echo $result['risk'];?></td>
		<td >&nbsp;</td>
		<td >&nbsp;<?php echo $result['renzhengfanwei'];?></td>
		<td >&nbsp;<?php echo $describes; ?></td>
		<td >&nbsp;<?php echo $empNamelist; ?></td>
        </tr>
<?php
}
?>

</table>