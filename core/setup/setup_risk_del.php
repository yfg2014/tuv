<?php
/**
 * 风险等级
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_risk.php');
GrepUtil::InitGP(array('id'));
$s = new setup_risk();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_risk', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_risk', '错误的删除对象');
}

?>