<?php
/**
 * 人员合同类型
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_position.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_hr_position();
	$rst = $s->get_setup($id);
}

include TEMP.'header.htm';
include TEMP.'setup/setup_hr_position_edit.htm';
include TEMP.'footer.htm';
?>