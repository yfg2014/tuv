<?php
/**
 * 组织性质
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_orgtype.php');
$fields = array('id','code','msg','isdesc','online');
$value = GrepUtil::InitGP($fields);

unset($value['id']);
$s = new setup_orgtype();
if ($id) {
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_orgtype', '保存成功');
?>