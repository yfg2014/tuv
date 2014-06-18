<?php
include '../include/globals.php';
//include_once S_DIR.'/include/module/Hr_reg_qualification.php';
//include_once S_DIR.'/include/module/Hr_information.php';

//GrepUtil::InitGP(array('page','code','msg','taskId'));
//
//if($code != ''){$sql_temp .= " AND idcode LIKE '$code%'";}
//if($msg!=''){$sql_temp.=" AND username LIKE '%$msg%'";}

//负责该项目的评定老师
//$empId = array();
//if($taskId>'0'){
//	$sql = "SELECT empId FROM {$dbtable[xm_auditor]} WHERE taskId='{$taskId}'";
//	$query = $db->query($sql);
//	while($rows = $db->fetch_array($query)){
//		$empId []= $rows['empId'];
//	}
//	$empId = implode('\',\'',$empId);
//	$sql_temp = $sql_temp." AND hr_id NOT IN('$empId')";
//}
//$url = "in_assesss_taff_list.php?code=$code&msg=$msg&";
//
//$sql_temp .= " and online='1' and qualification='1006'";
//
//$params = array(
//	'search' => $sql_temp,
//	'url' => $url,
//);
//
//$db_perpage = 15;
//$Hr_reg_qualification = new Hr_reg_qualification();
//$result = $Hr_reg_qualification->list_reg_qualification($params);

$query = $db->query("SELECT * FROM `hr_review`");
while($rows = $db->fetch_array($query)){
	$result[] = $rows;
}

include 'template/header.htm';
include 'template/in_assesss_taff_list.htm';
include 'template/footer.htm';
?>