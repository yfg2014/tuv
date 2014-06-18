<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Companyfinance.php');

Power::CkPower('A2D');

GrepUtil::InitGP(array('eid','zuzhi_id'));

$Companyfinance = new Companyfinance();
$Companyfinance->delete($eid,$zuzhi_id);

Url::goto_url('index.php?m=company&do=qiyedengji_finance_list', '删除成功');
?>
