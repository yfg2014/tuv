<?php
/**
 * 附件类型
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_law_uploadfile.php');
GrepUtil::InitGP(array('id'));
$s = new setup_law_uploadfile();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_law_uploadfile', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_law_uploadfile', '错误的删除对象');
}

?>