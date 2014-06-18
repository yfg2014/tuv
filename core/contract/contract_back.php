<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'include/module/Certificate.php';

GrepUtil::InitGP(array('ht_id','zuzhi_id'));

Power::CkPower('B2B');

$Certificate = new Certificate();
$result = $Certificate->toArray("ht_id='{$ht_id}'");
if($result == ''){
	$ContractItem = new ContractItem();
	$ContractItem->htBack($ht_id);
	$msg = '操作成功';
}else{
	$msg = '操作有误';
}
LogRW::logWriter($zuzhi_id, '合同撤销审批');

Url::goto_url('index.php?m=contract&do=contract_list', $msg);
?>