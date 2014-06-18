<?php
set_time_limit(0);
include_once S_DIR.'/include/module/CompanyQuestion.php';

GrepUtil::InitGP(array('id','zuzhi_id','htfrom','printdate','other','zd_ren'));

Power::CkPower('A2E');

$CompanyQuestion = new CompanyQuestion();

	$params = array(
	'zuzhi_id' => $zuzhi_id,   //组织ID
	'htfrom' => $htfrom,   //组织ID
	'printdate' => date('Y-m-d H:i:s'),    //登记时间
	'other' => $other,          //登记问题
	'zd_ren' => $_SESSION['username']   //操作人员
	);

if($id != ''){
	$CompanyQuestion->update($id,$params);
}else{
	$CompanyQuestion->add($params);
}


LogRW::logWriter($zuzhi_id,'问题登记');

Url::goto_url('index.php?m=company&do=qiyedengji_question_list', '保存成功');
?>