<?php
!defined('IN_SUPU') && exit('Forbidden');

include(S_DIR.'include/module/Company.php');
include(S_DIR.'include/module/Contract.php');
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_htfrom.php';
include SET_DIR.'setup_province.php';

Power::CkPower('B0S');

$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'id',
	  'sql_kind'=>'in'
	),
	'eidaima'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eidaima',
	  'msg'=>'组织机构代码',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'eidaima',
	  'sql_kind'=>'='
	),
	'htfrom'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'htfrom',
	  'msg'=>'合同来源',
	  'width'=>'100px',
	  'arr'=>$setup_htfrom,
	  'sql_field'=>'htfrom',
	  'sql_kind'=>'='
	),
	'province'=>array(
	  'kind'=>'select',  
	  'name'=>'province',
	  'msg'=>'省份地址',
	  'width'=>'100px',
	  'arr'=>$setup_province,
	  'sql_field'=>'eiarea_code',
	  'sql_kind'=>'='
	),
	  'eilinkman'=>array(
	  'kind'=>'text',
	  'name'=>'eilinkman',
	  'msg'=>'联 系 人',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'eilinkman',
	  'sql_kind'=>'%like%'
	),
	'br'=>array(
	    'kind'=>'br'
	 ),
    'zd_ren'=>array(
	  'kind'=>'text',
	  'name'=>'zd_ren',
	  'msg'=>'登记人',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zd_ren',
	  'sql_kind'=>'like%'
	),
	's_date'=>array(
	  'kind'=>'date1',   //要搜索的类型
	  'name'=>'s_date',
	  'msg'=>'登记日期',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zd_time',
	  'sql_kind'=>'>='
	),
	'e_date'=>array(
	  'kind'=>'date2',  
	  'name'=>'e_date',
	  'msg'=>'',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'zd_time',
	  'sql_kind'=>'<='
	)
);
$TopSearch = new TopSearch($seach_arr);

//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;

$sql_temp	= $TopSearch->SearchSql;	//构造搜索SQL
$SearchHtml = $TopSearch->SearchHtml;	//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();
$sql_temp .= Power::CpPower();
$sql_temp .= ' and fatherzuzhi_id  = 0';

$params = array(
	'search' => $sql_temp,
	'url' => $url
);

//$db_perpage = '2';
$width='900px';

$Company = new Company();
$result = $Company->listElement($params);

include TEMP.'header.htm';
include TEMP.'contract/contract_registration.htm';
include TEMP.'footer.htm';
?>