<?php
/**
 * ֤����ͣ
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_zs_stop.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_zs_stop();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_zs_stop_edit.htm';
include T_DIR.'footer.htm';
?>