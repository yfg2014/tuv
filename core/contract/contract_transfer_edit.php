<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Information.php';
include_once S_DIR.'include/module/finance.php';

GrepUtil::InitGP(array('ht_id','zuzhi_id','eid'));

Power::CkPower('B1B');

$finance = new finance();
$result = $finance->query($eid);
$result['finance_item'] = Cache::cache_Finance_item($result['finance_item']);

$width = '600px';
$Information = new Information(array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id)),$width,$height,$params = array('company'=>array(),'contract'=>array()));

include T_DIR.'header.htm';
include T_DIR.'contract/contract_transfer_edit.htm';
include T_DIR.'footer.htm';
?>