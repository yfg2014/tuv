<?php
/**
 * 学历信息
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_education.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_hr_education();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_hr_education_edit.htm';
include T_DIR.'footer.htm';
?>