<?php
include_once S_DIR.'/include/module/CompositeUpload.php';

Power::CkPower('L1E');

GrepUtil::InitGP(array('fid'));

$CompositeUpload = new CompositeUpload();
$CompositeUpload->delete((int)$fid);
Url::goto_url('index.php?m=composite&do=composite_upload_list&', '删除文件成功');		
?>