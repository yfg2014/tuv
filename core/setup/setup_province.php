<?php
/**
 * 省级地址
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_province.php');
GrepUtil::InitGP(array('id','page'));
$sql_temp = '';

$url = "index.php?m=setup&do=setup_province&";	//翻页地址

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = '50'; //一页显示记录条数

$s = new setup_province();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include TEMP.'header.htm';
include TEMP.'setup/setup_province.htm';
include TEMP.'footer.htm';
?>