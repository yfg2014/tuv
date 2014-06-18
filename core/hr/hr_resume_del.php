<?php
/**
 * 人员简历
 * @2011-5-5
 */


!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_resume.php');			//人员简历类 审核代码

Power::CkPower('H0E');
GrepUtil::InitGP(array('id','hr_id'));

$ren = new Hr_resume();
$ren->del($id);	
LogRW::logWriter('', '删除人员'.Cache::cache_username($hr_id).'简历信息');
?>