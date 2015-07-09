<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_orgtype.php');
GrepUtil::InitGP(array('id'));
$sql_temp = '';

$url = "index.php?m=setup&do=setup_orgtype&";	//翻页地址

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = '20'; //一页显示记录条数

$s = new setup_orgtype();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include TEMP.'header.htm';
include TEMP.'setup/setup_orgtype.htm';
include TEMP.'footer.htm';
?>