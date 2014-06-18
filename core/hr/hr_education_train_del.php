<?php

!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/EducationTrain.php');
Power::CkPower('H3P');

GrepUtil::InitGP(array('id'));

$EducationTrain = new EducationTrain();
$EducationTrain->del($id);	

Url::goto_url('index.php?m=hr&do=hr_education_train_list', '删除成功');

?>