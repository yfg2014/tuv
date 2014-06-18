<?php

!defined('IN_SUPU') && exit('Forbidden');

GrepUtil::InitGP(array('qj_id','empName','taskBeginDate'));

Power::CkPower('Z4D');
DBUtil::Del($db, $dbtable['xm_auditor'], "id='{$qj_id}'");

LogRW::logWriter( '审核员 '.$empName.' 请假时间'.$taskBeginDate.' 请假删除');
Url::goto_url('index.php?m=audit&do=ask_for_leave_list', '删除成功');

?>