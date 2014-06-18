<?php
//include '../include/globals.php';
include_once S_DIR.'include/module/ContractItem.php';
GrepUtil::InitGP(array('ht_id','htxm_id','zuzhi_id'));
Power::CkPower('B0D');
$ContractItem = new ContractItem();
$rows = $ContractItem->delete($htxm_id,$ht_id);
LogRW::logWriter($zuzhi_id,'合同项目删除');
echo $rows;
exit;
?>