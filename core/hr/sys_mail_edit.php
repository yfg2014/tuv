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

}

$width='500px';

include TEMP.'header.htm';
include TEMP.'hr/sys_mail_edit.htm';
include TEMP.'footer.htm';
?>