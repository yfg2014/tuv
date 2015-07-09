<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_type.php');
GrepUtil::InitGP(array('id'));
$sql_temp = '';

$url = "index.php?m=setup&do=setup_audit_type&";	//翻页地址

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = '20'; //一页显示记录条数

$s = new setup_audit_type();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include TEMP.'header.htm';
include TEMP.'setup/setup_audit_type.htm';
include TEMP.'footer.htm';
?>