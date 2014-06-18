<?php

!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/XmTaskReturnRecord.php');
Power::CkPower('M0D');

GrepUtil::InitGP(array('id'));

$s = new XmTaskReturnRecord();
$s->del($id);	

Url::goto_url('index.php?m=audit&do=return_record_list', '删除成功');

?>