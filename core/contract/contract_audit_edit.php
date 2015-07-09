<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Company.php';
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'/include/module/Information.php';
include_once S_DIR.'include/module/finance.php';
include_once SET_DIR.'setup_finance_item.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_risk.php';
include_once S_DIR.'include/setup/setup_audit_ver.php';
include_once S_DIR.'include/setup/setup_mark.php';

GrepUtil::InitGP(array('ht_id','zuzhi_id','htfrom'));

Power::CkPower('B2E');

$Company = new Company();
$rows = $Company->toArray("fatherzuzhi_id='{$zuzhi_id}'");
$rows == '' ? $rows = array() : $rows;

if ($ht_id > 0) {
	$contract = new Contract();
	$ht = $contract->query($ht_id);
	$ht['ps_time'] = Cache::cache_time_value($ht['ps_time']);
	$htfrom = $ht['htfrom'];

	$ContractItem = new ContractItem();
	$htxmtx = $ContractItem->toArray("ht_id='{$ht_id}' AND kind='1' ORDER BY id ASC");
	$htxmcp = $ContractItem->toArray("ht_id='{$ht_id}' AND kind='2' ORDER BY id ASC");

}else{
	$Company = new Company();
	$com = $Company->GetCompany($zuzhi_id,array('htfrom'));
	$htfrom = $com['htfrom'];

	$contract = new Contract();
	$ht['htcode'] = $contract->Htcode($zuzhi_id);
}

$Information = new Information(array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id)),'600px',$height,$params = array('company'=>array(),'contract' => array(),'finance'=>array()));

$width = '600px';
include TEMP.'header.htm';
include TEMP.'contract/contract_audit_edit.htm';
include TEMP.'footer.htm';
?>