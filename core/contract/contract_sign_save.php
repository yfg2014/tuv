<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Contract.php';

Power::CkPower('B2Q');

GrepUtil::InitGP(array('zuzhi_id','ht_id','signdate','signother','online'));

$value = array(
	'qd_ren' => $_SESSION['username'],
	'signdate' => $signdate,
	'signother' => $signother,
	'online' => $online
);

$Contract = new  Contract();
$ht_id = $Contract->save($ht_id,$value);

LogRW::logWriter($zuzhi_id, '合同签订');

Url::goto_url("index.php?m=contract&do=contract_list&online=$online", '保存成功');
?>
