<?php

/**
 *
 * @2011-5-4
 */

!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Hr_information.php');
include(S_DIR.'include/module/Hr_reg_qualification.php');

Power::CkPower('H1E');
$width='800px';
GrepUtil::InitGP(array('id','hr_id','page','core'));
Power::xt_htfrom($hr_id,'hr');
$s = new Hr_information();
$result_ren = $s->query($hr_id);

$url = "index.php?m=hr&do=hr_reg_qualification&core=$core&hr_id=$hr_id&";
$params = array(
	'search' => " and hr_id=$hr_id",
	'url' => $url,
);
//$db_perpage=2;
$s = new Hr_reg_qualification();
$result = $s->list_reg_qualification($params);
// 个人信息导航代码
$p_url = Personal::Navigation($hr_id);
include TEMP.'header.htm';
include TEMP.'hr/hr_reg_qualification.htm';
include TEMP.'footer.htm';
?>