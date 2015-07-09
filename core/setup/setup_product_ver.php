<?php
/**
 * 产品标准
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_product_ver.php');
include(S_DIR.'include/topsearch.php');
GrepUtil::InitGP(array('id'));
$sql_temp = '';
$seach_arr = array(
	//每个表单控件NAME值作为KEY
	'code'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'code',
	  'msg'=>'编码',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'code',
	  'sql_kind'=>'%like%'
	),
	'product'=>array(
	  'kind'=>'product',   //要搜索的类型，企业搜索固定为company
	  'name'=>'product',
	  'msg'=>'产品',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'product',
	  'sql_kind'=>'%like%'
	),
	'msg'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'msg',
	  'msg'=>'标准',
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

$db_perpage = '20'; //一页显示记录条数

$s = new setup_product_ver();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include TEMP.'header.htm';
include TEMP.'setup/setup_product_ver.htm';
include TEMP.'footer.htm';
?>