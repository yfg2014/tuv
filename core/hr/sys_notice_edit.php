<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/InformationRelease.php';
include(SET_DIR.'setup_hr_department.php');
Power::CkPower('J3E');
GrepUtil::InitGP(array('id'));

$width = '600px';
$InformationRelease = new InformationRelease();
$result = $InformationRelease->query($id);

$upload = "./core/hr/sys_notice_file_upload.php";

include T_DIR.'header.htm';
include T_DIR.'hr/sys_notice_edit.htm';
include T_DIR.'footer.htm';
?>