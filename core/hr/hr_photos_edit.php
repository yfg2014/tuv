<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');

GrepUtil::InitGP(array('hr_id'));
Power::CkPower('H0E');
Power::xt_htfrom($hr_id,'hr');
$width='400px';
$Hr_information = new Hr_information();
$result = $Hr_information->query($hr_id);
$result['photos'] = './upload/hr/'.$hr_id.'/photos/'.$result['photos'];

include TEMP.'header.htm';
include TEMP.'hr/hr_photos_edit.htm';
include TEMP.'footer.htm';
?>