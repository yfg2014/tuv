<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_rule.php');
GrepUtil::InitGP(array('id'));
$s = new setup_audit_rule();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_audit_rule', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_audit_rule', '错误的删除对象');
}

?>