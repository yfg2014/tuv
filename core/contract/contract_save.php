<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once SET_DIR.'setup_audit_ver.php';

Power::CkPower('B0E');

GrepUtil::InitGP(array('ht_id','zuzhi_id','hurry','first','htcode','htfrom','htdate','auditplandate','htcheck','audit_kind','other','online'));
GrepUtil::InitGP(array('htxm_id','htxmcode','audit_ver','audit_type','re_views','iso_people_num','qy_renzhengfanwei','mark','zjg','zjg_name','zjg_sdate','zjg_no','zjg_assess_date','zjg_edate','product','product_ver','key_part','audit_rule','product_ce','manuid','proid','kind','manu_address','pro_address','UnitNo','shanjiangtiaokuan','run_date','auditEnd_date','certEnd_date'));

function ver_iso($ver){
	global $setup_audit_ver;
	foreach($setup_audit_ver as $k=>$v){
		if($k == $ver){
			$rst = $v['iso'];
			break;
		}
	}
	return $rst;
}

foreach($audit_ver as $v){
	$iso []= ver_iso($v);
}

//排错特殊处理
include_once SET_DIR.'setup_audit_iso.php';
foreach($iso as $s=>$t){
	$mark_temp = $mark_temp_sel = array();
	$mark_temp = explode(',',$setup_audit_iso[$t]['mark']); //该体系拥有的认可标志
	$mark_temp_sel = explode(',',$mark[$s]); //所选择的认可标志
	foreach($mark_temp_sel as $v){
		!in_array($v,$mark_temp) && $error []= $t.'体系不具备认可标志'.Cache::cache_mark($v);
	}
}

//合同数据保存
$value = array(
	'zuzhi_id' => $zuzhi_id,
//	'htcode' => $htcode,
	'hurry' => $hurry,
	'first' => $first,
	'htdate' => $htdate,
	'htfrom' => $htfrom,
	'auditplandate' => $auditplandate,
	'htcheck' => $htcheck,

	'other' => $other,
	'iso' => implode(',',$iso),
	'online' => $online,
	'zd_ren' => $_SESSION['username'],
	'zd_time' => date('Y-m-d'),
);

$Contract = new  Contract();
$ht_id = $Contract->save($ht_id,$value);


//合同项目数据保存

function mark($mark)
{
	$mark == '' ? $mark = '00' : $mark;
	return $mark;
}
$ContractItem = new ContractItem();
foreach ($audit_type as $k => $v){

	$htxm = $db->get_one("SELECT id FROM {$dbtable['xm_item']} WHERE htxm_id='{$htxm_id[$k]}' and htxm_id>0");
	if($htxm['id'] == ''){
		$qy = $db->get_one("SELECT eipro_address FROM mk_company WHERE id='$zuzhi_id'");
		$manuid[$k] == '' && $manuid[$k] = $zuzhi_id;
		$manu_address[$k] == '' && $manu_address[$k] = $qy['eipro_address'];
		$proid[$k] == '' && $proid[$k] = $zuzhi_id;
		$pro_address[$k] == '' && $pro_address[$k] = $qy['eipro_address'];
		$value = array(
			'zuzhi_id' => $zuzhi_id,
			'ht_id' => $ht_id,
			'htcode' => $htcode,
//			'htxmcode' => $htxmcode[$k],
			'iso' => ver_iso($audit_ver[$k]),
			'audit_ver' => $audit_ver[$k],
			'audit_type' => $audit_type[$k],
			'htfrom' => $htfrom,
			're_views' => $re_views[$k],
			'iso_people_num' => $iso_people_num[$k],
			'shanjiangtiaokuan' => $shanjiangtiaokuan[$k],
			'run_date' => $run_date[$k],
			'mark' => mark($mark[$k]),
			'qy_renzhengfanwei' => $qy_renzhengfanwei[$k],
			'zjg' => $zjg[$k],
			'zjg_name' => $zjg_name[$k],
			'zjg_sdate' => $zjg_sdate[$k],
			'zjg_edate' => $zjg_edate[$k],
			'zjg_no' => $zjg_no[$k],
			'zjg_assess_date' => $zjg_assess_date[$k],
			'product' => $product[$k],
			'product_ver' => $product_ver[$k],
			'key_part' => $key_part[$k],
			'audit_rule' => $audit_rule[$k],
			'product_ce' => $product_ce[$k],
			'auditEnd_date' => $auditEnd_date[$k],
			'certEnd_date' => $certEnd_date[$k],
//			'product_test' => $product_test[$k],
			'manuid' => $manuid[$k],
			'manu_address' => $manu_address[$k],
			'proid' => $proid[$k],
			'pro_address' => $pro_address[$k],
			'kind' => $kind[$k],
			'online' => $online
		);

		$ContractItem->save($htxm_id[$k],$value);
	}
}

Url::goto_url("index.php?m=contract&do=contract_list&online=$online", '保存成功');

?>