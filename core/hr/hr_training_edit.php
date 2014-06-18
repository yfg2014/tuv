<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include_once S_DIR.'/include/module/Training.php';

Power::CkPower('H4E');

GrepUtil::InitGP(array('id'));

if ($id != ''){
	$arr = $add = array();
	$Training = new Training();
	$Hr_information = new Hr_information();
	$result = $Training->query($id);
	$arr = $Training->toArray($id);
	foreach ($arr as $v){
		$rows = $Hr_information->query($v,array('username'));
		$add []= $rows['username'];
	}
	$result['people'] = implode('；',$add);
}
// 个人信息导航代码
$p_url = Personal::Navigation($id);
$width= '550px';
include T_DIR.'header.htm';
include T_DIR.'hr/hr_training_edit.htm';
include T_DIR.'footer.htm';
?>