<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/InformationRelease.php';

GrepUtil::InitGP(array('id'));

$InformationRelease = new InformationRelease();
$result = $InformationRelease->query($id);
$times = $result['times'] + 1;
$InformationRelease->update($id, array('times' => $times));

$width = '600px';
include T_DIR.'header.htm';
include T_DIR.'hr/sys_sms_show.htm';
include T_DIR.'footer.htm';
?>