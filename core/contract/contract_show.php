<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/ContractItem.php';
include_once S_DIR.'include/module/Information.php';

GrepUtil::InitGP(array('ht_id','zuzhi_id'));

Power::CkPower('B0S');

$width = '600px';
$params = array('company' => array(),'contract' => array(),'finance'=>array());
$id_arr = array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id));

$Information = new Information($id_arr,$width,'',$params);

$press = 'off';
$ContractItem = new ContractItem();
$arr = $ContractItem->toArray("ht_id='$ht_id'");

foreach($arr as $v){
	$htxm = $db->get_one("SELECT id FROM {$dbtable['xm_item']} WHERE htxm_id='{$v['id']}'");
	if($htxm['id'] == ''){
		$press = 'on';
	}
}
$press2 = 'off';
$ck_xm = $db->get_one("SELECT id FROM xm_item WHERE ht_id='$ht_id' LIMIT 1");
if ($ck_xm['id'] != ''){$press2 = 'on';}

include T_DIR.'header.htm';
include T_DIR.'contract/contract_show.htm';
include T_DIR.'footer.htm';
?>