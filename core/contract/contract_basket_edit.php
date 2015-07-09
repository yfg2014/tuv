<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/finance.php';
include_once S_DIR.'/include/module/Information.php';
include_once S_DIR.'include/module/FinanceBasket.php';

GrepUtil::InitGP(array('eid','f_item_id'));

Power::CkPower('B2Z');

$finance = new finance();
$cwxm = $finance->query($f_item_id);
$cwxm['finance_item'] = Cache::cache_Finance_item($cwxm['finance_item']);
if($eid != ''){
	$FinanceBasket = new FinanceBasket();
	$result = $FinanceBasket->query($eid);
}else{
	$result = $db->get_one("SELECT * FROM {$dbtable['cw_finance_basket']} WHERE f_item_id='{$f_item_id}' ORDER BY id DESC");
}
$width = '600px';
$Information = new Information(array('zuzhi_id'=>$cwxm['zuzhi_id'],'ht_id'=>array($cwxm['ht_id'])),$width,$height,$params = array('company'=>array(),'contract'=>array()));

include TEMP.'header.htm';
include TEMP.'contract/contract_basket_edit.htm';
include TEMP.'footer.htm';
?>