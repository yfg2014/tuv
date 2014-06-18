<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/finance.php');

Power::CkPower('G0E');

//要处理的字段数据
$Fields = array(
'finance_item','contract_money','other','cwid','ht_id','zuzhi_id','kind','basket1','basket2','basket3','basket4'
);

$value = GrepUtil::InitGP($Fields);//过滤数据，获取数据集

unset($value['cwid']);
unset($value['zuzhi_id']);

$finance = new finance();
if($cwid == '' ){
	$finance->AddFinance($value,$zuzhi_id);
}else{
	unset($value['ht_id']);
	$finance->EditFinance($cwid,$zuzhi_id,$value);
}

Url::goto_url("index.php?m=finance&do=finance_edit&zuzhi_id=$zuzhi_id&ht_id=$ht_id&", '保存成功');
?>