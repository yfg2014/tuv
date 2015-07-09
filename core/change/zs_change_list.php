<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Change.php';
include_once S_DIR.'include/module/Certificate.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_certificate_online.php';
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
	 ),
	 'product'=>array(
		'kind'=>'test',
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

$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//SQL
$SearchHtml	= $TopSearch->SearchHtml;
$sql_temp .= Power::xt_htfrom();
if($sp_online == ''){
	$sql_temp = $sql_temp." AND sp_online='0'";
}

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$qy_num = $db->get_one("SELECT COUNT(distinct(zuzhi_id)) AS c_num FROM zs_change WHERE 1 $sql_temp ");
$Change = new Change();
$result = $Change->listChange($params);

$width = '1300px';
include TEMP.'header.htm';
include TEMP.'change/zs_change_list.htm';
include TEMP.'footer.htm';
?>