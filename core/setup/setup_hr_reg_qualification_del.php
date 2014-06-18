<?php
/**
 * 审核员注册级别
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_reg_qualification.php');
GrepUtil::InitGP(array('id'));
$s = new setup_hr_reg_qualification();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_hr_reg_qualification', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_hr_reg_qualification', '错误的删除对象');
}

?>