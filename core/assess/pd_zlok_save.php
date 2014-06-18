<?php
include_once S_DIR.'include/module/ContractDaoImpl.php';
include_once S_DIR.'include/module/ContractItem.php';

$contract = new contract();
$contractDaoImpl = new ContractDaoImpl();
$contractProject = new contractProject();
$ContractItem = new ContractItem();
$value = '';
//合同数据保存
$value = array(
		'jinji' => $jinji, 'first' => $first, 'htcode' => $htcode,'htcode_2' => $htcode_2, 'iso' => $iso,
		'eientercode' => $eientercode, 'htdate' => $htdate,	'htfrom' => $htfrom,
		'auditplandate' => $auditplandate, 'total' => $total,
		'feiyong_ht' => $feiyong_ht,'feiyong_jd' => $feiyong_jd,'hezuofang' => $hezuofang,
		'other' => $other, 'zd_ren' => $_SESSION['ename'], 'zd_time' => date('Y-m-d H:i:s',time()),
		'online' => '1',
);
if ($ht_id > 0) {
	$contract->setId($ht_id);
	$contractDaoImpl->update($contract,$contract_value);
} else {
	$contractDaoImpl->add($contract,$contract_value);
}

//合同项目数据保存
foreach ($lingyu as $k => $v) {
	$value = '';
	$value = array(
				'htcode' => $htcode,'eientercode' => $eientercode, 'kind' => '1',
				'eiarea_code' => $eiarea_code, 'htfrom' => $htfrom, 'iso' => $lingyu[$k],
				'audit_ver' => $tx_code[$k], 'audit_type' => $audit_type[$k], 'zjg_admin' => $zjg_admin[$k],
				'fengxian' => $fengxian[$k], 're_views' => $re_views[$k], 'number' => $number[$k],
				'mark' => $mark, 'jichurenri' => $jichurenri[$k], 'shanjiangyiju' => $shanjiangyiju[$k],
				'hedingrenri' => $hedingrenri[$k], 'shanjiangtiaokuan' => $shanjiangtiaokuan[$k],'audit_code' => $audit_code[$k],
				'qy_renzhengfanwei' => $qy_renzhengfanwei[$k],'renzhengfanwei' => $renzhengfanwei[$k],
				'renzhengfanwei_e' => $renzhengfanwei_e[$k], 'zjg_name' => $zjg_name,
				'zjg_sdate' => $zjg_sdate[$k], 'zjg_no' => $zjg_no[$k], 'zjg_edate' => $zjg_edate[$k],
				'zjg_assess_date' => $zjg_assess_date[$k], 'other' => $other, 'zd_ren' => $_SESSION['ename'],
				'zd_time' => date('Y-m-d H:i:s',time())
	);
	if ($htxm_id[$k] > 0) {
		$contractProject->setId($htxm_id[$k]);
		$ContractItem->update($contractProject,$value);
	} else {
		$ContractItem->add($contractProject,$value);
	}
}

Url::goto_url('index.php?m=company&do=contract_edit&ht_id='.$contract->getId(), '保存成功');

?>