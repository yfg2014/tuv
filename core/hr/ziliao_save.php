<?php
include(S_DIR.'include/module/Hr_information.php');
include(S_DIR.'include/module/HrZiliao.php');
include(S_DIR.'/include/setup/setup_hr_uploadfile.php');
include(S_DIR.'/include/class_uploader.php');

GrepUtil::InitGP(array('hr_id','filekind'));

$params = array(
	'hr_id'	   => $hr_id,
	'filekind' => $filekind,
	'files'    =>$_FILES['files'],
);
$HrZiliao = new ZiliaoDao();
$HrZiliao->uploadfile($params);

Url::goto_url('index.php?m=hr&do=ziliao_edit&hr_id='.$hr_id, '上传文件成功');
?>