<?php
/**
 * 人员专业能力
 * @2011-5-5
 */


!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_audit_code.php');			//人员专业能力类 审核代码
Power::CkPower('H1E');
GrepUtil::InitGP(array('id','hr_id'));

$ren = new Hr_audit_code();
$ren->del($id);	
LogRW::logWriter('', '删除人员'.Cache::cache_username($hr_id).'专业代码');
?>