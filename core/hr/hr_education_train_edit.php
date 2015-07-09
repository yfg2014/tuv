<?php
/**
 * 添加、修改培训计划
 */
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/EducationTrain.php');
include(SET_DIR.'setup_hr_education_train.php');

GrepUtil::InitGP(array('id'));
Power::CkPower('H2P');

$width='500px';

$EducationTrain = new EducationTrain();
$result = $EducationTrain->query($id);
$result['username'] = Cache::cache_username($result['hr_id']);

include TEMP.'header.htm';
include TEMP.'hr/hr_education_train_edit.htm';
include TEMP.'footer.htm';
?>