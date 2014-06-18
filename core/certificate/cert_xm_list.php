<?php
!defined('IN_SUPU') && exit('Forbidden');
//include_once S_DIR.'include/module/ContractAssessmentItem.php';
include_once S_DIR.'/include/module/AssessmentItem.php';
include_once S_DIR.'include/module/Certificate.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
Power::CkPower('E0S');

$width = '950px';
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
	'audit_type'=>array(
	  'kind'=>'select',
	  'name'=>'audit_type',
	  'msg'=>'审核类型',
	  'width'=>'104px',
	  'arr'=>$setup_audit_type,
	  'sql_field'=>'audit_type',
	  'sql_kind'=>'='
	),
	'zl_okdate1'=>array(
	  'kind'=>'date1',
	  'name'=>'zl_okdate1',
	  'msg'=>'资料收回时间',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zl_okdate',
	  'sql_kind'=>'>='
	),
	  'zl_okdate2'=>array(
	  'kind'=>'date2',
	  'name'=>'zl_okdate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zl_okdate',
	  'sql_kind'=>'<='
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'80px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
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

	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'认证体系',
	  'width'=>'70px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'iso',
	  'sql_kind'=>'='
	),
	  'approvaldate1'=>array(
	  'kind'=>'date1',
	  'name'=>'approvaldate1',
	  'msg'=>'评定通过时间',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'approvaldate',
	  'sql_kind'=>''
	),
	  'approvaldate2'=>array(
	  'kind'=>'date2',
	  'name'=>'approvaldate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'approvaldate',
	  'sql_kind'=>''
	),
	'online'=>array(
	  'kind'=>'hidden',
	  'name'=>'online'
	),
	'br3'=>array(
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
  ),
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();

$sql_temp = " AND zsprintdate='0000-00-00'".$sql_temp;

if($product!='')
{
	$sql_temp = $sql_temp." AND  coverFields LIKE '%{$product}%'";
}
if($zl_okdate1 != '' && $zl_okdate2 != '')
{
	$sql_temp = $sql_temp." AND xmid IN(SELECT id FROM {$dbtable['xm_item']} WHERE zl_okdate>='$zl_okdate1' AND zl_okdate<='$zl_okdate2')";
}
if($approvaldate1 != '' && $approvaldate2 != '')
{
	$sql_temp = $sql_temp." AND pdid IN(SELECT id FROM {$dbtable['pd_xm']} WHERE approvaldate>='$approvaldate1' AND approvaldate<='$approvaldate2')";
}

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$Certificate = new Certificate();
$result = $Certificate->listCertification($params);

include T_DIR.'header.htm';
include T_DIR.'certificate/cert_xm_list.htm';
include T_DIR.'footer.htm';
?>