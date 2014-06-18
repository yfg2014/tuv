<?php
/**
 * 认证领域
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_iso.php');
$fields = array('id','code','msg','msg_cn','mark','isdesc','online');
$value = GrepUtil::InitGP($fields);
$value['mark'] = implode(',',$value['mark']);
unset($value['id']);
$s = new setup_audit_iso();
if ($id) {
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_audit_iso', '保存成功');
?>