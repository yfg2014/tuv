<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/InformationRelease.php';
Power::CkPower('J3E');

$InformationRelease = new InformationRelease();
$result = $InformationRelease->listInformationRelease($params);

$width = '700px';
include_once T_DIR.'header.htm';
include_once T_DIR.'hr/sys_notice_list.htm';
include_once T_DIR.'footer.htm';
?>