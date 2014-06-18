<?php
include_once S_DIR.'/include/module/Company.php';
include(S_DIR.'include/topsearch.php');
include SET_DIR.'setup_htfrom.php';
include SET_DIR.'setup_province.php';


Power::CkPower('A0S');
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
	)
);
$TopSearch = new TopSearch($seach_arr);

//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;

//构造搜索SQL
$sql_temp	= $TopSearch->SearchSql;
//构造搜索HTML表单项
$SearchHtml = $TopSearch->SearchHtml;
$sql_temp .= Power::xt_htfrom();
$sql_temp .= Power::CpPower();

$params = array(
	'search' => $sql_temp,
	'url' => $url
);

$width='750px';
$Company = new Company();
$result = $Company->listElement($params);

include T_DIR.'header.htm';
include T_DIR.'company/qiyedengji_complaint.htm';
include T_DIR.'footer.htm';
?>