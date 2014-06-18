<?php

!defined('IN_SUPU') && exit('Forbidden');

GrepUtil::InitGP(array('id','empName','taskBeginDate'));

Power::CkPower('Z8D');
DBUtil::Del($db, $dbtable['xm_auditor'], "id='{$id}'");

LogRW::logWriter( '审核员 '.$empName.' 值班时间'.$taskBeginDate.' 值班删除');
Url::goto_url('index.php?m=audit&do=on_duty_list', '删除成功');

?>