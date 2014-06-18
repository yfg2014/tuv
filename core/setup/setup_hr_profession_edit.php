<?php
/**
 * хкт╠ж╟Ёф
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_profession.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_hr_profession();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_hr_profession_edit.htm';
include T_DIR.'footer.htm';
?>