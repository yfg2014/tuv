<?php

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_reg_qualification.php');

GrepUtil::InitGP(array('id','hr_id'));
Power::CkPower('H1E');
$ren = new Hr_reg_qualification();
Power::xt_htfrom($id,'hr');
$ren->del($id);	
LogRW::logWriter('', '删除人员'.Cache::cache_username($hr_id).'注册资格');
?>