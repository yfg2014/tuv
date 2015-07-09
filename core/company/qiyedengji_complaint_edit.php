<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Company.php');
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'/include/module/CompanyComplaint.php';

Power::CkPower('A2S');

GrepUtil::InitGP(array('id','zuzhi_id'));
//页面配置信息
$width = '700px';
if($id != ''){
	$Complaint = new CompanyComplaint();
	$result = $Complaint->query($id);
}else{
	$Company = new Company();
	$com = $Company->GetCompany($zuzhi_id,array('htfrom'));
	$result['htfrom'] = $com['htfrom'];
}

$upload = "./core/company/qiyedengji_complaint_file_upload.php";

include TEMP.'header.htm';
include TEMP.'company/qiyedengji_complaint_edit.htm';
include TEMP.'footer.htm';
?>