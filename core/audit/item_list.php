<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
GrepUtil::InitGP(array('s_online'));

Power::CkPower('C4S');//
$width='1000px';
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
	'htcode'=>array(
	  'kind'=>'htcode',
	  'name'=>'htcode',
	  'msg'=>'合同编号',
	  'width'=>'100px',
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
	 'taskBeginDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'taskBeginDate1',
	  'msg'=>'审核开始日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'taskBeginDate',
	  'sql_kind'=>'>='
	),
	  'taskBeginDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'taskBeginDate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'taskBeginDate',
	  'sql_kind'=>'<='
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

	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'104px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
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
	  'assessmentdate1'=>array(
	  'kind'=>'date1',
	  'name'=>'assessmentdate1',
	  'msg'=>'评定通过日期',
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
		'br2'=>array(
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
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

$sql_temp  .= Power::xt_htfrom();
$sql_temp = " and id!=''".$sql_temp;
if($product!='')
{
	$sql_temp = $sql_temp." AND product in( SELECT code FROM setup_product WHERE msg LIKE '%$product%')";
}
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Item = new Item();
$result = $Item->listElement($params);

include TEMP.'header.htm';
include TEMP.'audit/item_list.htm';
include TEMP.'footer.htm';
?>