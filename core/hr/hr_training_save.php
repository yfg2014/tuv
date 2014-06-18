<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Training.php';

Power::CkPower('H4E');

GrepUtil::InitGP(array('id','title','teachertraining','trainingdate','eid','content','other'));

$params = array(
	'title' => $title,
	'teachertraining' => $teachertraining,
	'trainingdate' => $trainingdate,
	'content' => $content,
	'other' => $other,
	'user' => $_SESSION['user'],
	'zd_ren' => $_SESSION['username'],
	'zd_date' => date('Y-m-d'),
);

$Training = new Training();
$Training->save($id, $params, $eid);

Url::goto_url('index.php?m=hr&do=hr_training_list', '保存成功');
?>