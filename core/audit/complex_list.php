<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Complex.php';
include(S_DIR.'include/topsearch.php');
include_once S_DIR.'include/setup/setup_htfrom.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include SET_DIR.'setup_province.php';

Power::CkPower('C9S');//再认证维护查询
$width = '1350px';
$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'a.zuzhi_id',
	  'sql_kind'=>'in'
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'104px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'a.htfrom',
	  'sql_kind'=>'='
	),
	'actualtaskEndDate1'=>array(
	  'kind'=>'date1',
	  'name'=>'actualtaskEndDate1',
	  'msg'=>'实际结束日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'a.actualtaskEndDate',
	  'sql_kind'=>'>='
	),
	  'actualtaskEndDate2'=>array(
	  'kind'=>'date2',
	  'name'=>'actualtaskEndDate2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'a.actualtaskEndDate',
	  'sql_kind'=>'<='
	),
		'eiarea'=>array(
	  'kind'=>'eiarea',
	  'name'=>'eiarea',
	  'msg'=>'所在地区',
	  'width'=>'100px',
	  'arr'=>$setup_province,
	  'sql_field'=>'a.zuzhi_id',
	  'sql_kind'=>'in'
	),

	  'zd_date1'=>array(
	  'kind'=>'date1',
	  'name'=>'zd_date1',
	  'msg'=>'制单日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'a.zd_date',
	  'sql_kind'=>'>='
	),
	  'zd_date2'=>array(
	  'kind'=>'date2',
	  'name'=>'zd_date2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'a.zd_date',
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
		'sql_field'=>'a.htxm_id',
		'sql_kind'=>'in'
	  ),
	'htcode'=>array(
	  'kind'=>'htcode',
	  'name'=>'htcode',
	  'msg'=>'合同编号',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'a.ht_id',
	  'sql_kind'=>'='
	),

	  'zsFinallyDate1'=>array(
	  'kind'=>'days',
	  'name'=>'zsFinallyDate1',
	  'msg'=>'证书到期日期',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'a.zsFinallyDate',
	  'sql_kind'=>''
	),
	'iso'=>array(
	  'kind'=>'select',
	  'name'=>'iso',
	  'msg'=>'认证体系',
	  'width'=>'100px',
	  'arr'=>$setup_audit_iso,
	  'sql_field'=>'a.iso',
	  'sql_kind'=>'='
	),
	'iffuping'=>array(
	  'kind'=>'hidden',
	  'name'=>'iffuping',
	  'msg'=>'是否复评',
	  'width'=>'60px',
	  'arr'=>array('2'=>'待定','1'=>'接受','0'=>'不接受'),
	  'sql_field'=>'a.iffuping',
	  'sql_kind'=>'='
	),
	'zd_ren'=>array(
	  'kind'=>'text',
	  'name'=>'zd_ren',
	  'msg'=>'制单人',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'a.zd_ren',
	  'sql_kind'=>'='
	),
	'br2'=>array(
	'kind'=>'br'
	),
	'daoqi'=>array(
		'kind'=>'days',
		'name'=>'daoqi',
		'msg'=>'第一次审核实际结束时间',
		'width'=>'92px',
		'arr'=>'',
		'sql_field'=>'a.s_audit_date',
		'sql_kind'=>''
	 ),
);
$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp  .= Power::xt_htfrom(0,1,'a.');

$params = array(
	'search' => $sql_temp,
	'page' => $page,
	'url' => $url,
);

$Complex = new Complex();
$result = $Complex->listSetup($params);

include TEMP.'header.htm';
include TEMP.'audit/complex_list.htm';
include TEMP.'footer.htm';
?>