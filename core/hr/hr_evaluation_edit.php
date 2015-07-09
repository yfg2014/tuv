<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/AuditorPlan.php');
include_once S_DIR.'/include/module/Information.php';

GrepUtil::InitGP(array('eid','taskId','zuzhi_id'));
Power::CkPower('H0J');

$width='600px';
$AuditorPlan = new AuditorPlan();
$result = $AuditorPlan->query($eid);
$result['role'] = Cache::cache_hr_role($result['role']);
$result['eiregistername'] = Cache::cache_company($zuzhi_id);
$rows = $db->get_one("SELECT taskBeginDate,taskEndDate FROM `{$dbtable['xm_auditor']}` WHERE id='{$result['auditorId']}' LIMIT 1");
$result['taskBeginDate'] = $rows['taskBeginDate'];
$result['taskEndDate'] = $rows['taskEndDate'];

$rows = $db->get_one("SELECT empName FROM `{$dbtable['xm_auditor_plan']}` WHERE taskId='{$taskId}' AND iso='{$result['iso']}' AND evaluate='2' and empId!='{$result['empId']}' LIMIT 1");
$result['pjempName'] = $rows['empName'];

$Information = new Information(array('zuzhi_id'=>$zuzhi_id),$width,'',$params = array('company'=>array()));

include TEMP.'header.htm';
include TEMP.'hr/hr_evaluation_edit.htm';
include TEMP.'footer.htm';
?>