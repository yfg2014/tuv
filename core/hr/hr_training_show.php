<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include_once S_DIR.'/include/module/Training.php';

Power::CkPower('H5S');

GrepUtil::InitGP(array('id'));

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

$width= '550px';
include T_DIR.'header.htm';
include T_DIR.'hr/hr_training_show.htm';
include T_DIR.'footer.htm';
?>