<?php
/**
 * 权限编辑
 * @2011-5-5
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include_once S_DIR.'/include/setup/setup_uploadfile.php';

Power::CkPower('J1E');
GrepUtil::InitGP(array('hr_id'));

foreach($setup_uploadfile as $v){
	if ($v['online'] != '0') {
		$menu_setup['composite'][$v['quanxian']] = $v['msg'];		
	}
}

$width='480px';
if ($hr_id) {
	$ren	= new hr_information();
	$value	= $ren->query($hr_id);
	$power = explode(',',$value['power']);
}

//$db->query("UPDATE hr_information SET power='$main_power_temp' WHERE id='1'");
include TEMP.'header.htm';
include TEMP.'hr/sys_user_edit.htm';
include TEMP.'footer.htm';
?>