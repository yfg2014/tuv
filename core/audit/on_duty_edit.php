<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Auditor.php';
GrepUtil::InitGP(array('id','hr_id'));
Power::CkPower('Z8S');
$width='700px';

if($id != ''){
	$Auditor = new Auditor();
	$result = $Auditor->query($id);
	
	$time_s_int = (int)$result['auditorBeginHalfDate'];
	$time_e_int = (int)$result['auditorEndHalfDate'];
	${'time_s_select'.$time_s_int} = 'selected';
	${'time_e_select'.$time_e_int} = 'selected';
}else{
	//初始时间选择
	$time_s_select8 = 'selected';
	$time_e_select17 = 'selected';
	
	$re = $db->get_one("SELECT id,username,htfrom FROM hr_information WHERE id='$hr_id'");
	$result['empId'] = $re['id'];
	$result['empName'] = $re['username'];
	$result['htfrom'] = $re['htfrom'];
}

for($i=0;$i<=23;$i++){
	if($i == 8 or $i == 12){
		$time_s_str .= "<option value=\"$i:00:00\" ".${'time_s_select'.$i}." >$i:00</option>";
	}
}
for($i=0;$i<=23;$i++){
	if($i == 12 or $i == 17){
		$time_e_str .= "<option value=\"$i:00:00\" ".${'time_e_select'.$i}." >$i:00</option>";
	}
}


include TEMP.'header.htm';
include TEMP.'audit/on_duty_edit.htm';
include TEMP.'footer.htm';
?>