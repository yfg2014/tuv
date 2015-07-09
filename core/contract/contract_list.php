<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_audit_type.php';
include SET_DIR.'setup_audit_iso.php';
include SET_DIR.'setup_htfrom.php';
include SET_DIR.'setup_ht_online.php';

Power::CkPower('B0S');
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
		'kind'=>'text',
		'name'=>'zd_ren',
		'msg'=>'受 理 人',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'zd_ren',
		'sql_kind'=>'%like%'
	  ),
	  'htfrom'=>array(
	  'kind'=>'select',   //?
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'100px',
	  'arr'=>$setup_htfrom ,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	 'date'=>array(
		'kind'=>'date1',
		'name'=>'date',
		'msg'=>'受理日期',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'htdate',
		'sql_kind'=>'>='
	 ),
	'e_date'=>array(
		'kind'=>'date2',
		'name'=>'e_date',
		'msg'=>'申请日期',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'htdate',
		'sql_kind'=>'<='
	 ),

	 'audit_type'=>array(
		'kind'=>'select',
		'name'=>'audit_type',
		'msg'=>'审核类型',
		'width'=>'100px',
		'arr'=>$setup_audit_type,
		'sql_field'=>'audit_type',
		'sql_kind'=>''
	),

	 'br'=>array(
		'kind'=>'br'
	 ),

	 'htxmcode'=>array(
		'kind'=>'htxmcode',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'id',
		'sql_kind'=>'in'
	  ),
	 'htcode'=>array(
		'kind'=>'text',
		'name'=>'htcode',
		'msg'=>'合同编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'htcode',
		'sql_kind'=>'='
	  ),
	'audit_code'=>array(
		'kind'=>'text',
		'name'=>'audit_code',
		'msg'=>'专业代码',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'',
		'sql_kind'=>''
	  ),
	  'first'=>array(
		'kind'=>'select',
		'name'=>'first',
		'msg'=>'是否初次',
		'width'=>'100px',
		'arr'=>array('1' =>array('code'=>'1','msg'=>'是','first'=>'1','online'=>'1'),
		              '0' =>array('code'=>'0','msg'=>'否','first'=>'0','online'=>'1')),
		'sql_field'=>'first',
		'sql_kind'=>'='
	  ),
	'iso'=>array(
		'kind'=>'select',
		'name'=>'iso',
		'msg'=>'体系',
		'width'=>'100px',
		'arr'=>$setup_audit_iso,
		'sql_field'=>'iso',
		'sql_kind'=>'%like%'
	),

	'online'=>array(
		'kind'=>'hidden',
		'name'=>'online',
		'sql_field'=>'online',
	),

	 'br'=>array(
		'kind'=>'br'
	 ),

	 'product'=>array(
		'kind'=>'text',
		'name'=>'product',
		'msg'=>'认证产品',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'',
		'sql_kind'=>''
	)
);

$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;
$SearchHtml	= $TopSearch->SearchHtml;
$sql_temp .= Power::xt_htfrom();
$sql_temp .= Power::CpPower(1);

if($audit_code != ''){
	$sql_temp = $sql_temp." AND id IN(SELECT ht_id FROM ht_contract_item  WHERE audit_code LIKE('$audit_code%'))";
}
if($audit_type != ''){
	$sql_temp = $sql_temp." AND id IN(SELECT ht_id FROM ht_contract_item  WHERE audit_type = '$audit_type')";
}
if($online != ''){
	$sql_temp = $sql_temp." AND online = '$online' ";
}
if($product!='')
{
	$sql_temp = $sql_temp." AND id IN(SELECT ht_id FROM ht_contract_item  WHERE product in( SELECT code FROM setup_product WHERE msg LIKE '%$product%' ))";
}

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$Contract = new Contract();
$result = $Contract->listElement($params);


$width = '1200px';
include_once TEMP.'header.htm';
include_once TEMP.'contract/contract_list.htm';
include_once TEMP.'footer.htm';
?>