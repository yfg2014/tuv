<?php
/**
 * 邮政编码
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_postcode.php');
$fields = array('id','postcode','province','city','area','isdesc','online');
$value = GrepUtil::InitGP($fields);

unset($value['id']);
$s = new setup_postcode();
if ($id) {
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_postcode', '保存成功');
?>