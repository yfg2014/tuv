<?php
/**
 * 部门
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_department.php');
GrepUtil::InitGP(array('id'));
$sql_temp = '';

$url = "index.php?m=setup&do=setup_hr_department&";	//翻页地址

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = '20'; //一页显示记录条数

$s = new setup_hr_department();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include T_DIR.'header.htm';
include T_DIR.'setup/setup_hr_department.htm';
include T_DIR.'footer.htm';
?>