<?php
/**
 * 附件类型
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_uploadfile.php');
$fields = array('id','code','msg','isdesc','online');
$value = GrepUtil::InitGP($fields);
$value['quanxian'] = 'L'.$isdesc.'HY'; //权限

unset($value['id']);
$s = new setup_uploadfile();
if ($id) {
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_uploadfile', '保存成功');
?>