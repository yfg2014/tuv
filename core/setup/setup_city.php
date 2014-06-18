<?php
/**
 * 市县级地址
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/topsearch.php');
include(S_DIR.'include/module/setup_city.php');
GrepUtil::InitGP(array('id','page'));
$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'code'=>array(
	  'kind'=>'text',   
	  'name'=>'code',
	  'msg'=>'编码',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'code',
	  'sql_kind'=>'%like%'
	),
	'msg'=>array(
	  'kind'=>'text', 
	  'name'=>'msg',
	  'msg'=>'内容',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'msg',
	  'sql_kind'=>'%like%'
	),
);

$TopSearch = new TopSearch($seach_arr);
//构造翻页地址
$baseurl	= 'index.php?m='.$TopSearch->SearchName['m'].'&do='.$TopSearch->SearchName['do'].'&';
$url		= $baseurl.$TopSearch->SearchUrl;
$sql_temp	= $TopSearch->SearchSql;		//构造搜索SQL
$SearchHtml	= $TopSearch->SearchHtml;		//构造搜索HTML表单项

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = '50'; //一页显示记录条数

$s = new setup_city();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include T_DIR.'header.htm';
include T_DIR.'setup/setup_city.htm';
include T_DIR.'footer.htm';
?>