<?php
include_once S_DIR.'/include/module/CompanyUpload.php';

Power::CkPower('A1L');

GrepUtil::InitGP(array('fid'));

$CompanyUpload = new CompanyUpload();
$CompanyUpload->down((int)$fid);
		
?>