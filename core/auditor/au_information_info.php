<?php

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include(SET_DIR.'setup_hr_politics.php');		//政治面貌
include(SET_DIR.'setup_htfrom.php');			//合同来源
include(SET_DIR.'setup_hr_use_lev.php');		//审核员使用级别
include(SET_DIR.'setup_hr_department.php');		//部门
include(SET_DIR.'setup_hr_position.php');		//人员职务
include(SET_DIR.'setup_hr_certificate.php');	//证件名称$
include(SET_DIR.'setup_hr_xingzhi.php');		//人员性质
include(SET_DIR.'setup_hr_contract_type.php');
include(SET_DIR.'setup_hr_ht.php');    //人员合同类型

$id = $_SESSION['userid'];

$width='750px';
Power::CkPower('H0S');
if ($id != '') {
	$ren	= new Hr_information();
	$value	= $ren->query($id);
	Power::xt_htfrom($id,'hr');
	$photos = 'upload/hr/'.$id.'/photos/'.$value['photos'];
	$birthday = explode("-", $value[birthday]);
	$value['xingzhi'] = explode(',',$value['xingzhi']);
}
$value[working] == '0' ? $working_ck02 = 'checked="checked"' : $working_ck01 = 'checked="checked"';		//在职 离职
$value['sex'] == '02' ? $sex_ck02 = 'checked="checked"' : $sex_ck01 = 'checked="checked"';				//男 女
if ($value[worktype] == '01'){$worktype_ck01 = 'checked="checked"';}		//01兼职
elseif ($value[worktype] == '02'){$worktype_ck02 = 'checked="checked"';}	//02专职
else {$worktype_ck08 = 'checked="checked"';}								//无(办公人员)
$value[baomi] == '1' ? $baomi_ck = 'checked="checked"' : $baomi_ck = '';		//保密
if ($value[shebaohao] == '0'){$shebaohao_ck0='checked="checked"'; }   //社保
elseif($value[shebaohao] == '1'){$shebaohao_ck01='checked="checked"'; }
elseif($value[shebaohao] == '2'){$shebaohao_ck02='checked="checked"' ;}
$value['groupdate'] != '0000-00-00' ? $groupdate_ck02 = 'checked="checked"' : $groupdate_ck01 = 'checked="checked"';
// 个人信息导航代码
$p_url = Personal::Navigation($id);
include TEMP.'header.htm';
include TEMP.'auditor/au_information_info.htm';
include TEMP.'footer.htm';
?>