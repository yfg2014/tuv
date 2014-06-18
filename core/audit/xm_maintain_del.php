<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';

GrepUtil::InitGP(array('xmid'));
Power::CkPower('C8D');
$Item = new Item();
$del = $Item->delete($xmid);

if($del){
	$msg = '操作成功';
}else{
	$msg = '操作有误';
}

Url::goto_url('index.php?m=audit&do=xm_maintain_list&', $msg);
?>