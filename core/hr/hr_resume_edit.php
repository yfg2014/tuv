<?php
/**
 * 人员简历
 * @2011-5-5
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include(S_DIR.'include/module/Hr_resume.php');			//人员简历类

include(SET_DIR.'setup_htfrom.php');						//合同来源 人员来源
include(SET_DIR.'setup_hr_ability_source.php');				//能力来源
include(SET_DIR.'setup_audit_iso.php');						//体系领域
include(SET_DIR.'setup_hr_qualification_type.php');			//资格类别


GrepUtil::InitGP(array('id','hr_id','page','core'));
Power::CkPower('H0E');
Power::xt_htfrom($hr_id,'hr');
$width='500px';
$s = new Hr_information();
$result_ren = $s->query($hr_id);

$url = "index.php?m=hr&do=hr_resume&core=$core&id=$id&";
$params = array(
	'search' => " and hr_id=$hr_id",
	'url' => $url,
);

$s = new hr_resume();
$result = $s->query($id);

$result[online] == '0' ? $online_ck02 = 'checked="checked"' : $online_ck01 = 'checked="checked"';		//有效 无效

if($result[yearok]=='2')
{
	$yearok_ck01 = 'checked="checked"';
}elseif($result[yearok]=='8'){
	$yearok_ck02 = 'checked="checked"';
}


include TEMP.'header.htm';
include TEMP.'hr/hr_resume_edit.htm';
include TEMP.'footer.htm';
?>