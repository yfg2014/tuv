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

include TEMP.'header.htm';
include TEMP.'setup/setup_key_part_edit.htm';
include TEMP.'footer.htm';
?>