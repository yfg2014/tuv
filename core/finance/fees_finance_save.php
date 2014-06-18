<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/fees_finance.php';
include_once S_DIR.'include/module/FinanceBasket.php';

Power::CkPower('G1E');
GrepUtil::InitGP(array('xmid','cw_online_xmid','eid','cwid'));
$value = GrepUtil::InitGP(array('f_item_id','invoice','invoicemoney','invoicemoneytime','withdraw','feespaid','costtime','other','ht_id','zuzhi_id','money_unit'));//过滤数据，获取数据集

$s = new fees_finance();

if($cwid == ''){
	$cwid = $s->AddFeesFinance($value,$zuzhi_id,$xmid,$cw_online_xmid);
}else{
	$cwid = $s->EditFessFinance($cwid,$zuzhi_id,$value,$xmid,$cw_online_xmid);
}

//收费划拨明细
/*$FinanceBasket = new FinanceBasket();
if($f_item_id != ''){
	$params = array(
		'f_item_id' => $f_item_id,
		'cwid' => $cwid,
		'ht_id' => $ht_id,
		'zuzhi_id' => $zuzhi_id,
		'htfrom' => $htfrom,
		'basket1' => $basket1,
		'basket2' => $basket2,
		'basket3' => $basket3,
		'basket4' => $basket4,
		'zd_ren' => $_SESSION['username'],
		'zd_time' => date("Y-m-d")
		);
	$FinanceBasket->save($eid,$params);
}*/


Url::goto_url('index.php?m=finance&do=fees_finance_edit&ht_id='.$ht_id.'&zuzhi_id='.$zuzhi_id, '保存成功');
?>