<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Item.php';
include_once S_DIR.'/include/module/ContractItem.php';

GrepUtil::InitGP(array('xmid','htxm_id','taskId','renzhengfanwei','audit_code','mark','zuzhi_id'));
Power::CkPower('K0W');

$Item = new Item();
$ContractItem = new ContractItem();
foreach ($xmid as $k => $v) {
	$value = array(
		'renzhengfanwei' => $renzhengfanwei[$k],
		'audit_code' => Cache::cache_audit_code($audit_code[$k]),
	);

	$Item->update($v, $value);
	$ContractItem->update($htxm_id[$k], array('mark'=>$mark[$k]));
}

Url::goto_url("index.php?m=auditor&do=auditor_range_edit&taskId=$taskId&zuzhi_id=$zuzhi_id", '操作成功');
?>