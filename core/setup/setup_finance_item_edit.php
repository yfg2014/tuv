<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_finance_item.php');
include(SET_DIR.'setup_mark.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_finance_item();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_finance_item_edit.htm';
include T_DIR.'footer.htm';
?>