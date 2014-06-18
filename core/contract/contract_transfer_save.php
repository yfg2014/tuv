<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/finance.php';

GrepUtil::InitGP(array('eid','zuzhi_id','basket1','basket2','basket3','basket4','basket_remark'));

Power::CkPower('B1B');

$finance = new finance();
$params = array(
	'zuzhi_id' => $zuzhi_id,
	'basket1' => $basket1,
	'basket2' => $basket2,
	'basket3' => $basket3,
	'basket4' => $basket4,
	'basket_remark' => $basket_remark,
	'bk_ren' => $_SESSION['username'],
	'bk_time' => date("Y-m-d")
);
$finance->update($eid,$params);
LogRW::logWriter($zuzhi_id,'合同划拨费用登记');

Url::goto_url('index.php?m=contract&do=contract_transfer', '保存成功');
?>
