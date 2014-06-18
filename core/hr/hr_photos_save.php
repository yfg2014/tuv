<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include(S_DIR.'/include/class_uploader.php');

GrepUtil::InitGP(array('hr_id','files'));
Power::CkPower('H0E');
Power::xt_htfrom($hr_id,'hr');
$params = array(
	'files' => $_FILES['files'],	//上传输入控件名
	'hr_id' => $hr_id,
	'filekind' => '8080',	//资料类型
	'MAXSIZE' => MAXSIZE,       //上传文件大小
);

$Hr_information = new Hr_information();
$Hr_information->photos($params);
LogRW::logWriter('', '上传人员'.Cache::cache_username($hr_id).'相片信息');

Url::goto_url('index.php?m=hr&do=hr_photos_edit&hr_id='.$hr_id, '保存成功');
?>