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

include TEMP.'header.htm';
include TEMP.'setup/setup_audit_iso_edit.htm';
include TEMP.'footer.htm';
?>