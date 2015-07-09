<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Item.php';
include_once S_DIR.'include/module/Task.php';
include_once S_DIR.'include/module/Auditor.php';
include_once S_DIR.'include/module/AuditorPlan.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'include/setup/setup_role.php';
include_once S_DIR.'include/setup/setup_hr_reg_qualification.php';
include_once S_DIR.'include/setup/setup_authentication.php';

GrepUtil::InitGP(array('taskId'));
Power::CkPower('C1E');
$Task = new Task();
$result = $Task->query($taskId);

$result['auditorBeginHalfDate'] = Cache::cache_time_online($result['auditorBeginHalfDate'],1);
$result['auditorEndHalfDate'] = Cache::cache_time_online($result['auditorEndHalfDate'],1);
if($result['jinxianchang'] == '1'){ $result['jinxianchang'] = '是';}else { $result['jinxianchang'] = '否';}

$Item = new Item();
$arr = $Item->toArray("taskId='{$taskId}' ORDER BY iso ASC");
$audit_code = array();$audit_type = array();
foreach ($arr as $v){
	$ht_id []= $v['ht_id'];
	$htxm_id []= $v['htxm_id'];
	$daima []= $v['audit_code'];
	$xm_other []= $v['xm_other'];
	$xmiso []= $v['iso'];
	$audit_code []= $v['iso']."：".$v['audit_code'];
	$audit_type []= $v['iso']."：".$v['audit_type'];
	$xcrs []= $v['iso']."：".$v['xcd'];
	$ck_xcd[$v['iso']] = $v['xcd']; //计算是否满足人日用
	$ck_daima[$v['iso']] = $v['audit_code'];
}

$result['iso'] = implode(',',array_unique($xmiso));
$result['audit_type'] = implode('<br/>&nbsp;', $audit_type);
$result['audit_code'] = implode('<br/>&nbsp;', $audit_code);
$result['xcd'] = implode('<br/>&nbsp;', $xcrs);
$getdaima = implode(';', $daima);
$getdaima = str_replace('；', ';', $getdaima);

$Auditor = new Auditor();
$rows = $Auditor->toArray("taskId='{$taskId}' ORDER BY id ASC");

//判断是否安排冲突
foreach($rows as $h){
	$hr_id_t []= $h['empId'];
}
$hr_id_t = implode("','",$hr_id_t);
$auditorBeginDate = $result['auditorBeginDate'];
$auditorBeginHalfDate = $result['auditorBeginHalfDate'];
$auditorEndDate = $result['auditorEndDate'];
$auditorEndHalfDate = $result['auditorEndHalfDate'];
$begin =  $auditorBeginDate.$auditorBeginHalfDate;
$end =  $auditorEndDate.$auditorEndHalfDate;

//插入取审核天数
if($auditorBeginDate != $auditorEndDate){
	$s_hour = (int)((strtotime('17:00:00') - strtotime($auditorBeginHalfDate))/3600);
	$s_hour < '0' && $s_hour = '0';
	$s_hour > '5' && $s_hour = $s_hour - '1';

	$e_hour = (int)((strtotime($auditorEndHalfDate) - strtotime('08:00:00'))/3600);
	$e_hour < '0' && $e_hour = '0';
	$e_hour > '5' && $e_hour = $e_hour - '1';

	$t_day = (int)((strtotime($auditorEndDate) - strtotime($auditorBeginDate))/(3600*24) - 1);
	$z_day = ($s_hour + $e_hour)/8 + $t_day;
}else{
	$z_hour =  (int)((strtotime($auditorEndHalfDate) - strtotime($auditorBeginHalfDate))/3600);
	$z_hour < '0' && $z_hour = '0';
	$z_hour > '5' && $z_hour = $z_hour - '1';
	$z_day = $z_hour/8;
}
$z_day == '' && $z_day = '0';

if($auditorBeginDate!='' and $auditorEndDate!=''){
	$esql = $db->query("SELECT zuzhi_id,empId,auditorBeginDate,auditorBeginHalfDate,auditorEndDate,auditorEndHalfDate FROM `xm_auditor`
	WHERE ((auditorBeginDate>='$auditorBeginDate' AND auditorBeginDate<='$auditorEndDate')
	OR (auditorEndDate>='$auditorBeginDate' AND auditorEndDate<='$auditorEndDate')
	OR (auditorBeginDate<='$auditorBeginDate' AND auditorEndDate>='$auditorBeginDate')
	 OR (auditorBeginDate<='$auditorEndDate' AND auditorEndDate>='$auditorEndDate')) AND empId IN('$hr_id_t')");
	while($date = $db->fetch_array($esql)){
		$begin_temp = $date['auditorBeginDate'].$date['auditorBeginHalfDate'];
		$end_temp = $date['auditorEndDate'].$date['auditorEndHalfDate'];
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

$xm_other = array_unique($xm_other);
Power::xt_htfrom($result['zuzhi_id']);
$width='900px';
$id_arr = array('zuzhi_id'=>$result['zuzhi_id'],'htxm_id'=>$htxm_id,'ht_id'=>$ht_id);
$params = array('company' => array(),'certificate' => array(),'contract'=>array(),'item' => array(),'finance'=>array());
$Information = new Information($id_arr,$width,'',$params);

include TEMP.'header.htm';
include TEMP.'audit/xm_edit.htm';
include TEMP.'footer.htm';
?>