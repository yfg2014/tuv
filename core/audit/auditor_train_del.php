<?php

!defined('IN_SUPU') && exit('Forbidden');

GrepUtil::InitGP(array('id','empName','taskBeginDate'));

Power::CkPower('Z7D');
DBUtil::Del($db, $dbtable['xm_auditor'], "id='{$id}'");

LogRW::logWriter( '审核员 '.$empName.' 培训时间'.$taskBeginDate.' 培训删除');
Url::goto_url('index.php?m=audit&do=auditor_train_list', '删除成功');

?>