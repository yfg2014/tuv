<?php
!defined('IN_SUPU') && exit('Forbidden');
include S_DIR.'/include/module/Evaluate.php';

GrepUtil::InitGP(array('id','taskId','pxmid','ht_id','zuzhi_id'));

Power::CkPower('D1D');

$Evaluate = new Evaluate();
$Evaluate->delete($id);
Url::goto_url("index.php?m=assess&do=pd_evaluation_edit&pxmid=$pxmid&taskId=$taskId&ht_id=$ht_id&zuzhi_id=$zuzhi_id", '操作成功');
?>