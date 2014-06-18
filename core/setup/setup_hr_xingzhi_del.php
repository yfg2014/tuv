<?php
/**
 * 人员性质
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_xingzhi.php');
GrepUtil::InitGP(array('id'));
$s = new setup_hr_xingzhi();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_hr_xingzhi', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_hr_xingzhi', '错误的删除对象');
}

?>