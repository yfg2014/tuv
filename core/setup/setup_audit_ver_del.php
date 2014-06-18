<?php
/**
 * 体系标准
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_ver.php');
GrepUtil::InitGP(array('id'));
$s = new setup_audit_ver();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_audit_ver', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_audit_ver', '错误的删除对象');
}

?>