<?php
include_once S_DIR.'/include/module/TaskUpload.php';

Power::CkPower('K0D');

GrepUtil::InitGP(array('fid'));

$TaskUpload = new TaskUpload();
$TaskUpload->down((int)$fid);
		
?>