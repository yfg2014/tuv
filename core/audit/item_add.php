<?php
!defined('IN_SUPU') && exit('Forbidden');
include S_DIR.'include/module/Contract.php';
include S_DIR.'include/module/ContractItem.php';
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_audit_type.php';
include SET_DIR.'setup_audit_iso.php';
include SET_DIR.'setup_htfrom.php';
include SET_DIR.'setup_audit_ver.php';
include SET_DIR.'setup_ht_online.php';

$width='1000px';
Power::CkPower('C0T');
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
	 'htxmcode'=>array(
		'kind'=>'text',
		'name'=>'htxmcode',
		'msg'=>'项目编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'htxmcode',
		'sql_kind'=>'%like%'
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
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;
$SearchHtml	= $TopSearch->SearchHtml;
$sql_temp .= Power::xt_htfrom();

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$ContractItem = new ContractItem();
$result = $ContractItem->listElement($params);

include TEMP.'header.htm';
include TEMP.'audit/item_add.htm';
include TEMP.'footer.htm';
?>