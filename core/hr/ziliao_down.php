<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/HrZiliao.php');

GrepUtil::InitGP(array('fid'));

$HrZiliao = new ZiliaoDao();;
$HrZiliao->down((int)$fid);
exit;
?>