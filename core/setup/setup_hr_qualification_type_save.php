<?php
/**
 * 资格类别
 * @2011-5-12
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_qualification_type.php');
$fields = array('id','code','msg','isdesc','online');
$value = GrepUtil::InitGP($fields);

unset($value['id']);
$s = new setup_hr_qualification_type();
if ($id) {
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_hr_qualification_type', '保存成功');
?>