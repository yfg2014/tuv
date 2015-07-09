<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/TypeChange.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_type_online.php';
include(S_DIR.'include/topsearch.php');
Power::CkPower('F1S');
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
	'ht_id'=>array(
		'kind'=>'htcode',
		'name'=>'ht_id',
		'msg'=>'合同编号',
		'width'=>'95px',
		'arr'=>'',
		'sql_field'=>'ht_id',
		'sql_kind'=>'='
	  ),
	 'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'领域',
	  'width'=>'75px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	's_date'=>array(
		'kind'=>'date1',
		'name'=>'s_date',
		'msg'=>'变更时间',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'xm_change_date',
		'sql_kind'=>'>='
	 ),
	'e_date'=>array(
		'kind'=>'date2',
		'name'=>'e_date',
		'msg'=>'',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'xm_change_date',
		'sql_kind'=>'<='
	 ),
	 'br1'=>array(
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
	 'htfrom'=>array(
		'kind'=>'select',
		'name'=>'htfrom',
		'msg'=>'合同来源',
		'width'=>'75px',
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
	'sp_online'=>array(
		'kind'=>'hidden',
		'name'=>'sp_online',
		'sql_field'=>'sp_online',
		'sql_kind'=>'='
	),
);

$TopSearch = new TopSearch($seach_arr);
//???
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&core='.$core.'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;		//HTML?
$sql_temp .= Power::xt_htfrom();
if($sp_online == ''){
	$sql_temp = $sql_temp." AND sp_online='0'";
}

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$qy_num = $db->get_one("SELECT COUNT(distinct(zuzhi_id)) AS c_num FROM xm_rzlx WHERE 1 $sql_temp ");
$TypeChange = new TypeChange();
$result = $TypeChange->listRzlx($params);

$width = '950px';
include TEMP.'header.htm';
include TEMP.'type/xm_type_list.htm';
include TEMP.'footer.htm';
?>