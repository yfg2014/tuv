<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/CompanyEx.php');

Power::CkPower('A0S');

GrepUtil::InitGP(array('zuzhi_id'));

$width='800px';

$sql_temp = "zuzhi_id = '$zuzhi_id'";
$s = new CompanyEx();
$result = $s->toArray($sql_temp);

$eiregistername_zhu = Cache::cache_company($zuzhi_id);

include TEMP.'header.htm';
include TEMP.'company/qiye_ex_list.htm';
include TEMP.'footer.htm';
?>