<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Wage.php';

GrepUtil::InitGP(array('eid','hr_id','wages','grant_time','five_insurance','tax','realhair','total','remark'));

Power::CkPower('H0G');

$Wage = new Wage();
$params = array(
	'hr_id' => $hr_id,
	'grant_time' => $grant_time,
	'wages' => $wages,
	'five_insurance' => $five_insurance,
	'tax' => $tax,
	'realhair' => $realhair,
	'total' => $total,
	'remark' => $remark,
	'zd_ren' => $_SESSION['username'],
	'zd_time' => date("Y-m-d")
);
$Wage->save($eid,$params);

Url::goto_url('index.php?m=hr&do=hr_wage_list', '保存成功');
?>
