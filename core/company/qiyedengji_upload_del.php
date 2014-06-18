<?php
include_once S_DIR.'/include/module/CompanyUpload.php';

Power::CkPower('A1D');

GrepUtil::InitGP(array('fid','op'));

$CompanyUpload = new CompanyUpload();
$zuzhi_id = $CompanyUpload->delete((int)$fid);

if($op == '1'){
	Url::goto_url('index.php?m=company&do=qiyedengji_upload_edit&zuzhi_id='.$zuzhi_id, '删除文件成功');		
}else{
	Url::goto_url('index.php?m=company&do=qiyedengji_upload_list', '删除文件成功');	
}
	
?>