<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/Task.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'include/module/Auditor.php';

GrepUtil::InitGP(array('taskId','zuzhi_id','op'));
Power::CkPower('C6S');
Power::xt_htfrom($zuzhi_id);
$width='600px';
$params = array('task' => array(),'company' => array());
$id_arr = array('zuzhi_id'=>$zuzhi_id,'taskId'=>$taskId);

$Information = new Information($id_arr,$width,'',$params);

$Task = new Task();
$result = $Task->query($taskId);
$result['taskBeginHalfDate'] = Cache::cache_time_online($result['taskBeginHalfDate'],1);
$result['taskEndHalfDate'] = Cache::cache_time_online($result['taskEndHalfDate'],1);

$Item = new Item();
$zsIf = $Item->toArray("taskId='$taskId' and zs_if!='0'",array('id','htxm_id'));

$Auditor = new Auditor();
$rows = $Auditor->toArray("taskId='{$taskId}' ORDER BY id ASC");

//判断是否安排冲突
foreach($rows as $h){
	$hr_id_t []= $h['empId'];
}
$hr_id_t = implode("','",$hr_id_t);
$taskBeginDate = $result['taskBeginDate'];
$taskBeginHalfDate = $result['taskBeginHalfDate'];
$taskEndDate = $result['taskEndDate'];
$taskEndHalfDate = $result['taskEndHalfDate'];
$begin =  $taskBeginDate.$taskBeginHalfDate;
$end =  $taskEndDate.$taskEndHalfDate;
if($taskBeginDate!='' and $taskEndDate!=''){
	$esql = $db->query("SELECT zuzhi_id,empId,taskBeginDate,taskBeginHalfDate,taskEndDate,taskEndHalfDate FROM `xm_auditor`
	WHERE ((taskBeginDate>='$taskBeginDate' AND taskBeginDate<='$taskEndDate')
	OR (taskEndDate>='$taskBeginDate' AND taskEndDate<='$taskEndDate')
	OR (taskBeginDate<='$taskBeginDate' AND taskEndDate>='$taskBeginDate')
	 OR (taskBeginDate<='$taskEndDate' AND taskEndDate>='$taskEndDate')) AND empId IN('$hr_id_t')");
	while($date = $db->fetch_array($esql)){
		$begin_temp = $date['taskBeginDate'].$date['taskBeginHalfDate'];
		$end_temp = $date['taskEndDate'].$date['taskEndHalfDate'];
		$error = '1';
		if($begin_temp>=$begin and $begin_temp<=$end){
			$error = '1';
		}else if($end_temp>=$begin and $end_temp<=$end){
			$error = '1';
		}else if($begin_temp<=$begin and $end_temp>=$begin){
			$error = '1';
		}else if($begin_temp<=$end and $end_temp>=$end){
			$error = '1';
		}else{
			$error = '0';
		}
		if($error == '1' and ($date['zuzhi_id']=='0' or $result['zuzhi_id']=='0')){
			$hr_qj = $db->get_one("SELECT username FROM hr_information WHERE id='$date[empId]'");
		}
		if($error == '1' and $date['zuzhi_id']!=$result['zuzhi_id'] and $date['zuzhi_id']!='0' and $result['zuzhi_id']!='0'){
			$hrmsg = $db->get_one("SELECT username FROM hr_information WHERE id='$date[empId]'");
			$hr_Has[$hrmsg['username']] = Cache::cache_company($date['zuzhi_id']);
		}
	}
}


include T_DIR.'header.htm';
include T_DIR.'audit/approval_edit.htm';
include T_DIR.'footer.htm';
?>