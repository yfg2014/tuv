<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/OtherCosts.php');
include_once SET_DIR.'setup_other_costs.php';
include(S_DIR.'include/topsearch.php');

Power::CkPower('G3S');

$seach_arr = array(
	'eiregistername'=>array(
	  'kind'=>'company',   //要搜索的类型，企业搜索固定为company
	  'name'=>'eiregistername',
	  'msg'=>'企业名称',
	  'width'=>'80px',
	  'arr'=>'',
	  'sql_field'=>'zuzhi_id',
	  'sql_kind'=>'in'
	),
	'zd_ren1'=>array(
	  'kind'=>'date1',
	  'name'=>'zd_ren1',
	  'msg'=>'登记时间',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zd_ren',
	  'sql_kind'=>'>='
	),
	  'zd_ren2'=>array(
	  'kind'=>'date2',
	  'name'=>'zd_ren2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'zd_ren',
	  'sql_kind'=>'<='
	),
);

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项
$sql_temp .= Power::xt_htfrom();
$params = array(
	'search' => $sql_temp,
	'url' => $url
);

$width='600px';
$OtherCosts = new OtherCosts();
$result = $OtherCosts->listElement($params);

include T_DIR.'header.htm';
include T_DIR.'finance/other_costs_list.htm';
include T_DIR.'footer.htm';
?>
