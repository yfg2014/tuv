<?php
/**
 * Ͽɱ־
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_product_ver.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_product_ver();
	$rst = $s->get_setup($id);
}

include TEMP.'header.htm';
include TEMP.'setup/setup_product_ver_edit.htm';
include TEMP.'footer.htm';
?>