<?php
/**
 * 人员职称
 * @2011-5-5
 */


!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_profession.php');			//人员职称类 审核代码

GrepUtil::InitGP(array('id','hr_id'));

$ren = new Hr_profession();
Power::CkPower('H0E');
Power::xt_htfrom($id,'hr');
$ren->del($id);	
LogRW::logWriter('', '删除人员'.Cache::cache_username($hr_id).'职称信息');
?>