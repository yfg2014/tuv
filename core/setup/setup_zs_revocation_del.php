<?php
/**
 * 证书撤销原因
 * @2011-4-27
 *
 *
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/setup_zs_revocation.php');
GrepUtil::InitGP(array('id'));
$s = new setup_zs_revocation();
if ($id) {
	$s->del_setup($id);
	Url::goto_url('index.php?m=setup&do=setup_zs_revocation', '删除成功');
} else {
	Url::goto_url('index.php?m=setup&do=setup_zs_revocation', '错误的删除对象');
}

?>