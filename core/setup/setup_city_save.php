<?php
/**
 * 市县级地址
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_city.php');
$fields = array('id','code','msg','isdesc','online');
$value = GrepUtil::InitGP($fields);

$value['dacode'] = substr($value['code'],0,2).'0000';

$s = new setup_city();
if ($id) {
	unset($value['id']);
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_city', '保存成功');
?>