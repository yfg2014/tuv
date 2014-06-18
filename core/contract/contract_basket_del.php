<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/FinanceBasket.php');

Power::CkPower('B2D');

GrepUtil::InitGP(array('eid','zuzhi_id'));

$FinanceBasket = new FinanceBasket();
$FinanceBasket->delete($eid,$zuzhi_id);
LogRW::logWriter($params['zuzhi_id'],'合同划拨明细费用删除');

Url::goto_url('index.php?m=company&do=qiyedengji_finance_list', '删除成功');
?>
