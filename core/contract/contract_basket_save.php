<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/FinanceBasket.php';

GrepUtil::InitGP(array('eid','zuzhi_id','ht_id','f_item_id','htfrom','basket1','basket2','basket3','basket4','remark'));

Power::CkPower('B1B');

$FinanceBasket = new FinanceBasket();
$params = array(
	'zuzhi_id' => $zuzhi_id,
	'f_item_id' => $f_item_id,
	'ht_id' => $ht_id,
	'htfrom' => $htfrom,
	'basket1' => $basket1,
	'basket2' => $basket2,
	'basket3' => $basket3,
	'basket4' => $basket4,
	'remark' => $remark,
	'zd_ren' => $_SESSION['username'],
	'zd_time' => date("Y-m-d")
);
$FinanceBasket->save($eid,$params);

Url::goto_url('index.php?m=contract&do=contract_basket_list', '保存成功');
?>
