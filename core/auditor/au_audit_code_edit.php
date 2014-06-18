<?php
/**
 * 人员专业能力
 * @2011-5-5
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include(S_DIR.'include/module/Hr_audit_code.php');			//人员专业能力类 审核代码

include(SET_DIR.'setup_htfrom.php');						//合同来源 人员来源
include(SET_DIR.'setup_hr_ability_source.php');				//能力来源
include(SET_DIR.'setup_audit_iso.php');						//体系领域
include(SET_DIR.'setup_hr_reg_qualification.php');			//资格类别


GrepUtil::InitGP(array('id','hr_id','page','core'));
Power::CkPower('K0S');
Power::xt_htfrom($hr_id,'hr');
$width='600px';
$s = new Hr_information();
$result_ren = $s->query($hr_id);

$url = "index.php?m=auditor&do=au_audit_code&core=$core&id=$id&";
$params = array(
	'search' => " and hr_id=$hr_id",
	'url' => $url,
);

$s = new Hr_audit_code();
$result = $s->query($id);

		//有效 无效

if($result[online]=='0')
{
	$online_ck = '关闭';
}elseif($result[online]=='1'){
	$online_ck = '有效';
}else{
    $result[online]='2';
    $online_ck = '申请';
}

include T_DIR.'header.htm';
include T_DIR.'auditor/au_audit_code_edit.htm';
include T_DIR.'footer.htm';
?>