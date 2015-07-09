<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'/include/module/CompanyQuestion.php';

Power::CkPower('A2E');

GrepUtil::InitGP(array('zuzhi_id'));
//页面配置信息
$width = '500px';
$id_arr = array('zuzhi_id'=>$zuzhi_id);
$params = array('company' => array());
$Information = new Information($id_arr,$width,'',$params);

$company = $db->get_one("SELECT htfrom FROM `mk_company` WHERE id='$zuzhi_id'");


GrepUtil::InitGP(array('id'));
$CompanyQuestion = new CompanyQuestion();
$Question = $CompanyQuestion->query($id);

include TEMP.'header.htm';
include TEMP.'company/qiyedengji_question_edit.htm';
include TEMP.'footer.htm';
?>