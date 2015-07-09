<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Certificate.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_certificate_online.php';
include_once S_DIR.'include/setup/setup_mark.php';
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include(S_DIR.'include/topsearch.php');
Power::CkPower('F0S');
$seach_arr=array(
	 'eiregistername'=>array(
	  'kind'=>'company',
	  'name'=>'eiregistername',
	  'msg'=>'组织名称',
	  'width'=>'75px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	 's_date1'=>array(
		'kind'=>'date1',
		'name'=>'s_date1',
		'msg'=>'注册日期',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'certStart',
		'sql_kind'=>'>='
	 ),
	'e_date1'=>array(
		'kind'=>'date2',
		'name'=>'e_date1',
		'msg'=>'',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'certStart',
		'sql_kind'=>'<='
	 ),

	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'领域',
	  'width'=>'74px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),

	'online'=>array(
		'kind'=>'select',
		'name'=>'online',
		'msg'=>'证书状态',
		'width'=>'75px',
		'arr'=>$setup_certificate_online,
		'sql_field'=>'online ',
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
	 'br'=>array(
		'kind'=>'br'
	 ),
	'htxmcode'=>array(
	  'kind'=>'htxmcode',
	  'name'=>'htxmcode',
	  'msg'=>'项目编号',
	  'width'=>'75px',
	  'arr'=>'',
	  'sql_field'=>'htxm_id',
	  'sql_kind'=>'='
	),
	 'certNo'=>array(
		'kind'=>'text',
		'name'=>'certNo',
		'msg'=>'证书编号',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'certNo',
		'sql_kind'=>'='
	  ),

	 's_date3'=>array(
		'kind'=>'date1',
		'name'=>'s_date3',
		'msg'=>'注册到期',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'certEnd',
		'sql_kind'=>'>='
	 ),
	'e_date3'=>array(
		'kind'=>'date2',
		'name'=>'e_date3',
		'msg'=>'',
		'width'=>'75px',
		'arr'=>'',
		'sql_field'=>'certEnd',
		'sql_kind'=>'<='
	 ),
    'htfrom'=>array(
	  'kind'=>'select',
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'80px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
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

$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&core='.$core.'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;
$SearchHtml	= $TopSearch->SearchHtml;
$sql_temp .= Power::xt_htfrom();
$sql_temp = "and (online='01' or online='04' or online='03') and zsprintdate!='0000-00-00' and certNo!=''".$sql_temp;
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Certificate = new Certificate();
$result = $Certificate->listCertification($params);

$width = '850px';
include TEMP.'header.htm';
include TEMP.'change/change_list.htm';
include TEMP.'footer.htm';
?>