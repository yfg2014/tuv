<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once SET_DIR.'setup_htfrom.php';

GrepUtil::InitGP(array('s_date','e_date'));

if($s_date == '' || $e_date == ''){
	$s_date = date('Y-m').'-01';
	$e_date = date('Y-m-t');
}

$arr = array();
$arriso = array('Q','E','QY','S');
$sql_temp .= " AND iso IN('".implode("','",$arriso)."')";
$sql = "SELECT id,htfrom,iso FROM {$dbtable['xm_item']} WHERE online='3' and taskBeginDate>='$s_date' and taskBeginDate<='$e_date' $sql_temp";
$query = $db->query($sql);
while($v = $db->fetch_array($query)){
	$arr[$v['htfrom']][$v['iso']]++;
}

include TEMP.'header.htm';
include TEMP.'report/audit_department_report.htm';
include TEMP.'footer.htm';
?>
