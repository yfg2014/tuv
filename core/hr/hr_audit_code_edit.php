<?php
/**
 * 人员专业能力
 * @2011-5-5
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include(S_DIR.'include/module/Hr_audit_code.php');			//人员专业能力类 审核代码
include(S_DIR.'include/module/Hr_reg_qualification.php');  //注册资格

include(SET_DIR.'setup_htfrom.php');						//合同来源 人员来源
include(SET_DIR.'setup_hr_ability_source.php');				//能力来源
include(SET_DIR.'setup_audit_iso.php');						//体系领域
include(SET_DIR.'setup_hr_reg_qualification.php');			//资格类别


GrepUtil::InitGP(array('hr_id','zg_id','id'));
Power::CkPower('H2E');
Power::xt_htfrom($hr_id,'hr');
$width='600px';
$Hr_information = new Hr_information();
$result_ren = $Hr_information->query($hr_id);

$reg = new Hr_reg_qualification();
$result_reg = $reg->query($zg_id);

$Hr_audit_code = new Hr_audit_code();
if($id == ""){
	$params = array("iso","qualification");
	$result = $Hr_audit_code->toArray(" hr_id={$hr_id} AND zg_id={$zg_id}",$params);
}else{
	$result = $Hr_audit_code->query($id);
}

if($result[online]=='0')
{
	$online_ck0 = 'checked="checked"';
}elseif($result[online]=='1'){
	$online_ck1 = 'checked="checked"';
}elseif($result[online]=='2'){
	$online_ck2 = 'checked="checked"';
}else{
	$online_ck1 = 'checked="checked"';
}

if($result[ifjianzheng]=='0')
{
	$ifjianzheng0 = 'checked="checked"';
}elseif($result[ifjianzheng]=='1'){
	$ifjianzheng1 = 'checked="checked"';
}else {
	$ifjianzheng0 = 'checked="checked"';
}

if($result[ifrenzhengjueding]=='0')
{
	$ifrenzhengjueding0 = 'checked="checked"';
}elseif($result[ifrenzhengjueding]=='1'){
	$ifrenzhengjueding1 = 'checked="checked"';
} else {
	$ifrenzhengjueding0 = 'checked="checked"';
}

if($result[ifhead]=='0')
{
	$ifhead_ck0 = 'checked="checked"';
}elseif($result[ifhead]=='1'){
	$ifhead_ck1 = 'checked="checked"';
}else {
	$ifhead_ck0 = 'checked="checked"';
}

if($result[yearok]=='2')
{
	$yearok_ck01 = 'checked="checked"';
}elseif($result[yearok]=='8'){
	$yearok_ck02 = 'checked="checked"';
}

// 个人信息导航代码
$p_url = Personal::Navigation($hr_id);
include T_DIR.'header.htm';
include T_DIR.'hr/hr_audit_code_edit.htm';
include T_DIR.'footer.htm';
?>