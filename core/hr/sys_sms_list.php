<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/InformationRelease.php';
Power::CkPower('J4E');
GrepUtil::InitGP(array('id'));

$sql_temp = $sql_temp." and num='2' and touserid like '%{$_SESSION['user']}%'";
$params = array(
	'search' => $sql_temp,
	'url' => $url,
);

$InformationRelease = new InformationRelease();
$result = $InformationRelease->listInformationRelease($params);

$width = '600px';
include_once TEMP.'header.htm';
include_once TEMP.'hr/sys_sms_list.htm';
include_once TEMP.'footer.htm';
?>