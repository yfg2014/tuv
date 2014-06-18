<?php
!defined('IN_SUPU') && exit('Forbidden');
include (S_DIR.'include/module/XmTaskReturnRecord.php');
Power::CkPower('M0E');

$value = GrepUtil::InitGP(array('id','zuzhi_id','taskId','if_record','record_date','other'));

$s = new XmTaskReturnRecord();

if ($id > 0) {
	$re = $s->update($id,$value);
} else {
	$re = $s->add($value);
}

Url::goto_url('index.php?m=audit&do=return_record_list', '保存成功');
?>