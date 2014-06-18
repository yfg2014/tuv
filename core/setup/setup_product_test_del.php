<?php
/**
 * 检测机构
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_product_test.php');
GrepUtil::InitGP(array('id'));
$s = new setup_product_test();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_product_test', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_product_test', '错误的删除对象');
}

?>