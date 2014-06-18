<?php
/**
 * 认可标志
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_mark.php');
GrepUtil::InitGP(array('id'));
$s = new setup_mark();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_mark', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_mark', '错误的删除对象');
}

?>