<?php
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include(S_DIR.'include/topsearch.php');
Power::CkPower('F1S');
GrepUtil::InitGP(array('eiregistername','page'));

$seach_arr=array(
//?
	 'eiregistername'=>array(
	  'kind'=>'company',
	  'name'=>'eiregistername',
	  'msg'=>'组织名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'认证体系',
	  'width'=>'104px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	'date'=>array(
		'kind'=>'date1',
		'name'=>'date',
		'msg'=>'计划开始',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'auditplandate',
		'sql_kind'=>'>='
	 ),
	'e_date'=>array(
		'kind'=>'date2',
		'name'=>'e_date',
		'msg'=>'',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'auditplandate',
		'sql_kind'=>'<='
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
	  'sql_field'=>'htxm_id',
	  'sql_kind'=>'='
	),
	  'ht_id'=>array(
		'kind'=>'htcode',
		'name'=>'ht_id',
		'msg'=>'合同编号',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'ht_id',
		'sql_kind'=>'='
	  ),
    'htfrom'=>array(
	  'kind'=>'select',
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'104px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
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
	'product'=>array(
		'kind'=>'product',
		'name'=>'product',
		'msg'=>'认证产品',
		'width'=>'100px',
		'arr'=>'',
		'sql_field'=>'product',
		'sql_kind'=>'%like%'
	),
	'zuzhi_id'=>array(
	  'kind'=>'hidden',
	  'name'=>'zuzhi_id',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'='
	),

);


$TopSearch = new TopSearch($seach_arr);
//???
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?
$sql_temp .= Power::xt_htfrom();
$sql_temp = " and (audit_type ='1002' or audit_type ='1003' or audit_type ='1004') ".$sql_temp;



$params = array(
	'search' => $sql_temp,
	'url' => $url,
);


$Item = new Item();
$result = $Item->listElement($params);



$width = '900px';
include T_DIR.'header.htm';
include T_DIR.'type/type_list.htm';
include T_DIR.'footer.htm';
?>