<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/InformationRelease.php';
Power::CkPower('J3E');

$InformationRelease = new InformationRelease();
$result = $InformationRelease->listInformationRelease($params);

$width = '700px';
include_once TEMP.'header.htm';
include_once TEMP.'hr/sys_notice_list.htm';
include_once TEMP.'footer.htm';
?>