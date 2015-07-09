<?php
include_once S_DIR.'/include/module/Demand.php';
include_once S_DIR.'/include/setup/setup_law_uploadfile.php';
include_once S_DIR.'/conf/menu.inc.php';
include(S_DIR.'include/topsearch.php');


$width='550px';

$seach_arr = array(
	'menu'=>array(
	  'kind'=>'select',   //要搜索的类型
	  'name'=>'menu',
	  'msg'=>'需求分类',
	  'width'=>'104px',
	  'arr'=>$menu_arr,
	  'sql_field'=>'menu',
	  'sql_kind'=>'='
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

$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml = $TopSearch->SearchHtml;		//构造搜索HTML表单项

$params = array(
	'search' => $sql_temp,
	'url' => $url
);

$Demand = new Demand();
$result = $Demand->listElement($params);

include TEMP.'header.htm';
include TEMP.'demand/demand_list.htm';
include TEMP.'footer.htm';
?>