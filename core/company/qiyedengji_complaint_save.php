<?php
set_time_limit(0);
include_once S_DIR.'/include/module/CompanyComplaint.php';

$value = GrepUtil::InitGP(array('zuzhi_id','htfrom','complaint','complaint_linkman','complaint_linkman_mob','complaint_from','complaint_content','processing_type','processing_date','processing_department','relating_department','important_degree','processing_result','processing_status','online','zd_ren'));
GrepUtil::InitGP(array('id','FileID'));
Power::CkPower('A2E');

$value['zd_ren'] = $_SESSION["username"];
$value['zd_date'] = date("Y-m-d");

$CompanyComplaint = new CompanyComplaint();

$FileID > 0 && $id = $FileID;
			
if($id > 0){
	$row = $db->get_one("SELECT complaint FROM  `mk_company_complaint` WHERE id='$id'");
	$CompanyComplaint->update($id,$value);
	if($row['complaint'] == ''){
		LogRW::logWriter($params['zuzhi_id'], '企业投诉登记');
	}else{
		LogRW::logWriter($params['zuzhi_id'], '企业投诉修改');
	}
}else{
	$CompanyComplaint->add($value);
	LogRW::logWriter($params['zuzhi_id'], '企业投诉登记');
}

Url::goto_url('index.php?m=company&do=qiyedengji_complaint_list', '保存成功');
?>