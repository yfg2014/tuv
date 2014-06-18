<?php
/**
 * 认可标志
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_key_part.php');
GrepUtil::InitGP(array('id'));
$s = new setup_key_part();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_key_part', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_key_part', '错误的删除对象');
}

?>