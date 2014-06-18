<?php
!defined('IN_SUPU') && exit('Forbidden');
GrepUtil::InitGP(array('zuzhi_id'));
include_once S_DIR.'include/module/Company.php';

Power::CkPower('A0D');

$company = new Company();
$company->DelCompany($zuzhi_id);
Url::goto_url('index.php?m=company&do=qiye_list', '删除成功');
?>