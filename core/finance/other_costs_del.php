<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/OtherCosts.php');

Power::CkPower('G3D');

GrepUtil::InitGP(array('eid','zuzhi_id'));

$OtherCosts = new OtherCosts();
$OtherCosts->delete($eid,$zuzhi_id);
LogRW::logWriter($zuzhi_id, '其他费用删除');

Url::goto_url('index.php?m=finance&do=other_costs_list', '删除成功');
?>
