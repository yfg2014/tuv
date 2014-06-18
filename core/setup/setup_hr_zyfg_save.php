<?php
/**
 * 专业覆盖
 * @2011-4-27
 *fgxiaolei   覆盖类
 *bfgxiaolei  被覆盖类
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_hr_zyfg.php');
$fields = array('id','code','iso','fgxiaolei','bfgxiaolei','isdesc','online');
$value = GrepUtil::InitGP($fields);

unset($value['id']);
$s = new setup_hr_zyfg();
if ($id) {
	$s->edit_setup($id,$value);
} else {
	$s->add_setup($value);
}
Url::goto_url('index.php?m=setup&do=setup_hr_zyfg', '保存成功');
?>