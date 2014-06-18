<?php
/**
 * уЧжнцФц╡
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_politics.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_hr_politics();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_hr_politics_edit.htm';
include T_DIR.'footer.htm';
?>