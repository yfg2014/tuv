<?php
include '../include/globals.php';

GrepUtil::InitGP(array('iso','code','hr_id','c_code_id','kind'));
if ($kind == 'daima') {
	$array = explode('；',str_replace(';','；',$code));
	$arr = array();
	foreach($array as $v){
		$rows = $db->get_one("SELECT * FROM `setup_audit_code` WHERE `iso`='{$iso}' AND xiaolei like '{$v}%' AND `online`=1");
		if ($rows == ""){
			$arr []= $v;
		}
	}
	$result = implode('；',$arr);
	if($result != ''){
		echo $result.' 该专业代码有误或者不存在!';
		exit;
	}
}
if ($kind == 'msg') {
	$rows = $db->get_one("SELECT * FROM `setup_audit_code` WHERE `iso`='{$iso}' AND xiaolei like '{$code}%' AND `online` =1");
	if ($rows != ""){
		echo $rows['msg'];
		exit;
	}
}

if ($kind == 'leibie'){
	$rows = $db->get_one("SELECT `qualification` FROM {$dbtable['hr_reg_qualification']} WHERE `hr_id` ='{$hr_id}' AND online='1' and iso ='$iso'");
//	$zg = array();
//	while($arr = $db->fetch_array($rows)){
//		$zg []= $arr['qualification'];
//	}
//	if(!in_array($c_code_id,$zg)){
//		echo '该审核员无此资格';
//		exit;
//	}
	if($rows == ""){
		echo '不具有该'.$iso.'体系任何的资格!';
		exit;
	}
}
?>