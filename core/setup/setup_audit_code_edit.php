<?php
/**
 * 业务分类
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_code.php');
include(SET_DIR.'setup_audit_iso.php');//
include(SET_DIR.'setup_mark.php');//

GrepUtil::InitGP(array('id'));
if ($id) {
	$s = new setup_audit_code();
	$rst = $s->get_setup($id);
	$rst['mark'] = explode(',',$rst['mark']);
}

include T_DIR.'header.htm';
include T_DIR.'setup/setup_audit_code_edit.htm';
include T_DIR.'footer.htm';
?>