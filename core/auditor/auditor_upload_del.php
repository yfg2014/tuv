<?php
include_once S_DIR.'/include/module/TaskUpload.php';

Power::CkPower('K0H');

GrepUtil::InitGP(array('fid'));

$TaskUpload = new TaskUpload();
$zuzhi_id = $TaskUpload->delete((int)$fid);
Url::goto_url('index.php?m=auditor&do=auditor_upload_list&', '删除文件成功');		
?>