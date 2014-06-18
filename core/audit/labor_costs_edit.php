<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/Task.php';
include_once S_DIR.'/include/module/Auditor.php';
include_once S_DIR.'/include/module/AuditorPlan.php';
include_once S_DIR.'/include/module/Au_reg_qualification.php';
include_once S_DIR.'/include/module/Hr_information.php';
include_once S_DIR.'include/module/Information.php';

GrepUtil::InitGP(array('zuzhi_id','taskId','auditorId'));
Power::CkPower('C5E');
Power::xt_htfrom($zuzhi_id);
$Task = new Task();
$result = $Task->query($taskId);

$result['taskBeginHalfDate'] = Cache::cache_time_online($result['taskBeginHalfDate'],1);
$result['taskEndHalfDate'] = Cache::cache_time_online($result['taskEndHalfDate'],1);
$result['jinxianchang'] = Cache::cache_iswhether($result['jinxianchang']);

$Item = new Item();
$arr = $Item->toArray("taskId='{$taskId}' ORDER BY iso ASC");
$arr =='' ? $arr = array() : $arr;
$daima = array();$audit_type = array();$audit_code = array();
foreach ($arr as $v){
	$daima []= $v['audit_code'];
	$audit_type []= $v['iso']."：".$v['audit_type'];
	$audit_code []= $v['iso']."：".$v['audit_code'];
	$xcd []= $v['iso']."：".$v['xcd'];
}
$result['audit_type'] = implode('<br/>&nbsp;', $audit_type);
$result['audit_code'] = implode('<br/>&nbsp;', $audit_code);
$result['xcd'] = implode('<br/>&nbsp;', $xcd);

$Auditor = new Auditor();
$rows = $Auditor->query($auditorId);
$rows['printdate'] = Cache::cache_time_value($rows['printdate']);
$rows['paydate'] = Cache::cache_time_value($rows['paydate']);
$rows['laborcosts'] == '0' ? $rows['laborcosts'] = '' :$rows['laborcosts'];

$qualification = $isLeader = $role = $hr_audit_code = array();
$AuditorPlan = new AuditorPlan();
$add = $AuditorPlan->toArray("auditorId='{$auditorId}'  ORDER BY iso ASC");
$add == '' ? $add = array() : $add;
foreach ($add as $v){
	$role_t = Cache::cache_hr_role($v['role']);
	$v['witness'] == '0' && $v['witness'] = '';
	$v['witness'] == '1' && $v['witness'] = '验证';
	$v['witness'] == '2' && $v['witness'] = '被验证';
	
	$role_t == '无' && $role_t='';
	$role []= $v['iso']."：".$role_t.' '.$v['witness'];
	$isLeader []= $v['iso']."：".$v['isLeader'] = $v['isLeader'] == '1' ? $v['isLeader'] = '是' : $v['isLeader'] = '';
	$qualification []= $v['iso']."：".Cache::cache_hr_reg_qualification($v['qualification']);
	$hr_audit_code []= $v['iso']."：".$v['audit_code'];
}
$rows['role'] = implode('<br/>&nbsp;', $role);
$rows['isLeader'] = implode('<br/>&nbsp;', $isLeader);
$rows['qualification'] = implode('<br/>&nbsp;', $qualification);
$rows['hr_audit_code'] = implode('<br/>&nbsp;', $hr_audit_code);

//审核员当前审核地点
$audit_qy = $db->get_one("SELECT eiregistername,eisc_address,eiaddress FROM $dbtable[mk_company] WHERE id='$result[zuzhi_id]'");

$width='600px';
$id_arr = array('zuzhi_id'=>$zuzhi_id,'taskId'=>$taskId);
$params = array('task' => array(),'company' => array());
$Information = new Information($id_arr,$width,'',$params);


include_once T_DIR.'header.htm';
include_once T_DIR.'audit/labor_costs_edit.htm';
include_once T_DIR.'footer.htm';
?>