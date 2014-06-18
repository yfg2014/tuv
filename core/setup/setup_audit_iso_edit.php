<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_iso.php');
include(SET_DIR.'setup_mark.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_audit_iso();
	$rst = $s->get_setup($id);
	$rst['mark'] = explode(',',$rst['mark']);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_audit_iso_edit.htm';
include T_DIR.'footer.htm';
?>