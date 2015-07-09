<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Certificate.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
Power::CkPower('E3S');
$width = '1000px';
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
	'certNo'=>array(
	  'kind'=>'text',
	  'name'=>'certNo',
	  'msg'=>'证书编号',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'certNo',
	  'sql_kind'=>'='
	),
	'zs_change_date1'=>array(
	  'kind'=>'date1',
	  'name'=>'zs_change_date1',
	  'msg'=>'变更日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zs_change_date',
	  'sql_kind'=>'>='
	),
	  'zs_change_date2'=>array(
	  'kind'=>'date2',
	  'name'=>'zs_change_date2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zs_change_date',
	  'sql_kind'=>'<='
	),
	'certStart1'=>array(
	  'kind'=>'date1',
	  'name'=>'certStart1',
	  'msg'=>'注册日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'certStart',
	  'sql_kind'=>'>='
	),
	  'certStart2'=>array(
	  'kind'=>'date2',
	  'name'=>'certStart2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'certStart',
	  'sql_kind'=>'<='
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

	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'84px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),

	  'zs_zanting_edate1'=>array(
	  'kind'=>'date1',
	  'name'=>'zs_zanting_edate1',
	  'msg'=>'暂停到期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zs_zanting_edate',
	  'sql_kind'=>'>='
	),
	  'zs_zanting_edate2'=>array(
	  'kind'=>'date2',
	  'name'=>'zs_zanting_edate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zs_zanting_edate',
	  'sql_kind'=>'<='
	),
	  'certEnd1'=>array(
	  'kind'=>'date1',
	  'name'=>'certEnd1',
	  'msg'=>'注册到期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'certEnd',
	  'sql_kind'=>'>='
	),
	  'certEnd2'=>array(
	  'kind'=>'date2',
	  'name'=>'certEnd2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'certEnd',
	  'sql_kind'=>'<='
	),
		'br2'=>array(
	'kind'=>'br'
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
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();
$today = date("Y-m-d");
$sql_temp = " and online='03' and zs_zanting_edate<='{$today}'".$sql_temp;
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);
$Certificate = new Certificate();
$result = $Certificate->listCertification($params);

include TEMP.'header.htm';
include TEMP.'certificate/should_withdraw_list.htm';
include TEMP.'footer.htm';
?>