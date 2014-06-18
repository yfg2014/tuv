<?php
/**
 * 市县级地址
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_city.php');
GrepUtil::InitGP(array('id'));
$s = new setup_city();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_city', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_city', '错误的删除对象');
}

?>