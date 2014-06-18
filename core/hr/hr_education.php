<?php

/**
 * 人员学历
 * @2011-5-5
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');			//人员基本信息类
include(S_DIR.'include/module/hr_education.php');			//人员学历类


GrepUtil::InitGP(array('id','hr_id','page','core','iso'));
Power::CkPower('H0S');
Power::xt_htfrom($hr_id,'hr');
$width='800px';
$s = new Hr_information();
$result_ren = $s->query($hr_id);

$url = "index.php?m=hr&do=hr_education&core=$core&hr_id=$hr_id&";

$search_tmp=" and hr_id='$hr_id'";
if (!empty($iso))
{
	$search_tmp=" and hr_id='$hr_id' and iso='$iso' ";
}

$params = array(
	'search' => $search_tmp,
	'url' => $url,
);

$s = new hr_education();
$result = $s->list_audit_code($params);

include T_DIR.'header.htm';
include T_DIR.'hr/hr_education.htm';
include T_DIR.'footer.htm';
?>