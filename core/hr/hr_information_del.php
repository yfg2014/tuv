<?php

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');

GrepUtil::InitGP(array('id'));
Power::xt_htfrom($id,'hr');

Power::CkPower('H0E');
$ren = new hr_information();
$ren->del($id);	

Url::goto_url('index.php?m=hr&do=hr_information', '删除成功');

?>