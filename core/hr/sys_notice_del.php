<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/InformationRelease.php';
Power::CkPower('J3E');
GrepUtil::InitGP(array('id'));

//删除文件
$rows = $db->get_one("SELECT * FROM {$dbtable['mk_company_complaint']} WHERE id='".$id."'");
$file = UPLOAD_DIR.$rows['path'].'/'.$rows['fname'];
$file = str_replace('\\','/',$file);
if (file_exists($file))@unlink($file);

$InformationRelease = new InformationRelease();
$InformationRelease->delete($id);

Url::goto_url('index.php?m=hr&do=sys_notice_list', '操作成功');
?>