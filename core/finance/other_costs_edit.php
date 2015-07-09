<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Information.php';
include_once S_DIR.'include/module/OtherCosts.php';
include_once SET_DIR.'setup_other_costs.php';

Power::CkPower('G3E');

GrepUtil::InitGP(array('eid','ht_id','zuzhi_id'));

if($eid != ''){
	$OtherCosts = new OtherCosts();
	$result = $OtherCosts->query($eid);
}

$width = '500px';
$id_arr = array('zuzhi_id'=>$zuzhi_id,'ht_id'=>array($ht_id));
$params = array('company' => array(),'contract' => array());
$Information = new Information($id_arr,$width,'',$params);

include TEMP.'header.htm';
include TEMP.'finance/other_costs_edit.htm';
include TEMP.'footer.htm';
?>