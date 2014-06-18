<?php
!defined('IN_SUPU') && exit('Forbidden');
include(S_DIR.'include/module/Wage.php');

Power::CkPower('H2G');

GrepUtil::InitGP(array('eid'));

$Wage = new Wage();
$Wage->delete($eid,$zuzhi_id);
LogRW::logWriter('','人员工资信息删除');

Url::goto_url('index.php?m=hr&do=hr_wage_list', '删除成功');
?>
