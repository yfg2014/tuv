<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/InformationRelease.php';
Power::CkPower('J4E');
GrepUtil::InitGP(array('id'));

$InformationRelease = new InformationRelease();
$InformationRelease->delete($id);

Url::goto_url('index.php?m=hr&do=sys_sms_list2', '操作成功');
?>