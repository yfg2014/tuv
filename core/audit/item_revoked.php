<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';

GrepUtil::InitGP(array('xmid','zuzhi_id'));
$Item = new Item();
$xm = $Item->query($xmid,array('online'));
if($xm['online'] == '0'){
	$Item->revoked($xmid,$zuzhi_id);
	Url::goto_url('./index.php?m=audit&do=xm_no_list', '操作成功');
}else{
	Url::goto_url('./index.php?m=audit&do=xm_no_list', '项目已安排，操作失败');
}
?>