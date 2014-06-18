<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_type.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_audit_type();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_audit_type_edit.htm';
include T_DIR.'footer.htm';
?>