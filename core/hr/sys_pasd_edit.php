<?php
/**
 * 修改密码
 * @2011-5-27
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');

if ($_SESSION['userid']) {
	$ren	= new hr_information();
	$value	= $ren->query($_SESSION['userid']);

	$url = "index.php?m=hr&do=sys_pasd_edit&id=$id&";
	$params = array(
		'search' => " and id=$id",
		'url' => $url,
	);
}

$width='500px';

include T_DIR.'header.htm';
include T_DIR.'hr/sys_pasd_edit.htm';
include T_DIR.'footer.htm';
?>