<?php
!defined('IN_SUPU') && exit('Forbidden');
include S_DIR.'/include/module/Files.php';

GrepUtil::InitGP(array('pdid','taskId','zuzhi_id','id'));

Power::CkPower('D2D');

$Files = new Files();
$Files->delete($id);

LogRW::logWriter($params['zuzhi_id'], '评定问题删除');

Url::goto_url("index.php?m=assess&do=pd_problem_edit&pdid=$ppdid&taskId=$taskId&zuzhi_id=$zuzhi_id", '操作成功');
?>