<?php
/**
 * 添加、修改培训计划
 */
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Hr_auditor_plan.php');
include(SET_DIR.'setup_hr_auditor_plan.php');
include_once S_DIR.'include/setup/setup_audit_iso.php';

GrepUtil::InitGP(array('id'));
Power::CkPower('H6S');

$width='600px';

$Hr_auditor_plan = new Hr_auditor_plan();
$result = $Hr_auditor_plan->query($id);

$result['username'] = Cache::cache_username($result['hr_id']);
$result['plan_complete_date']  == '0000-00-00' && $result['plan_complete_date'] = '';
$result['actual_complete_date']  == '0000-00-00' && $result['actual_complete_date'] = '';

include TEMP.'header.htm';
include TEMP.'hr/hr_auditor_plan_edit.htm';
include TEMP.'footer.htm';
?>