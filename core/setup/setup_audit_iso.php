<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_iso.php');
//print_r($dbtable);exit;
$sql_temp = '';

$url = "index.php?m=setup&do=setup_audit_iso&";//翻页地址

$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$db_perpage = '50'; //一页显示记录条数

$s = new setup_audit_iso();
$result = $s->list_setup($params);

//页面设置
$width = '1000px';
include TEMP.'header.htm';
include TEMP.'setup/setup_audit_iso.htm';
include TEMP.'footer.htm';
?>