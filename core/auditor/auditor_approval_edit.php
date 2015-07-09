<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/Task.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'include/module/Auditor.php';

GrepUtil::InitGP(array('taskId','zuzhi_id','op'));
Power::CkPower('K1S');
//Power::xt_htfrom($zuzhi_id);
$width='600px';
$params = array('task' => array(),'company' => array());
$id_arr = array('zuzhi_id'=>$zuzhi_id,'taskId'=>$taskId);

$Information = new Information($id_arr,$width,'',$params);

$Task = new Task();
$result = $Task->query($taskId);
$result['taskBeginHalfDate'] = Cache::cache_time_online($result['taskBeginHalfDate'],1);
$result['taskEndHalfDate'] = Cache::cache_time_online($result['taskEndHalfDate'],1);

$Item = new Item();
$zsIf = $Item->toArray("taskId='$taskId'",array('id','htxm_id'));

$Auditor = new Auditor();
$rows = $Auditor->toArray("taskId='{$taskId}' ORDER BY id ASC");

if($op == '1'){
	$provisions = '';
	$htxm2 = array();
	foreach($zsIf as $v){
		$htxm2 []= $v['htxm_id'];
	}
	$hrts = $db->get_one("SELECT id FROM xm_auditor WHERE empId='{$_SESSION['userid']}' and taskId='$taskId' GROUP BY empId");
	$zz = $db->get_one("SELECT id FROM xm_auditor_plan WHERE taskId='$taskId' and isLeader='1' and auditorId='{$hrts['id']}'");
	$htxmm = implode("','",$htxm2);
	$query = $db->query("SELECT taskId FROM xm_item WHERE htxm_id IN('$htxmm') AND taskId!='$taskId' GROUP BY taskId ORDER BY id DESC");
	while ($tsk = $db->fetch_array($query)) {
		$task = $db->get_one("SELECT provisions,prompt,summary,communicate,taskBeginDate FROM xm_task WHERE id='$tsk[taskId]' and provisions!= ''");
		if($task != ''){
			$auditordb []= $task;

		}
	}
}

include TEMP.'header.htm';
include TEMP.'auditor/auditor_approval_edit.htm';
include TEMP.'footer.htm';
?>