<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Companyfinance.php');

GrepUtil::InitGP(array('id','zuzhi_id','htfrom','get_money','get_money_date','invoice_msg','remark','get_money_item'));

Power::CkPower('A1C');

$Companyfinance = new Companyfinance();
$params = array(
	'zuzhi_id' => $zuzhi_id,
	'get_money' => $get_money,
	'htfrom' => $htfrom,
	'get_money_date' => $get_money_date,
	'invoice_msg' => $invoice_msg,
	'remark' => $remark,
	'get_money_item' => $get_money_item,
	'zd_ren' => $_SESSION['username'],
	'zd_time' => date("Y-m-d")
);
$Companyfinance->save($id,$params);

Url::goto_url('index.php?m=company&do=qiyedengji_finance_list', '保存成功');
?>
