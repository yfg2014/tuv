<?php
/**
 * Ͽɱ־
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_key_part.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_key_part();
	$rst = $s->get_setup($id);
	
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_key_part_edit.htm';
include T_DIR.'footer.htm';
?>