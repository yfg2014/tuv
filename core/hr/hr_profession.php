<?php

/**
 * 人员职称
 * @2011-5-5
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');			//人员基本信息类
include(S_DIR.'include/module/Hr_profession.php');			//人员职称类


GrepUtil::InitGP(array('id','hr_id','page','core','iso'));
Power::CkPower('H0S');
Power::xt_htfrom($hr_id,'hr');
$s = new Hr_information();
$result_ren = $s->query($hr_id);

$url = "index.php?m=hr&do=hr_profession&core=$core&hr_id=$hr_id&";

$search_tmp=" and hr_id='$hr_id'";
if (!empty($iso))
{
	$search_tmp=" and hr_id='$hr_id' and iso='$iso' ";
}


$params = array(
	'search' => $search_tmp,
	'url' => $url,
);

$width='800px';
$s = new hr_profession();
$result = $s->list_audit_code($params);

include TEMP.'header.htm';
include TEMP.'hr/hr_profession.htm';
include TEMP.'footer.htm';
?>