<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_finance_item.php');

GrepUtil::InitGP(array('id','page'));
$sql_temp = '';

$url = "index.php?m=setup&do=setup_finance_item&";//翻页地址

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = '20'; //一页显示记录条数

$s = new setup_finance_item();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include TEMP.'header.htm';
include TEMP.'setup/setup_finance_item.htm';
include TEMP.'footer.htm';
?>