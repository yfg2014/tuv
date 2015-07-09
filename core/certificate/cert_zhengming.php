<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_certificate_online.php';

Power::CkPower('E0Z');

$width = '1000px';
$seach_arr = array(    
	'eiregistername'=>array(
	  'kind'=>'company',
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
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
	'assessmentdate1'=>array(
	  'kind'=>'date1',
	  'name'=>'assessmentdate1',
	  'msg'=>'评定日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'assessmentdate',
	  'sql_kind'=>'>='
	),
	  'assessmentdate2'=>array(
	  'kind'=>'date2',
	  'name'=>'assessmentdate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'assessmentdate',
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
	'br1'=>array(
	'kind'=>'br'
	),
	'renzhengfanwei'=>array(
	  'kind'=>'text',
	  'name'=>'renzhengfanwei',
	  'msg'=>'证书范围',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'renzhengfanwei',
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
);

$TopSearch = new TopSearch($seach_arr);
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;
$SearchHtml	= $TopSearch->SearchHtml;

$sql_temp = $sql_temp." AND zs_if='1' AND (audit_type='1002' OR audit_type='1003' OR audit_type='1004') AND ifchangecert='0'";
$sql_temp .= Power::xt_htfrom();
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$Item = new Item();
$result = $Item->listElementPost($params);

include TEMP.'header.htm';
include TEMP.'certificate/cert_zhengming.htm';
include TEMP.'footer.htm';
?>