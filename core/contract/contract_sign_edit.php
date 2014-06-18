<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';
include_once S_DIR.'include/module/Information.php';

Power::CkPower('B2Q');

GrepUtil::InitGP(array('ht_id','zuzhi_id'));

$contract = new Contract();
$ht = $contract->query($ht_id);
$ht['signdate'] == '0000-00-00' && $ht['signdate']='';

$width = '600px';
$params = array('company' => array(),'contract' => array(),'finance'=>array());
$id_arr = array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id));
$Information = new Information($id_arr,$width,'',$params);

include T_DIR.'header.htm';
include T_DIR.'contract/contract_sign_edit.htm';
include T_DIR.'footer.htm';
?>
