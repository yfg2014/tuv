<?php
/**
 * 实施规则
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_audit_rule.php');
$fields = array('id','code','msg','online');
$value = GrepUtil::InitGP($fields);
$s = new setup_audit_rule();
if ($id) {
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_audit_rule', '保存成功');
?>