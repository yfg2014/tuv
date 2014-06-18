<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/AssessmentItem.php';
//include_once S_DIR.'include/module/ContractAssessmentItem.php';
include_once S_DIR.'include/module/Certificate.php';

GrepUtil::InitGP(array('pdid','zsid','fatherzuzhi_id','eiregistername','eiregistername_e','online','renewal_reason','main_certNo',
'zs_address','zs_address_e','zs_postalcode','manu_company_e','manu_address','manu_address_e','pro_address','pro_address_e','zjg_name',
'audit_code','mark','certNo','certStart','certEnd','firstDate','certNo_y','renewaldate','coverFields','coverFieldsE','zsprintdate','other','op'));
Power::CkPower('E0E');

Power::xt_htfrom($fatherzuzhi_id);
$AssessmentItem = new AssessmentItem();
$rows = $AssessmentItem->query($pdid);

if($rows['kind'] == '2'){
	$htxm = $db->get_one("SELECT manuid,proid FROM ht_contract_item WHERE id='$rows[htxm_id]'");
	$htxm['manuid'] == '0' && $htxm['manuid'] = $fatherzuzhi_id;
	$htxm['proid'] == '0' && $htxm['proid'] = $fatherzuzhi_id;
}

if($online == ''){$online = '01';}
$value = array(
	'pdid' => $pdid,
	'xmid' => $rows['xmid'],
	'htxm_id' => $rows['htxm_id'],
	'zuzhi_id' => $fatherzuzhi_id,
	'ht_id' => $rows['ht_id'],
	'taskId' => $rows['taskId'],
	'htfrom' => $rows['htfrom'],
	'audit_type' => $rows['audit_type'],
	'fatherzuzhi_id' => $rows['zuzhi_id'],
	'eiregistername' => $eiregistername,
	'eiregistername_e'=> $eiregistername_e,
	'manu_company' => Cache::cache_company($htxm['manuid']),
	'manu_company_e' => $manu_company_e,
	'pro_company' => Cache::cache_company($htxm['proid']),
	'pro_company_e' => $pro_address_e,
	'zs_address'=> $zs_address,
	'zs_address_e'=> $zs_address_e,
	'zs_postalcode'=> $zs_postalcode,
	'manu_address'=> $manu_address,
	'manu_address_e'=> $manu_address_e,
	'pro_address'=> $pro_address,
	'pro_address_e'=> $pro_address_e,
	'audit_code' => $rows['audit_code'],
	'iso' => $rows['iso'],
	'audit_ver' => $rows['audit_ver'],
	'mark' => $mark,
	'certNo' => $certNo,
	'certStart' => $certStart,
	'certEnd' => $certEnd,
	'renewaldate' => $renewaldate,
	'certNo_y' => $certNo_y,
	'firstDate' => $firstDate,
	'other' => $other,
	'coverFields' => $coverFields,
	'coverFieldsE' => $coverFieldsE,
	'zsprintdate'=>$zsprintdate,
	'main_certNo' => $main_certNo,
	'renewal_reason'=>$renewal_reason,
	'zjg_name'=>$zjg_name,
	'product' => $rows['product'],
	'product_ver' => $rows['product_ver'],
	'kind' => $rows['kind'],
	'online' => $online,
	'zd_ren' => $_SESSION['username'],
	'zd_date' => date("Y-m-d"),
);

$Certificate = new Certificate();
$id = $Certificate->save($zsid, $value);

$value = array(
	'eiregistername_e'=> $eiregistername_e,
	'zs_address'=> $zs_address,
	'zs_address_e'=> $zs_address_e,
	'zs_postalcode'=>$zs_postalcode
);
DBUtil::update_tb($db,$dbtable['mk_company'],$value,"id='$fatherzuzhi_id'");
if($rows['zuzhi_id'] == $fatherzuzhi_id) {
	$db->query("UPDATE {$dbtable['ht_contract_item']} SET manu_address='$manu_address',pro_address='$pro_address' WHERE id='$rows[htxm_id]'");
	$db->query("UPDATE {$dbtable['pd_xm']} SET zsprintdate='$zsprintdate' WHERE id='$rows[pdid]'");
	$db->query("UPDATE {$dbtable['xm_item']} SET zsprintdate='$zsprintdate' WHERE id='$rows[xmid]'");
}
if($op != ''){
	$msg = '登记证书';
}else{
	$msg = '修改证书';
}
LogRW::logWriter($fatherzuzhi_id,$msg.$rows['iso'].' '.Cache::cache_audit_type($rows['audit_type']).' '.$rows['certNo']);

Url::goto_url('index.php?m=certificate&do=cert_edit&pdid='.$pdid.'&zsid='.$id.'&zuzhi_id='.$rows['zuzhi_id'], '保存成功');
?>