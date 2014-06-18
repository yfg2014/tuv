<?php
!defined('IN_SUPU') && exit('Forbidden');
GrepUtil::InitGP(array('zuzhi_id'));
include_once S_DIR.'include/module/CompanyEx.php';

Power::CkPower('A0D');

GrepUtil::InitGP(array('id','zuzhi_id'));

$company_ex = new CompanyEx();
$company_ex->DelCompanyEx($id);

Url::goto_url('index.php?m=company&do=qiye_ex_list&zuzhi_id='.$zuzhi_id, '删除成功');

?>