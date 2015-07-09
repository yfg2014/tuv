<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Company.php';
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_risk.php';
include_once S_DIR.'include/setup/setup_audit_ver.php';
include_once S_DIR.'include/setup/setup_mark.php';
include_once S_DIR.'include/setup/setup_product_test.php';

GrepUtil::InitGP(array('ht_id','zuzhi_id','op'));

Power::CkPower('B0E');

$Company = new Company();
$rows = $Company->toArray("fatherzuzhi_id='{$zuzhi_id}'");
$rows == '' ? $rows = array() : $rows;
$com = $Company->GetCompany($zuzhi_id,array('htfrom','eiman_amount'));

$htxmtx = $htxmcp = '';
if ($ht_id > 0) {
	
	$xm = $db->get_one("SELECT id FROM `{$dbtable[xm_item]}` WHERE ht_id='{$ht_id}'");
	if($xm['id'] != ''){
		$disabled = 'disabled="disabled"';
	}
	
	$contract = new Contract();
	$ht = $contract->query($ht_id);
	$htfrom = $ht['htfrom'];

	$ContractItem = new ContractItem();
	$htxmtx = $ContractItem->toArray("ht_id='{$ht_id}' AND kind='1'");
	$htxmcp = $ContractItem->toArray("ht_id='{$ht_id}' AND kind='2'");
	
	if ($op == '1'){
		$ht_id = '';
		$disabled = '';
	}

}else{
	$htfrom = $com['htfrom'];
}

$width = '600px';
$params = array('company' => array(), 'finance'=>array());
$id_arr = array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id));

$Information = new Information($id_arr,$width,'',$params);


include TEMP.'header.htm';
include TEMP.'contract/contract_edit.htm';
include TEMP.'footer.htm';
?>