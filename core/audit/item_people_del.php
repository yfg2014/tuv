<?php
!defined('IN_SUPU') && exit('Forbidden');
Power::CkPower('C6D');
GrepUtil::InitGP(array('id'));

DBUtil::Del($db, $dbtable['xm_auditor'], "id='{$id}'");
DBUtil::Del($db, $dbtable['xm_auditor_plan'], "auditorId='{$id}'");

LogRW::logWriter('删除审核任务计划 '.$id.' 的审核员');

Url::goto_url('./index.php?m=audit&do=item_people_list', '删除成功');
?>