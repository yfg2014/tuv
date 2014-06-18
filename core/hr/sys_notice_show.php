<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/InformationRelease.php';
include(SET_DIR.'setup_hr_department.php');

GrepUtil::InitGP(array('id'));

$InformationRelease = new InformationRelease();
$result = $InformationRelease->query($id);
$result['departments'] = Cache::cache_hr_department($result['departments']);
$times = $result['times'] + 1;
$InformationRelease->update($id, array('times' => $times));

$width = '600px';
include T_DIR.'header.htm';
include T_DIR.'hr/sys_notice_show.htm';
include T_DIR.'footer.htm';
?>