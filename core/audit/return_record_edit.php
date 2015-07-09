<?php
/**
 * 审核项目回访记录
 */
!defined('IN_SUPU') && exit('Forbidden');
include_once S_DIR.'include/module/Information.php';
include (S_DIR.'include/module/XmTaskReturnRecord.php');

GrepUtil::InitGP(array('id','taskId','zuzhi_id'));
Power::CkPower('M0S');

$width='600px';
$params = array('task' => array(),'company' => array());
$id_arr = array('zuzhi_id'=>$zuzhi_id,'taskId'=>$taskId);
$Information = new Information($id_arr,$width,'',$params);

$XmTaskReturnRecord = new XmTaskReturnRecord();
$result = $XmTaskReturnRecord->query($id);

$result['record_date'] == '' && $result['record_date'] = date("Y-m-d");

include TEMP.'header.htm';
include TEMP.'audit/return_record_edit.htm';
include TEMP.'footer.htm';
?>