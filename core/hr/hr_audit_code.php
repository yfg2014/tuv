<?php

/**
 * 人员专业能力
 * @2011-5-5
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');			//人员基本信息类
include(S_DIR.'include/module/Hr_audit_code.php');			//人员专业能力类 审核代码

include(SET_DIR.'setup_htfrom.php');						//合同来源 人员来源
include(SET_DIR.'setup_mark.php');							//认可标志
include(SET_DIR.'setup_audit_iso.php');						//体系领域
include(SET_DIR.'setup_hr_reg_qualification.php');	    //能力来源
GrepUtil::InitGP(array('id','hr_id','page','core','iso','qualification'));

Power::CkPower('H2E');
Power::xt_htfrom($hr_id,'hr');
$width='800px';
$s = new Hr_information();
$result_ren = $s->query($hr_id);

$url = "index.php?m=hr&do=hr_audit_code&core=$core&hr_id=$hr_id&";

$search_tmp=" and hr_id='$hr_id'";
//列出 审核员能评审的体系、资格 导航
$params = array(
	'search' => $search_tmp,
	'url' => $url,
);
$s = new Hr_audit_code();
$re = $s->list_audit_code($params);
foreach($re['data'] AS $vv){
	$iso_arr []= $vv['iso'];
	$qualification_arr []= $vv['qualification'];
}

$search_tmp2=" and hr_id='$hr_id'";	
if (!empty($iso)){	$search_tmp2=" and hr_id='$hr_id' and iso='$iso' "; }
if(!empty($qualification)){ $search_tmp2=" and hr_id='$hr_id' and qualification='$qualification' "; }

$params2 = array(
	'search' => $search_tmp2,
	'url' => $url,
);

$s = new Hr_audit_code();
$result = $s->list_audit_code($params2);

// 个人信息导航代码
$p_url = Personal::Navigation($hr_id);
include TEMP.'header.htm';
include TEMP.'hr/hr_audit_code.htm';
include TEMP.'footer.htm';
?>