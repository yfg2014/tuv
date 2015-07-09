<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/fees_finance.php');
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';

Power::CkPower('G1S');

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
	'audit_type'=>array(
	  'kind'=>'select',
	  'name'=>'audit_type',
	  'msg'=>'审核类型',
	  'width'=>'70px',
	  'arr'=>$setup_audit_type,
	  'sql_field'=>'audit_type',
	  'sql_kind'=>'='
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
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'体系',
	  'width'=>'60px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	'br'=>array(
		'kind'=>'br'
	),
	 'htxmcode'=>array(
	  'kind'=>'htxmcode',
	  'name'=>'htxmcode',
	  'msg'=>'项目编号',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'htxm_id',
	  'sql_kind'=>'='
	)
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();
$sql_temp = $sql_temp." AND audit_type!='1007' AND cw_online='1'";
$params = array(
	'search' => $sql_temp,
	'url' => $url
);
$s = new fees_finance();
$result = $s->listElement($params);

$width = '750px';
include TEMP.'header.htm';
include TEMP.'finance/xm_finance_list.htm';
include TEMP.'footer.htm';

?>