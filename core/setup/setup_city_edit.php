<?php
/**
 * 市县级地址
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_city.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_city();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_city_edit.htm';
include T_DIR.'footer.htm';
?>