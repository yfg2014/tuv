<?php
/**
 * 人员学历
 * @2011-5-5
 */


!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_education.php');			//人员学历类 审核代码

GrepUtil::InitGP(array('id','hr_id'));

$ren = new Hr_education();

Power::CkPower('H0E');
Power::xt_htfrom($id,'hr');
$ren->del($id);	
LogRW::logWriter('', '删除人员'.Cache::cache_username($hr_id).'学历信息');
?>