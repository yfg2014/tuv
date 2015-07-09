<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include(S_DIR.'include/module/HrZiliao.php');
include(S_DIR.'/include/setup/setup_hr_uploadfile.php');

GrepUtil::InitGP(array('hr_id'));
$width='600px';
if ($hr_id) {
	$ren	= new hr_information();
	$value	= $ren->query($hr_id);
	$power = explode(',',$value['power']);
	
	$renUpload= new ZiliaoDao();
	$zi=$renUpload->query($hr_id);
}

include TEMP.'header.htm';
include TEMP.'hr/ziliao_edit.htm';
include TEMP.'footer.htm';
?>