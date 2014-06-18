<?php
/**
 * 资格类别
 * @2011-5-12
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_qualification_type.php');
GrepUtil::InitGP(array('id'));
$s = new setup_hr_qualification_type();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_hr_qualification_type', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_hr_qualification_type', '错误的删除对象');
}

?>