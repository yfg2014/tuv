<?php
include '../include/globals.php';

GrepUtil::InitGP(array('hr_id','iso','getdaima','role'));
//$iso == 'QY' && $iso = 'Q';
$getdaima  = str_replace('；',';',str_replace(" ","",$getdaima));
$getdaima = implode(";",array_unique(explode(';',$getdaima)));
if(in_array($role,array('1001','1002','1003'))){
	$role_sql = " AND (qualification = '1002' OR qualification = '1003' OR qualification = '1004') ";
}else if($role = '1004'){
	$role_sql = " AND qualification = '1005' ";
}else{
	$role_sql = "AND qualification = '0'";
}

$daima = str_replace(';', '\',\'', $getdaima);
$result = $db->get_one("SELECT qualification FROM hr_reg_qualification WHERE hr_id='$hr_id' AND iso='$iso' AND online='1' $role_sql");

$sql = $db->query("SELECT xiaolei FROM hr_audit_code WHERE hr_id='$hr_id' AND online='1' AND iso='$iso' AND xiaolei IN('$daima') $role_sql");
while ($rows = $db->fetch_array($sql)) {
	$ma []= $rows['xiaolei'];
}
$result['xiaolei'] = implode('；', $ma);
$wrap = json_encode($result);
echo $wrap;
exit;
?>