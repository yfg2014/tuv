<?php
/**
 * 证书撤销原因
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_zs_revocation.php');
$fields = array('id','code','msg','isdesc','online');
$value = GrepUtil::InitGP($fields);

unset($value['id']);
$s = new setup_zs_revocation();
if ($id) {
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_zs_revocation', '保存成功');
?>