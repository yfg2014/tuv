<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_type.php');
GrepUtil::InitGP(array('id'));
$s = new setup_audit_type();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_audit_type', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_audit_type', '错误的删除对象');
}

?>