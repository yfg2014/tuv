<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_risk.php';
include_once S_DIR.'include/setup/setup_audit_ver.php';
include_once S_DIR.'include/setup/setup_mark.php';
include_once S_DIR.'include/setup/setup_product_test.php';

GrepUtil::InitGP(array('ht_id'));

Power::CkPower('B0E');

$contract = new Contract();
$ht = $contract->query($ht_id);

$ContractItem = new ContractItem();
$htxmtx = $ContractItem->toArray("ht_id='{$ht_id}' AND kind='1'");
$htxmcp = $ContractItem->toArray("ht_id='{$ht_id}' AND kind='2'");

$width = '600px';
include T_DIR.'header.htm';
include T_DIR.'contract/contract_show2.htm';
include T_DIR.'footer.htm';
?>