<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Task.php';
include_once S_DIR.'include/module/Item.php';

GrepUtil::InitGP(array('taskPid','taskId','zuzhi_id','jinxianchang','zrd','wenshen','xcd','iso','audit_ver','audit_type','taskBeginDate','taskBeginHalfDate','taskEndDate','taskEndHalfDate','taskRemarks','selfRemarks','upplan'));
Power::CkPower('C0E');
$params = array(
	'zuzhi_id' => $zuzhi_id,
	'jinxianchang' => $jinxianchang,
	'zrd' => $zrd,
	'wenshen' => $wenshen,
	'xcd' => implode(',',$xcd),
	'iso' => implode(',',$iso),
	'audit_ver' => implode(',',$audit_ver),
	'audit_type' => implode(',',$audit_type),
	'taskBeginDate' => $taskBeginDate,
	'taskBeginHalfDate' => $taskBeginHalfDate,
	'taskEndDate' => $taskEndDate,
	'taskEndHalfDate' => $taskEndHalfDate,
	'taskRemarks' => $taskRemarks,
	'selfRemarks' => $selfRemarks,
	'zd_ren' => $_SESSION['username'],
	'zd_time' => date("Ymd")
);
if($taskId == ''){
	$xm = $db->get_one("SELECT taskId,online FROM xm_item WHERE id='$taskPid[0]'");
	if($xm['online'] != '0'){
		$taskId = $xm['taskId'];
		Url::goto_url('index.php?m=audit&do=task_edit&taskId='.$taskId, '此审核任务已创建，请勿重复操作');
	}
}
$Task = new Task();
$taskId = $Task->save($taskId,$params);

$Item = new Item();
$value = array(
	'taskId' => $taskId,
	'online' => '1',
	'taskBeginDate' => $params['taskBeginDate'],
	'taskBeginHalfDate' => $params['taskBeginHalfDate'],
	'taskEndDate' => $params['taskEndDate'],
	'taskEndHalfDate' => $params['taskEndHalfDate'],
	'actualtaskBeginDate' => $params['taskBeginDate'],
	'actualtaskBeginHalfDate' => $params['taskBeginHalfDate'],
	'actualtaskEndDate' => $params['taskEndDate'],
	'actualtaskEndHalfDate' => $params['taskEndHalfDate']
);
foreach ($taskPid as $k=>$v){
	$xm = $db->get_one("SELECT iso FROM xm_item WHERE id='$v'");
	$value['xcd'] = $xcd[$xm['iso']];
	$value['upplan'] = $upplan[$xm['iso']];
	$Item->update($v, $value);
}

Url::goto_url('index.php?m=audit&do=xm_edit&taskId='.$taskId, '保存成功');
?>