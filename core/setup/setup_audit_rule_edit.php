<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_rule.php');
include(SET_DIR.'setup_audit_rule.php');
GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_audit_rule();
	$rst = $s->get_setup($id);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_audit_rule_edit.htm';
include T_DIR.'footer.htm';
?>