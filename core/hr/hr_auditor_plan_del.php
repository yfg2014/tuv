<?php

!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Hr_auditor_plan.php');
Power::CkPower('H6D');

GrepUtil::InitGP(array('id'));

$Hr_auditor_plan = new Hr_auditor_plan();
$Hr_auditor_plan->del($id);	

Url::goto_url('index.php?m=hr&do=hr_auditor_plan_list', '删除成功');

?>