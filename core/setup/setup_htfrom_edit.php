<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_htfrom.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_htfrom();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_htfrom_edit.htm';
include T_DIR.'footer.htm';
?>