<?php
/**
 * 审核员组内身份
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_role.php');
GrepUtil::InitGP(array('id'));
$s = new setup_role();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_role', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_role', '错误的删除对象');
}

?>