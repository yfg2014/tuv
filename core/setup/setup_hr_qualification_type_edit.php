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
if ($id) {
	$s = new setup_hr_qualification_type();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_hr_qualification_type_edit.htm';
include T_DIR.'footer.htm';
?>