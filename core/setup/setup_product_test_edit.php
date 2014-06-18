<?php
/**
 * 检测机构
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_product_test.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_product_test();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_product_test_edit.htm';
include T_DIR.'footer.htm';
?>