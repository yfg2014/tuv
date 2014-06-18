<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Companyfinance.php');
include(S_DIR.'include/topsearch.php');

Power::CkPower('A2C');

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
	'get_money_date1'=>array(
	  'kind'=>'date1',
	  'name'=>'get_money_date1',
	  'msg'=>'到款时间',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'get_money_date',
	  'sql_kind'=>'>='
	),
	  'get_money_date2'=>array(
	  'kind'=>'date2',
	  'name'=>'get_money_date2',
	  'msg'=>'',
	  'width'=>'70px',
	  'arr'=>'',
	  'sql_field'=>'get_money_date',
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
$sql_temp .= Power::CpPower(1);

$params = array(
	'search' => $sql_temp,
	'url' => $url
);

$width='800px';
$Companyfinance = new Companyfinance();
$result = $Companyfinance->listElement($params);

include T_DIR.'header.htm';
include T_DIR.'company/qiyedengji_finance_list.htm';
include T_DIR.'footer.htm';
?>
