<?php
include_once S_DIR.'/include/module/CompositeUpload.php';

Power::CkPower('L1S');

GrepUtil::InitGP(array('fid'));

$CompositeUpload = new CompositeUpload();
$CompositeUpload->down((int)$fid);
		
?>