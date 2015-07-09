<?php
/**
 * 资格类别
 * @2011-5-12
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_qualification_type.php');
GrepUtil::InitGP(array('id'));
$sql_temp = '';

$url = "index.php?m=setup&do=setup_hr_qualification_type&";	//翻页地址

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = '20'; //一页显示记录条数

$s = new setup_hr_qualification_type();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include TEMP.'header.htm';
include TEMP.'setup/setup_hr_qualification_type.htm';
include TEMP.'footer.htm';
?>