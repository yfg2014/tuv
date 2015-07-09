<?php
!defined('IN_SUPU') && exit('Forbidden');
GrepUtil::InitGP(array('htxmcode'));

$htxm = $db->get_one("SELECT zuzhi_id,ht_id FROM ht_contract_item WHERE htxmcode='$htxmcode'");
if($htxm['ht_id']!=''){
	$zs_sql  = "SELECT id,iso,risk FROM ht_contract_item WHERE ht_id='$htxm[ht_id]'";
	$zs_q = $db->query($zs_sql);
	while($zs = $db->fetch_array($zs_q)){
		$zs['iso'] == 'QJ' && $zs['iso'] = 'Q';
		if(!in_array($zs['iso'],$iso_t)){
			$arr []= $zs;
		}
		$iso_t []= $zs['iso'];
	}
}
$arr2 = array(
'Q','E','S','F'
);
$company = Cache::cache_company($htxm['zuzhi_id']);
include TEMP.'header.htm';
include TEMP.'plugins/leastprice.htm';
include TEMP.'footer.htm';

?>