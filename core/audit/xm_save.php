<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Task.php';
include_once S_DIR.'include/module/Auditor.php';
include_once S_DIR.'include/module/AuditorPlan.php';
include_once S_DIR.'include/module/Item.php';

GrepUtil::InitGP(array('zuzhi_id','taskId','auditorId','empId','empName','auditday','laborcosts','xmonline','upplan','auditorBeginDate','auditorBeginHalfDate','auditorEndDate','auditorEndHalfDate'));
GrepUtil::InitGP(array('auditorPlanId','isof','iso','isLeader','role','witness','qualification','audit_code','other','evaluate'));
Power::CkPower('C1E');
Power::xt_htfrom($zuzhi_id);
$Task = new Task();
$result = $Task->query($taskId);

if($result['id'] == ''){
	//Error::ShowError("错误：此任务已删除");
	Url::goto_url("index.php?m=audit&do=xm_no_list", '错误：此任务已删除');
}
$Auditor = new Auditor();
$AuditorPlan = new AuditorPlan();
$row = 0;$biaozhun = explode(',',$isof);
(int)$iso_n = count($biaozhun);
foreach($empName as $k => $v){
	 //审核天数计算
	$floor = 0;
	$EndHalfDate[$k] = strtotime($auditorEndHalfDate[$k]) > strtotime("12:00:00") ? "23:59:59" : "12:00:00" ;
	$BeginHalfDate[$k] = strtotime($auditorBeginHalfDate[$k]) >= strtotime("12:00:00") ? "12:00:00" : "00:00:00" ;
	$time1 = strtotime($auditorEndDate[$k].' '.$EndHalfDate[$k]);
	$time2 = strtotime($auditorBeginDate[$k].' '.$BeginHalfDate[$k]);
	$time_t = ($time1 - $time2) / (24*3600);
	$tianshu = number_format($time_t,'1');
	$tianshu_int = floor($tianshu);
	$tianshu_floor = $tianshu - $tianshu_int;
	if($tianshu_floor == 0){
		$auditday = $tianshu_int;
	}else if($tianshu_floor > 0 and $tianshu_floor<= 0.5){
		$auditday = $tianshu_int + 0.5;
	}else if($tianshu_floor > 0.5){
		$auditday = $tianshu_int + 1;
	}else{
		$auditday = $tianshu;
	}

	$params = array(
		'zuzhi_id' => $zuzhi_id,
		'taskId' => $taskId,
		'empId' => $empId[$k],
		'empName' => $v,
		'htfrom'=> $result['htfrom'],
		'taskBeginDate' => $result['taskBeginDate'],
		'taskBeginHalfDate' => $result['taskBeginHalfDate'],
		'taskEndDate' => $result['taskEndDate'],
		'taskEndHalfDate' => $result['taskEndHalfDate'],
		'auditorBeginDate' => $auditorBeginDate[$k],
		'auditorBeginHalfDate' => $auditorBeginHalfDate[$k],
		'auditorEndDate' => $auditorEndDate[$k],
		'auditorEndHalfDate' => $auditorEndHalfDate[$k],
		'auditday' => $auditday,
		'laborcosts' => $laborcosts[$k],
		'zd_ren' => $_SESSION['username'],
		'zd_time' => date("Ymd")
	);

	$auditrId = $Auditor->save($auditorId[$k], $params);

	for($i = 0; $i < $iso_n; $i++){
		$params = array(
			'zuzhi_id' => $zuzhi_id,
			'auditorId' => $auditrId,
			'taskId' => $taskId,
			'htfrom'=> $result['htfrom'],
			'iso' => $iso[$row],
			'empName' => $v,
			'empId' => $empId[$k],
			'isLeader' => $isLeader[$row],
			'role' => $role[$row],
			'witness' => $witness[$row],
			'evaluate' => $evaluate[$row],
			'qualification' => $qualification[$row],
			'audit_code' => $audit_code[$row],
			'auditor_tianshu' =>  $auditday
		);

		$AuditorPlan->save($auditorPlanId[$row], $params);
		$row++;
	}
}

//处理项目和任务状态
if($xmonline == '2'){
	$online = '1';
}else{
	$online = '0';
	$xmonline = '1';
}
if($result['xmonline'] <= 2){
	DBUtil::update_tb($db, $dbtable['xm_task'], array('online' => $online,'xmonline' => $xmonline), "id='{$taskId}'");
	DBUtil::update_tb($db, $dbtable['xm_item'], array('online' => $xmonline), "taskId='{$taskId}'");
}

//处理是否重报审核计划
$Item = new Item();
$arr = $Item->toArray("taskId='{$taskId}'",array('iso','audit_type'));
$audit_type = array();
foreach ($arr as $v){
	$audit_type []= $v['iso'].'：'.$v['audit_type'];
}
$type = implode('/',$audit_type);
foreach ($upplan as $k=>$v){
	$Item->update($k, array('upplan'=>$v));
}

LogRW::logWriter($zuzhi_id, '安排审核员审核 '.$type);

Url::goto_url('index.php?m=audit&do=approval_edit&taskId='.$taskId.'&zuzhi_id='.$zuzhi_id, '保存成功');
?>