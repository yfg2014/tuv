<?php
/**
 * 业务分类
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_code.php');
$fields = array('id','code','iso','mark','msg','shangbao','jianzhengdate','pizhundate','eientercode','pingjiadate','specrequire_cn','specrequire_en','other','online');
$value = GrepUtil::InitGP($fields);
$value['mark'] = implode(',',$value['mark']);

unset($value['id']);
$s = new setup_audit_code();
if ($id) {
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_audit_code', '保存成功');
?>