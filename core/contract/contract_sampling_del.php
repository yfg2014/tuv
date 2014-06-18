<?php
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Sampling.php';

Power::CkPower('B3E');

GrepUtil::InitGP(array('id','zuzhi_id'));

$Sampling = new Sampling();
$rows = $Sampling->delete($id);
LogRW::logWriter($zuzhi_id, '删除抽样信息');
$msg = '操作成功';

Url::goto_url('index.php?m=contract&do=contract_sampling_list', $msg);
?>