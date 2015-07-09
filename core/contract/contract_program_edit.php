<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'/include/module/Information.php';

GrepUtil::InitGP(array('id','ht_id','zuzhi_id'));

Power::CkPower('B0N');

$width = '600px';
$Information = new Information(array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id)),$width,'',$params = array('company'=>array(),'contract' => array()));

include TEMP.'header.htm';
include TEMP.'contract/contract_program_edit.htm';
include TEMP.'footer.htm';
?>

