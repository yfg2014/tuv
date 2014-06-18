<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/Certificate.php';
GrepUtil::InitGP(array('id','mailother','op'));

$params = array(
	'mailother' => $mailother,
);

if ($op == '1'){
	$Certificate = new Certificate();
	$Certificate->save($id, $params);
	
	Url::goto_url('index.php?m=certificate&do=cert_post', '保存成功');
}elseif ($op == '2'){
	$Item = new Item();
	$Item->update($id, $params);
	
	Url::goto_url('index.php?m=certificate&do=prove_post', '保存成功');
}
?>