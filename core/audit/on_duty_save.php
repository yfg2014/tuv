<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Auditor.php';
$params = GrepUtil::InitGP(array('id','empId','empName','htfrom','auditorBeginDate','auditorBeginHalfDate','auditorEndDate','auditorEndHalfDate','qj_other'));
Power::CkPower('Z8E');

$params['online'] = 8;
$params['zd_ren'] = $_SESSION['username'];
$params['zd_time'] = date('Y-m-d');

$Auditor = new Auditor();
$Auditor = $Auditor->save($id,$params);

LogRW::logWriter($Auditor, '审核员 '.$empName.' 请假登记');
Url::goto_url("index.php?m=audit&do=on_duty_list&", '保存成功');
?>