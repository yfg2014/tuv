<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/InformationRelease.php';
Power::CkPower('J4E');
GrepUtil::InitGP(array('id'));

$sql_temp = $sql_temp." and num='2' and userid='{$_SESSION['user']}'";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$InformationRelease = new InformationRelease();
$result = $InformationRelease->listInformationRelease($params);

$width = '600px';
include_once T_DIR.'header.htm';
include_once T_DIR.'hr/sys_sms_list2.htm';
include_once T_DIR.'footer.htm';
?>