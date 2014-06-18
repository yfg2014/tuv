<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_finance_item.php');
GrepUtil::InitGP(array('id'));
$s = new setup_finance_item();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_finance_item', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_finance_item', '错误的删除对象');
}

?>