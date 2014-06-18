<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/Hr_auditor_plan.php');
Power::CkPower('H6E');

$value = GrepUtil::InitGP(array('id','hr_id','plan_item','planner','iso','plan_date','plan_complete_date','actual_complete_date','remark'));	//过滤数据，获取数据集

$value['zd_ren'] = $_SESSION["username"];
$value['zd_time'] = date("Y-m-d");

$s = new Hr_auditor_plan();

if ($id > 0) {
	$re = $s->update($id,$value);
} else {
	$re = $s->add($value);
}

Url::goto_url('index.php?m=hr&do=hr_auditor_plan_list', '保存成功');
?>