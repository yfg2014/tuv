<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_orgtype.php');
GrepUtil::InitGP(array('id'));
$s = new setup_orgtype();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_orgtype', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_orgtype', '错误的删除对象');
}

?>