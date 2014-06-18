<?php
/**
 * 产品
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_product.php');
include(S_DIR.'include/topsearch.php');
GrepUtil::InitGP(array('id'));

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
	'msg_s'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'msg_s',
	  'msg'=>'简称',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'msg_s',
	  'sql_kind'=>'%like%'
	),
	'msg'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'msg',
	  'msg'=>'产品',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'msg',
	  'sql_kind'=>'%like%'
	),
	'rules'=>array(
	  'kind'=>'text',   //要搜索的类型，企业搜索固定为company
	  'name'=>'rules',
	  'msg'=>'实施规则',
	  'width'=>'100px',
	  'arr'=>'',
	  'sql_field'=>'rules',
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

$s = new setup_product();
$result = $s->list_setup($params);

//页面设置
$width = '800px';
include T_DIR.'header.htm';
include T_DIR.'setup/setup_product.htm';
include T_DIR.'footer.htm';
?>