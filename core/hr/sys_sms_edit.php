<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include_once S_DIR.'include/module/InformationRelease.php';
include(SET_DIR.'setup_hr_department.php');
Power::CkPower('J4E');
GrepUtil::InitGP(array('id'));

$width = '600px';
$InformationRelease = new InformationRelease();
$result = $InformationRelease->query($id);

$Hr_information = new Hr_information();
$where = "password!='' and online='1' and user!='admin'";
$arr = $Hr_information->toArray($where);

include T_DIR.'header.htm';
include T_DIR.'hr/sys_sms_edit.htm';
include T_DIR.'footer.htm';
?>