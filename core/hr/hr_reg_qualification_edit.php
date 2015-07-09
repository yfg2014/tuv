<?php
/**
 * 注册资格
 * @2011-5-4
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include(S_DIR.'include/module/Hr_reg_qualification.php');

include(SET_DIR.'setup_htfrom.php');
include(SET_DIR.'setup_audit_iso.php');
include(SET_DIR.'setup_audit_ver.php');
include(SET_DIR.'setup_hr_reg_qualification.php');
include(SET_DIR.'setup_hr_qualification_type.php');

GrepUtil::InitGP(array('id','hr_id','page','core'));

Power::CkPower('H1E');
Power::xt_htfrom($hr_id,'hr');

$width='600px';
$Hr_information = new Hr_information();
$result_ren = $Hr_information->query($hr_id);

$url = "index.php?m=hr&do=hr_reg_qualification&core=$core&id=$id&";
$params = array(
	'search' => " and hr_id=$hr_id",
	'url' => $url,
);

$Hr_reg_qualification = new Hr_reg_qualification();
$result = $Hr_reg_qualification->query($id);
$result[online] == '0' ? $online_ck02 = 'checked="checked"' : $online_ck01 = 'checked="checked"';

if($result[yearok]=='2')
{
	$yearok_ck01 = 'checked="checked"';
}elseif($result[yearok]=='8'){
	$yearok_ck02 = 'checked="checked"';
}else
{$yearok_ck03 = 'checked="checked"';}
// 个人信息导航代码
$p_url = Personal::Navigation($hr_id);
include TEMP.'header.htm';
include TEMP.'hr/hr_reg_qualification_edit.htm';
include TEMP.'footer.htm';
?>