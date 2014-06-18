<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/OtherCosts.php';

Power::CkPower('G3E');

GrepUtil::InitGP(array('id','zuzhi_id','ht_id','type','costs','other'));

$OtherCosts = new OtherCosts();
$params = array(
	'zuzhi_id' => $zuzhi_id,
	'ht_id' => $ht_id,
	'type' => $type,
	'costs' => $costs,
	'other' => $other,
	'zd_ren' => $_SESSION['username'],
	'zd_time' => date("Y-m-d")
);
$OtherCosts->save($id,$params);

Url::goto_url('index.php?m=finance&do=other_costs_list', '保存成功');
?>
