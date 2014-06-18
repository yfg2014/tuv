<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_htfrom.php');
GrepUtil::InitGP(array('id','page'));
$sql_temp = '';

$url = "index.php?m=setup&do=setup_htfrom&";	//翻页地址
$sql_temp .= " AND code!='1000' ";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = '20'; //一页显示记录条数

$s = new setup_htfrom();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include T_DIR.'header.htm';
include T_DIR.'setup/setup_htfrom.htm';
include T_DIR.'footer.htm';
?>