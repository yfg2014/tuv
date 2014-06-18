<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/AuditorPlan.php';

GrepUtil::InitGP(array('eid','evaluate_date','remark'));

Power::CkPower('H0J');

$AuditorPlan = new AuditorPlan();
$params = array(
	'evaluate_date' => $evaluate_date,
	'remark' => $remark,
	'pj_ren' => $_SESSION['username'],
	'pj_time' => date("Y-m-d")
);
$AuditorPlan->update($eid,$params);
LogRW::logWriter('','登记评价内容');

Url::goto_url('index.php?m=hr&do=hr_evaluation_list', '保存成功');
?>
