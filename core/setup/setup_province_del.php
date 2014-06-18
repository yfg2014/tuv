<?php
/**
 * 省级地址
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_province.php');
GrepUtil::InitGP(array('id'));
$s = new setup_province();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_province', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_province', '错误的删除对象');
}

?>