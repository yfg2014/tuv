<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'include/module/Item.php';

GrepUtil::InitGP(array('ht_id','zuzhi_id'));

Power::CkPower('B2P');

$Item = new Item();
$Contract = new Contract();
$rows = $Contract->query($ht_id);

$ContractItem = new ContractItem();
$arr = $ContractItem->toArray("ht_id='$ht_id'");

foreach ($arr as $v) {
	$htxm = $db->get_one("SELECT id FROM {$dbtable['xm_item']} WHERE htxm_id='{$v['id']}'");
	if($htxm['id'] == ''){
		if ($v['audit_type'] == '1001' && $v['kind'] == '1') {
			$audit_type = array('0' => '1007', '1' => '1008');
			foreach ($audit_type as $vl) {
				$value = array(
					'htxm_id' => $v['id'],
					'htfrom' => $rows['htfrom'],
					'ht_id' => $v['ht_id'],
					'zuzhi_id' => $zuzhi_id,
					'renzhengfanwei' => $v['renzhengfanwei'],
					'iso' => $v['iso'],
					'audit_ver' => $v['audit_ver'],
					'audit_code' => $v['audit_code'],
					'audit_type' => $vl,
					'auditplandate' => $rows['auditplandate'],
					'product' => $v['product'],
					'product_ver' => $v['product_ver'],
					'auditEnd_date' => $v['auditEnd_date'],
					'certEnd_date' => $v['certEnd_date'],
					'kind' => $v['kind'],
					'ifadd' => $v['ifadd'],
					'cj_ren' => $_SESSION['username'],
					'cj_time' => date('Y-m-d'),
					'online' => '0'
				);

				$Item->add($value);
			}
		} else {
			$value = array(
				'htxm_id' => $v['id'],
				'htfrom' => $rows['htfrom'],
				'ht_id' => $v['ht_id'],
				'zuzhi_id' => $zuzhi_id,
				'renzhengfanwei' => $v['renzhengfanwei'],
				'iso' => $v['iso'],
				'audit_ver' => $v['audit_ver'],
				'audit_code' => $v['audit_code'],
				'audit_type' => $v['audit_type'],
				'auditplandate' => $rows['auditplandate'],
				'product' => $v['product'],
				'product_ver' => $v['product_ver'],
				'auditEnd_date' => $v['auditEnd_date'],
				'certEnd_date' => $v['certEnd_date'],
				'kind' => $v['kind'],
				'ifadd' => $v['ifadd'],
				'cj_ren' => $_SESSION['username'],
				'cj_time' => date('Y-m-d'),
				'online' => '0'
			);

			$Item->add($value);
		}
	}

}

$ContractItem->htApp($zuzhi_id,$ht_id);

Url::goto_url('index.php?m=contract&do=contract_list', '保存成功');
?>