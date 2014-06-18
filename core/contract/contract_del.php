<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';

Power::CkPower('B0D');

GrepUtil::InitGP(array('zuzhi_id','ht_id'));

$Contract = new Contract();
$rows = $Contract->query($ht_id);

if ($rows['online'] != '3'){
	$Contract->del($ht_id);
	LogRW::logWriter($zuzhi_id, '合同内容删除');
	$msg = '操作成功';
}else{
	$msg = '合同已经审批通过，请撤消审批，再进行此操作';
}

Url::goto_url('index.php?m=contract&do=contract_list&', $msg);
?>