<?php
/**
 * 业务分类
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_code.php');
GrepUtil::InitGP(array('id'));
$s = new setup_audit_code();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_audit_code', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_audit_code', '错误的删除对象');
}

?>