<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/EducationTrain.php');
Power::CkPower('H6E');

$value = GrepUtil::InitGP(array('id','hr_id','type','teach_date','content'));

$value['zd_ren'] = $_SESSION["username"];
$value['zd_time'] = date("Y-m-d");

$EducationTrain = new EducationTrain();
$EducationTrain->save($id,$value);

Url::goto_url('index.php?m=hr&do=hr_education_train_list', '保存成功');
?>