<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Company.php';
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';

GrepUtil::InitGP(array('ht_id','htxm_id','zuzhi_id','op'));

$Company = new Company();
$com = $Company->GetCompany($zuzhi_id);
$com['htfrom'] = Cache::cache_htfrom($com['htfrom']);

$ContractItem = new ContractItem();
if($op == 1){
	$htxm = $ContractItem->toArray("ht_id='{$ht_id}'");
	$htm = 'contract_audit_item.htm';
}else{
	$contract = new Contract();
	$ht = $contract->query($ht_id);
	$plandate = explode('-',$ht['auditplandate']);
	strstr($ht['outsourcing'],'a') && $achceck= 'checked="checked"';
	strstr($ht['outsourcing'],'b') && $bchceck= 'checked="checked"';
	strstr($ht['outsourcing'],'c') && $cchceck= 'checked="checked"';
	strstr($ht['outsourcing'],'d') && $dchceck= 'checked="checked"';
	strstr($ht['outsourcing'],'e') && $echceck= 'checked="checked"';
	
	$htxm = $ContractItem->query($htxm_id);
	switch($htxm['iso']){
		case 'Q': $q = 'checked="checked"'; $qcode = $htxm['audit_code']; $bqcode ='';break;
		case 'E': $e = 'checked="checked"'; $ecode = $htxm['audit_code']; $becode ='';break;
		case 'S': $s = 'checked="checked"'; $scode = $htxm['audit_code']; $bscode ='';break;
		case 'QY': $qy = 'checked="checked"'; $qycode = $htxm['audit_code']; $bqycode ='';break;
		default: $t = 'checked="checked"';
	}
	$htxm['cert_num'] > 0 && $certckec = 'checked="checked"';
	$htxm['cert_num'] == 0 && $htxm['cert_num']= '';
	if($htxm['approach'] != '2'){
		$approach1 = 'checked="checked"';
		$htxm['audit_type'] == '1001' && $chus= 'checked="checked"';
		$htxm['audit_type'] == '1005' && $zrz= 'checked="checked"';
	}else{
		$approach2 = 'checked="checked"';
	}
	
	
	strstr($htxm['mark'],'02') && $UKAS= 'checked="checked"';
	strstr($htxm['mark'],'04') && $ANAB= 'checked="checked"';
	strstr($htxm['mark'],'01') && $CNAS= 'checked="checked"';
	strstr($htxm['mark'],'00') && $wubiao= 'checked="checked"';
	$rundate = explode('-',$htxm['run_date']);
	
	$htm = 'contract_audit_show.htm';
}

$main_title = '打印评审记录';

include TEMP.'header.htm';
include TEMP.'contract/'.$htm;
include TEMP.'footer.htm';
?>