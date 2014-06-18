<?php
include '../include/globals.php';

GrepUtil::InitGP(array('zuzhi_id','xmid','finalItemDate'));

Power::CkPower('C9F');

DBUtil::update_tb($db, $dbtable['xm_item'], array('finalItemDate' => $finalItemDate), "id='{$xmid}'");

LogRW::logWriter($zuzhi_id, '修改监审最后期限日期');
echo $xmid;
exit;
?>
