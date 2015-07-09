<?php
!defined('IN_SUPU') && exit('Forbidden');

include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'/include/module/Information.php';

include_once S_DIR.'include/setup/setup_audit_type.php';
include_once S_DIR.'include/setup/setup_audit_iso.php';
include_once S_DIR.'include/setup/setup_risk.php';
include_once S_DIR.'include/setup/setup_audit_ver.php';
include_once S_DIR.'include/setup/setup_mark.php';

GrepUtil::InitGP(array('ht_id','zuzhi_id','htxm_id'));

Power::CkPower('B2E');

$ContractItem = new ContractItem();
$htxm_v = $ContractItem->query($htxm_id);
$zs = $db->get_one("SELECT id FROM {$dbtable['zs_cert']} WHERE htxm_id='{$htxm_id}'");
if($zs['id'] != '' or $zs['id'] == '0'){
	$disabled = 'disabled' ;
}

$Information = new Information(array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id)),'600px',$height,$params = array('company'=>array(),'finance'=>array()));

$width = '600px';
include TEMP.'header.htm';
include TEMP.'contract/contractitem_edit.htm';
include TEMP.'footer.htm';
?>